<?php

namespace App\Http\Controllers\MenuPengaturan;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuPengaturan\FooterLinkModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FooterLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = FooterLinkModel::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('status', function ($query) {
                    return $query->is_active
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Nonaktif</span>';
                })
                ->addColumn('opsi', function ($query) {
                    return view('components.datatable-actions', [
                        'edit' => route('footer-link.edit', encrypt($query->id)),
                        'destroy' => route('footer-link.destroy', encrypt($query->id)),
                    ])->render();
                })
                ->rawColumns(['opsi', 'status'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.pengaturan.footer-link.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pengaturan.footer-link.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'label' => 'required|max:100',
                'url' => 'required|max:500',
                'sort_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
            ]);

            $data = [
                'label' => strip_tags($request->label),
                'url' => $request->url,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ];

            FooterLinkModel::create($data);

            Cache::forget('footer_links');

            return redirect()->route('footer-link.index')->with(['success' => 'Tautan footer berhasil ditambahkan!']);
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->withInput()->with(['failed' => 'Tautan footer gagal ditambahkan!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('footer-link.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $footerLink = FooterLinkModel::findOrFail(decrypt($id));

        return view('backend.pengaturan.footer-link.edit', compact('footerLink'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'label' => 'required|max:100',
                'url' => 'required|max:500',
                'sort_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
            ]);

            $data = [
                'label' => strip_tags($request->label),
                'url' => $request->url,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ];

            FooterLinkModel::findOrFail(decrypt($id))->update($data);

            Cache::forget('footer_links');

            return redirect()->route('footer-link.index')->with('success', 'Tautan footer berhasil diperbarui!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('footer-link.index')->with(['failed' => 'Tautan footer gagal diperbarui!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            FooterLinkModel::findOrFail(decrypt($id))->delete();

            Cache::forget('footer_links');

            return redirect()->route('footer-link.index')->with('success', 'Tautan footer berhasil dihapus!');
        } catch (Exception $e) {
            report($e);

            return redirect()->route('footer-link.index')->with(['failed' => 'Data yang dihapus tidak ada!']);
        }
    }
}
