<?php

namespace App\Http\Controllers\Tim;

use App\Http\Controllers\Controller;
use App\Models\backend\Tim\DeveloperModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengembangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $developers = DeveloperModel::all();
        return view('backend.tim.index', compact('developers'));
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
            'skill' => 'required|array', // ubah validasi ke array
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
    public function show($id)
    {
        $developer = DeveloperModel::findOrFail($id);
        return view('backend.tim.show', compact('developer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $developer = DeveloperModel::findOrFail($id);
        return view('backend.tim.edit', compact('developer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'skill' => 'required|array',
            'skill.*' => 'string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $developer = DeveloperModel::findOrFail($id);

        if ($request->hasFile('photo')) {
            if ($developer->photo) {
                Storage::delete('public/' . $developer->photo);
            }
            $developer->photo = $request->file('photo')->store('photos', 'public');
        }

        // Gabungkan array skill dengan separator "|"
        $skills = implode('|', $request->skill);

        $developer->update([
            'name' => $request->name,
            'skill' => $skills,
            'photo' => $developer->photo,
        ]);

        return redirect()->route('pengembang.index')->with('success', 'Developer updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $developer = DeveloperModel::findOrFail($id);
        if ($developer->photo) {
            Storage::delete('public/' . $developer->photo);
        }
        $developer->delete();

        return redirect()->route('pengembang.index')->with('success', 'Developer deleted successfully!');
    }
}
