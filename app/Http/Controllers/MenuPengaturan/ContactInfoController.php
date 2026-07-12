<?php

namespace App\Http\Controllers\MenuPengaturan;

use App\Http\Controllers\Controller;
use App\Models\backend\MenuPengaturan\ContactInfoModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ContactInfoController extends Controller
{
    /**
     * Kontak hanya satu baris (singleton). Redirect langsung ke form edit,
     * membuat baris default kalau belum ada.
     */
    public function index()
    {
        $contact = ContactInfoModel::first() ?? ContactInfoModel::create([]);

        return redirect()->route('contact-info.edit', encrypt($contact->id));
    }

    public function edit(string $id)
    {
        $contact = ContactInfoModel::findOrFail(decrypt($id));

        return view('backend.pengaturan.contact-info.edit', compact('contact'));
    }

    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'email' => 'nullable|email|max:255',
                'whatsapp' => 'nullable|max:50',
                'address' => 'nullable|max:500',
            ]);

            $data = [
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'address' => $request->address ? strip_tags($request->address) : null,
            ];

            ContactInfoModel::findOrFail(decrypt($id))->update($data);

            Cache::forget('footer_contact_info');

            return redirect()->route('contact-info.edit', $id)->with('success', 'Informasi kontak berhasil diperbarui!');
        } catch (Exception $e) {
            report($e);

            return redirect()->back()->withInput()->with(['failed' => 'Informasi kontak gagal diperbarui!']);
        }
    }
}
