<?php

namespace App\Http\Controllers\Tim;

use App\Http\Controllers\Controller;
use App\Models\backend\Tim\DeveloperModel;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class PengembangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = DeveloperModel::select('*')->get()->map(function ($item) {
            $item->photo = $item->photo ? route('media.blob', ['photos', basename($item->photo)]) : null;

            return $item;
        });
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('opsi', function ($query) {
                    $encryptedId = Crypt::encrypt($query->id);

                    return view('components.datatable-actions', [
                        'preview' => route('pengembang.show', $encryptedId),
                        'edit' => route('pengembang.edit', $encryptedId),
                        'destroy' => route('pengembang.destroy', $encryptedId),
                    ])->render();
                })
                ->rawColumns(['opsi'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('backend.tim.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.tim.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'skill' => 'required|max:100|array', // ubah validasi ke array
            'skill.*' => 'string|max:255', // validasi untuk setiap skill
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        // Gabungkan array skill dengan separator "|"
        $skills = implode('|', $request->skill);

        DeveloperModel::create([
            'name' => $request->name,
            'skill' => $skills,
            'photo' => $photoPath,
        ]);

        return redirect()->route('pengembang.index')->with('success', 'Developer added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $developer = DeveloperModel::findOrFail($id);

        // Enkripsi ulang ID untuk dikirim ke view
        $encryptedId = Crypt::encrypt($id);

        return view('backend.tim.show', compact('developer', 'encryptedId'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $developer = DeveloperModel::findOrFail($id);

            return view('backend.tim.edit', compact('developer'));
        } catch (DecryptException $e) {
            return redirect()->route('pengembang.index')
                ->with('error', 'Invalid request.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $request->validate([
                'name' => 'required|string|max:255',
                'skill' => 'required|max:100|array',
                'skill.*' => 'string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $developer = DeveloperModel::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($developer->photo) {
                    Storage::delete('public/'.$developer->photo);
                }
                $developer->photo = $request->file('photo')->store('photos', 'public');
            }

            $skills = implode('|', $request->skill);
            $developer->update([
                'name' => $request->name,
                'skill' => $skills,
                'photo' => $developer->photo ?? $developer->getOriginal('photo'),
            ]);

            return redirect()->route('pengembang.index')
                ->with('success', 'Developer updated successfully!');
        } catch (DecryptException $e) {
            return redirect()->route('pengembang.index')
                ->with('error', 'Invalid request.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $developer = DeveloperModel::findOrFail($id);
        if ($developer->photo) {
            Storage::delete('public/'.$developer->photo);
        }
        $developer->delete();

        return redirect()->route('pengembang.index')->with('success', 'Developer deleted successfully!');
    }
}
