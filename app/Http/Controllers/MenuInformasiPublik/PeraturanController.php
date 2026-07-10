<?php

namespace App\Http\Controllers\MenuInformasiPublik;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuInformasiPublik\PeraturanModel;
use App\Models\backend\MenuReferensi\ref_jenis_peraturan;
use App\Models\backend\MenuReferensi\ref_peraturan_status;
use App\Models\backend\ref_kategori;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PeraturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = PeraturanModel::with(['kategori', 'data_jenis_peraturan', 'data_status_peraturan'])->select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                ->addColumn('file_peraturan', function ($query) {
                    $url = asset('storage/romadan_file_web/'.$query->file);

                    return '<a href="'.$url.'" target="_blank">'.$query->nomor_peraturan.'</a>';
                })
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'preview' => route('peraturan.show', encrypt($query->id)),
                        'edit' => route('peraturan.edit', encrypt($query->id)),
                        'destroy' => route('peraturan.destroy', encrypt($query->id)),
                    ])->render();
                })

                ->editColumn('tanggal_penetapan', function ($query) {
                    return Carbon::parse($query->tanggal_penetapan)->translatedFormat('d-F-Y');
                })

                // ->editColumn('tanggal_berlaku', function ($query) {
                //     return date('d-F-Y', strtotime($query->tanggal_berlaku));
                // })
                ->editColumn('tanggal_berlaku', function ($query) {
                    return Carbon::parse($query->tanggal_berlaku)->translatedFormat('d-F-Y');
                })

                ->rawColumns(['opsi', 'file_peraturan'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.infopub.peraturan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = ref_kategori::all();
        $data_jenis_peraturan = ref_jenis_peraturan::all();
        $data_status_peraturan = ref_peraturan_status::all();
        // dd($status_peraturan);

        return view('backend.infopub.peraturan.create', compact(['kategori', 'data_jenis_peraturan', 'data_status_peraturan']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            // VALIDASI DATA
            $request->validate([
                'nomor_peraturan' => 'required|unique:peraturan|max:100',
                'judul_peraturan' => 'required|max:255',
                'file' => 'required|mimes:doc,docx,ppt,pptx,csv,xlx,xls,xlsx,pdf,zip,rar|max:100000',
                'kategori' => 'required',
                'jenis_peraturan' => 'required',
                'tanggal_penetapan' => 'required|date|date_format:Y-m-d',
                'tanggal_berlaku' => 'required|date|after_or_equal:tanggal_penetapan|date_format:Y-m-d',
            ], [
                'nomor_peraturan.required' => 'Nomor peraturan harus diisi.',
                'nomor_peraturan.unique' => 'Nomor peraturan sudah digunakan.',
                'judul_peraturan.required' => 'Judul peraturan harus diisi.',
                'file.required' => 'File harus diunggah.',
                'file.mimes' => 'File harus berupa dokumen (doc, docx), presentasi (ppt, pptx), spreadsheet (csv, xlsx), PDF, ZIP, atau RAR.',
                'file.max' => 'Ukuran file tidak boleh melebihi 100 MB.',
                'kategori.required' => 'Kategori peraturan harus dipilih.',
                'jenis_peraturan.required' => 'Jenis peraturan harus dipilih.',
                'tanggal_penetapan.required' => 'Tanggal penetapan harus diisi.',
                'tanggal_penetapan.date' => 'Format tanggal penetapan tidak valid.',
                'tanggal_penetapan.date_format' => 'Format tanggal penetapan harus YYYY-MM-DD (contoh: 2024-05-16).',
                'tanggal_berlaku.required' => 'Tanggal berlaku harus diisi.',
                'tanggal_berlaku.date' => 'Format tanggal berlaku tidak valid.',
                'tanggal_berlaku.after_or_equal' => 'Tanggal berlaku harus setelah atau sama dengan tanggal penetapan.',
                'tanggal_berlaku.date_format' => 'Format tanggal berlaku harus YYYY-MM-DD (contoh: 2024-05-16).',
            ]);

            // Filter HTML tags from input
            $filteredNomor = strip_tags($request->nomor_peraturan);
            $filteredJudul = strip_tags($request->judul_peraturan);

            // UPLOAD FILE
            $file = $request->file('file');
            $file->storeAs('public/romadan_file_web', $file->hashName());

            // SLUG

            $slug = Str::slug($filteredJudul);
            // $slug = Str::of($request->judul_peraturan)->slug('?');

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nomor_peraturan' => $filteredNomor,
                'judul_peraturan' => $filteredJudul,
                'file' => $file->hashName(),
                'kategori' => $request->kategori,
                'jenis_peraturan' => $request->jenis_peraturan,
                'tanggal_penetapan' => Carbon::parse($request->tanggal_penetapan)->format('Y-m-d'),
                'tanggal_berlaku' => Carbon::parse($request->tanggal_berlaku)->format('Y-m-d'),
                'status_peraturan' => $request->status_peraturan,
                'slug' => $slug,

            ];

            PeraturanModel::create($data);

            // redirect to index
            return redirect()->back()->with(['success' => 'Data Peraturan Berhasil Disimpan!']);
        } catch (ValidationException $e) {
            // Validation failed, return to previous page with errors and input data
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Data Peraturan Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = ref_kategori::all();
        $jenis_peraturan = ref_jenis_peraturan::all();
        $status_peraturan = ref_peraturan_status::all();
        $peraturan = PeraturanModel::with(['kategori', 'data_jenis_peraturan', 'data_status_peraturan'])->findOrFail(decrypt($id));

        return view('backend.infopub.peraturan.edit', compact(['kategori', 'jenis_peraturan', 'status_peraturan', 'peraturan']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'nomor_peraturan' => 'required|max:100',
                'judul_peraturan' => 'required|max:255',
                'file' => 'mimes:doc,docx,ppt,pptx,csv,xlx,xls,xlsx,pdf,zip,rar|max:100000',
                'kategori' => 'required',
                'jenis_peraturan' => 'required',
                'tanggal_penetapan' => 'required|date|date_format:Y-m-d',
                'tanggal_berlaku' => 'required|date|after_or_equal:tanggal_penetapan|date_format:Y-m-d',
            ]);

            // Filter HTML tags from input
            $filteredNomor = strip_tags($request->nomor_peraturan);
            $filteredJudul = strip_tags($request->judul_peraturan);

            // SLUG

            $slug = Str::slug($filteredJudul);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nomor_peraturan' => $filteredNomor,
                'judul_peraturan' => $filteredJudul,
                'kategori' => $request->kategori,
                'jenis_peraturan' => $request->jenis_peraturan,
                'tanggal_penetapan' => Carbon::parse($request->tanggal_penetapan)->format('Y-m-d'),
                'tanggal_berlaku' => Carbon::parse($request->tanggal_berlaku)->format('Y-m-d'),
                'status_peraturan' => $request->status_peraturan,
                'slug' => $slug,

            ];

            if ($request->hasFile('file')) {
                $request->validate([
                    'file' => 'mimes:csv,xlx,xls,xlsx,pdf,zip,rar|max:250000',
                ], [
                    'file.mimes' => 'File hanya diperbolehkaan berekstensi CSV, XLX, XLS, XLSX, PDF, ZIP, RAR',
                ]);

                // UPLOAD IMAGE
                $file = $request->file('file');
                $file->storeAs('public/romadan_file_web', $file->hashName());

                $data_file = PeraturanModel::findOrFail(decrypt($id));
                File::delete(public_path('storage/romadan_file_web/').$data_file->file);

                $data = [
                    'file' => $file->hashName(),
                ];
            }

            PeraturanModel::findOrFail(decrypt($id))->update($data);

            return redirect()->route('peraturan.index')->with('success', "Data Peraturan $request->nomor_peraturan berhasil diupdate!");
        } catch (Exception $e) {
            report($e);

            return redirect()->route('peraturan.index')->with(['failed' => 'Data Peraturan Gagal Di Update!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data_file = PeraturanModel::findOrFail(decrypt($id));
            File::delete(public_path('storage/romadan_file_web/').$data_file->file);
            PeraturanModel::findOrFail(decrypt($id))->delete();

            return redirect()->route('peraturan.index')->with('success', 'Peraturan berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('peraturan.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada !']);
        }
    }
}
