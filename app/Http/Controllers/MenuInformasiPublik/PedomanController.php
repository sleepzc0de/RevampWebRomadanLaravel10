<?php

namespace App\Http\Controllers\MenuInformasiPublik;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuInformasiPublik\PedomanModel;
use App\Models\backend\RefKategori;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PedomanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = PedomanModel::with('dataKategori')->select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('file_pedoman', function ($query) {
                    $url = asset('storage/romadan_file_web/'.$query->file);

                    return '<a href="'.$url.'" target="_blank">'.e($query->judul_pedoman).'</a>';
                })
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'edit' => route('pedoman.edit', encrypt($query->id)),
                        'destroy' => route('pedoman.destroy', encrypt($query->id)),
                    ])->render();
                })
                ->editColumn('tanggal_terbit', function ($query) {
                    return $query->tanggal_terbit ? Carbon::parse($query->tanggal_terbit)->translatedFormat('d-F-Y') : '-';
                })
                ->rawColumns(['opsi', 'file_pedoman'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.infopub.pedoman.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = RefKategori::all();

        return view('backend.infopub.pedoman.create', compact(['kategori']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul_pedoman' => 'required|max:255',
                'deskripsi' => 'nullable|max:1000',
                'file' => 'required|mimes:doc,docx,ppt,pptx,csv,xlx,xls,xlsx,pdf,zip,rar|max:20480',
                'kategori' => 'nullable|exists:ref_kategori,id_kategori',
                'tanggal_terbit' => 'nullable|date',
            ]);

            $filteredJudul = strip_tags($request->judul_pedoman);
            $filteredDeskripsi = $request->deskripsi ? strip_tags($request->deskripsi) : null;

            $file = $request->file('file');
            $file->storeAs('public/romadan_file_web', $file->hashName());

            $slug = Str::slug($filteredJudul).'-'.Str::random(6);

            $data = [
                'judul_pedoman' => $filteredJudul,
                'deskripsi' => $filteredDeskripsi,
                'file' => $file->hashName(),
                'kategori' => $request->kategori,
                'tanggal_terbit' => $request->tanggal_terbit ? Carbon::parse($request->tanggal_terbit)->format('Y-m-d') : null,
                'slug' => $slug,
            ];

            PedomanModel::create($data);

            return redirect()->back()->with(['success' => 'Data Pedoman Berhasil Disimpan!']);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Data Pedoman Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('pedoman.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = RefKategori::all();
        $pedoman = PedomanModel::findOrFail(decrypt($id));

        return view('backend.infopub.pedoman.edit', compact(['kategori', 'pedoman']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'judul_pedoman' => 'required|max:255',
                'deskripsi' => 'nullable|max:1000',
                'file' => 'nullable|mimes:doc,docx,ppt,pptx,csv,xlx,xls,xlsx,pdf,zip,rar|max:20480',
                'kategori' => 'nullable|exists:ref_kategori,id_kategori',
                'tanggal_terbit' => 'nullable|date',
            ]);

            $filteredJudul = strip_tags($request->judul_pedoman);
            $filteredDeskripsi = $request->deskripsi ? strip_tags($request->deskripsi) : null;

            $data = [
                'judul_pedoman' => $filteredJudul,
                'deskripsi' => $filteredDeskripsi,
                'kategori' => $request->kategori,
                'tanggal_terbit' => $request->tanggal_terbit ? Carbon::parse($request->tanggal_terbit)->format('Y-m-d') : null,
            ];

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $file->storeAs('public/romadan_file_web', $file->hashName());

                $data_file = PedomanModel::findOrFail(decrypt($id));
                File::delete(public_path('storage/romadan_file_web/').$data_file->file);

                $data['file'] = $file->hashName();
            }

            PedomanModel::findOrFail(decrypt($id))->update($data);

            return redirect()->route('pedoman.index')->with('success', "Data Pedoman $request->judul_pedoman berhasil diupdate!");
        } catch (Exception $e) {
            report($e);

            return redirect()->route('pedoman.index')->with(['failed' => 'Data Pedoman Gagal Di Update!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data_file = PedomanModel::findOrFail(decrypt($id));
            File::delete(public_path('storage/romadan_file_web/').$data_file->file);
            PedomanModel::findOrFail(decrypt($id))->delete();

            return redirect()->route('pedoman.index')->with('success', 'Pedoman berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('pedoman.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada !']);
        }
    }
}
