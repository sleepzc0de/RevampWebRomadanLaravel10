<?php

namespace App\Http\Controllers\UserManajemen;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $role_cek = User::role('HUMAS_PENATAUSAHAAN')->get();
        // dd($role_cek);
        $query = User::select('*');
        if (request()->ajax()) {
            return datatables()->of($query)
                ->addColumn('role_name', function ($query) {
                    return $query->roles->pluck('name')->implode(', ');
                })
                ->addColumn('action', 'kondisibaik.action')
                ->addColumn('opsi', function ($query) {
                    $preview = route('users.show', $query->id);
                    $edit = route('users.edit', $query->id);
                    $hapus = route('users.destroy', $query->id);
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
                ->filterColumn('role_name', function ($query, $keyword) {
                    $query->whereHas('roles', function ($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%');
                    });
                })
                ->rawColumns(['action', 'opsi', 'role_name'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $user = User::find();
        $role = Role::whereIn('name', ['REDAKTUR', 'EDITOR', 'HUMAS_PERSIJA', 'HUMAS_PENGELOLAAN', 'HUMAS_PERENCANAAN', 'HUMAS_PENATAUSAHAAN', 'HUMAS_PENGADAAN', 'HUMAS_PENGADAAN', 'TAMU'])->get();
        $data = ['role' => $role];
        return view('backend.users.tambah_user', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        try {
            //code...
            $request->validate([
                // 'username' => 'required|unique:users',
                // 'nip' => 'required|unique:users|max:18',
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required|confirmed|min:10'
            ]);

            $user =  User::create([
                // 'username' => $request->username,
                // 'nip' => $request->nip,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->role);



            // return redirect()->back()->with(['success' => true, 'message' => 'Data User Berhasil Ditambahkan']);

            return redirect()->back()->with(['success' => 'Data User Berhasil Ditambahkan']);
        } catch (Exception $e) {
            return redirect()->back()->with(['failed' => 'Ada Kesalahan Sistem! error :' . $e->getMessage()]);
            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $showdata = User::findorFail($id);
        // dd($userdata);
        return view('backend.users.show_user', compact('showdata'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $userdata = User::findorFail($id);
        // dd($userdata);
        $role = Role::whereIn('name', ['REDAKTUR', 'EDITOR', 'HUMAS_PERSIJA', 'HUMAS_PENGELOLAAN', 'HUMAS_PERENCANAAN', 'HUMAS_PENATAUSAHAAN', 'HUMAS_PENGADAAN', 'HUMAS_PENGADAAN', 'TAMU'])->get();

        $olduser = $userdata->roles->first();
        $data = ['role' => $role, 'olduser' => $olduser];

        return view('backend.users.edit_user', compact('userdata', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                // 'username' => 'required',
                // 'nip' => 'required|max:18',
                'name' => 'required',
                'email' => 'required',
            ]);

            $user = User::findorFail($id);

            // $user->name = $request->name;


            // $user->username = $request->username;
            // $user->nip = $request->nip;
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->password != "") {
                $user->password = Hash::make($request->password);
            }

            $user->update();

            return redirect()->route('users.index')->with(['success' => 'User Berhasil di Update !']);
        } catch (Exception $e) {
            return redirect()->route('users.index')->with(['failed' => 'User Gagal di Update. error :' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findorFail($id);

        if ($user) {
            $user->delete();
            return redirect()->back()->with(['success' => 'User Berhasil di Hapus !']);
        } else {
            return redirect()->back()->with(['failed' => 'User Tidak Ditemukan']);
        }
    }
}
