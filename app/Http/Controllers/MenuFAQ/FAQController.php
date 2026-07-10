<?php

namespace App\Http\Controllers\MenuFAQ;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuFAQ\FAQModel;
use App\Models\backend\ref_kategori;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = FAQModel::with(['kategori'])->select('*');
        if (request()->ajax()) {
            return datatables()->of($query)

                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'preview' => route('faq.show', encrypt($query->id)),
                        'edit' => route('faq.edit', encrypt($query->id)),
                        'destroy' => route('faq.destroy', encrypt($query->id)),
                    ])->render();
                })

                ->rawColumns(['opsi'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.faq.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = ref_kategori::get();

        return view('backend.faq.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            // VALIDASI DATA
            $request->validate([
                'faq_judul' => 'required|unique:faq|max:255',
                'faq_isi' => 'required|max:1000',
                'kategori' => 'required',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'faq_judul' => $request->faq_judul,
                'faq_isi' => $request->faq_isi,
                'kategori' => $request->kategori,
                'penulis' => Auth::user()->name,

            ];

            FAQModel::create($data);

            // redirect to index
            return redirect()->back()->with(['success' => 'Data FAQ Berhasil Disimpan!']);
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->with(['failed' => 'Data FAQ Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = ref_kategori::all();
        $faq = FAQModel::with(['kategori'])->findOrFail(decrypt($id));

        return view('backend.faq.edit', compact('kategori', 'faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // VALIDASI DATA
            $request->validate([
                'faq_judul' => 'required|max:255',
                'faq_isi' => 'required|max:1000',
                'kategori' => 'required',
            ]);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'faq_judul' => $request->faq_judul,
                'faq_isi' => $request->faq_isi,
                'kategori' => $request->kategori,
                'penulis' => Auth::user()->name,

            ];

            FAQModel::findOrFail(decrypt($id))->update($data);

            // $berita = Berita::find($id)->update($data);
            return redirect()->route('faq.index')->with('success', 'Data FAQ berhasil diupdate!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('faq.index')->with(['failed' => 'Data FAQ Gagal Di Update!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            FAQModel::findOrFail(decrypt($id))->delete();

            return redirect()->route('faq.index')->with('success', 'FAQ berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('faq.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada !']);
        }
    }
}
