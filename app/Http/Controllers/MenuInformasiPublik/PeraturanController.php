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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

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
                    $url = asset('storage/romadan_file_web/' . $query->file);
                    return '<a href="' . $url . '" target="_blank">' . $query->nomor_peraturan . '</a>';
                })
                ->addColumn('opsi', function ($query) {
                    $preview = route('peraturan.show', encrypt($query->id));
                    $edit = route('peraturan.edit', encrypt($query->id));
                    $hapus = route('peraturan.destroy', encrypt($query->id));
                    return '<div class="d-inline-flex">
											<div class="dropdown">
												<a href="#" class="text-body" data-bs-toggle="dropdown">
													<i class="ph-list"></i>
												</a>

												<div class="dropdown-menu dropdown-menu-end">
													<a href="' . $preview . '" class="dropdown-item">
														<i class="ph-detective me-2"></i>
														Preview
													</a>
													<a href="' . $edit . '" class="dropdown-item">
														<i class="ph-note-pencil me-2"></i>
														Edit
													</a>
													<form action="' . $hapus . '" method="POST">
													' . @csrf_field() . '
													' . @method_field('DELETE') . '
													<button type="submit" name="submit" class="dropdown-item"> <i class="ph-trash me-2"></i> Hapus</button>
													</form>
												</div>
											</div>
										</div>
                ';
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
                'nomor_peraturan' => 'required|unique:peraturan',
                'judul_peraturan' => 'required',
                'file' => 'required|mimes:doc,docx,ppt,pptx,csv,xlx,xls,xlsx,pdf,zip,rar|max:100000',
                'kategori' => 'required',
                'jenis_peraturan' => 'required',
                'tanggal_penetapan' => 'required|date|date_format:Y-m-d',
                'tanggal_berlaku' => 'required|date|after_or_equal:tanggal_penetapan|date_format:Y-m-d',
            ]);

            //UPLOAD FILE
            $file = $request->file('file');
            $file->storeAs('public/romadan_file_web', $file->hashName());

            // SLUG

            $slug = Str::slug($request->judul_peraturan);
            // $slug = Str::of($request->judul_peraturan)->slug('?');

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nomor_peraturan' => $request->nomor_peraturan,
                'judul_peraturan' => $request->judul_peraturan,
                'file' => $file->hashName(),
                'kategori' => $request->kategori,
                'jenis_peraturan' => $request->jenis_peraturan,
                'tanggal_penetapan' => Carbon::parse($request->tanggal_penetapan)->format('Y-m-d'),
                'tanggal_berlaku' => Carbon::parse($request->tanggal_berlaku)->format('Y-m-d'),
                'status_peraturan' => $request->status_peraturan,
                'slug' => $slug,

            ];


            PeraturanModel::create($data);

            //redirect to index
            return redirect()->back()->with(['success' => 'Data Peraturan Berhasil Disimpan!']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Data Peraturan Gagal Disimpan! error :' . $e->getMessage()]);
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
                'nomor_peraturan' => 'required',
                'judul_peraturan' => 'required',
                'file' => 'mimes:doc,docx,ppt,pptx,csv,xlx,xls,xlsx,pdf,zip,rar|max:100000',
                'kategori' => 'required',
                'jenis_peraturan' => 'required',
                'tanggal_penetapan' => 'required|date|date_format:Y-m-d',
                'tanggal_berlaku' => 'required|date|after_or_equal:tanggal_penetapan|date_format:Y-m-d',
            ]);

            // SLUG

            $slug = Str::slug($request->judul_peraturan);

            // TAMPUNGAN REQUEST DATA DARI FORM
            $data = [
                'nomor_peraturan' => $request->nomor_peraturan,
                'judul_peraturan' => $request->judul_peraturan,
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

                //UPLOAD IMAGE
                $file = $request->file('file');
                $file->storeAs('public/romadan_file_web', $file->hashName());

                $data_file = PeraturanModel::findOrFail(decrypt($id));
                File::delete(public_path('storage/romadan_file_web/') . $data_file->file);

                $data = [
                    'file' => $file->hashName(),
                ];
            }

            PeraturanModel::findOrFail(decrypt($id))->update($data);
            return redirect()->route('peraturan.index')->with('success', "Data Peraturan $request->nomor_peraturan berhasil diupdate!");
        } catch (Exception $e) {
            return redirect()->route('peraturan.index')->with(['failed' => 'Data Peraturan Gagal Di Update! error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data_file = PeraturanModel::findOrFail(decrypt($id));
            File::delete(public_path('storage/romadan_file_web/') . $data_file->file);
            PeraturanModel::findOrFail(decrypt($id))->delete();
            return redirect()->route('peraturan.index')->with('success', "Peraturan berhasil dihapus!");
        } catch (Exception $e) {
            return redirect()->route('peraturan.index')->with(['failed' => 'Data Yang Dihapus Tidak Ada ! error :' . $e->getMessage()]);
        }
    }
}
