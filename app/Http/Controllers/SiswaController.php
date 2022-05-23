<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DataTables;

class SiswaController extends Controller
{
    public function index()
    {
        return view('admin.siswa');
    }

    public function addSiswa(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name'  => 'required|string',
            'role'  => '2',
            'nis'   => 'string',
            'kelas' => 'string',
            'password' => 'string'
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else {
            $siswa = new User();
            $siswa->role = 2;
            $siswa->nis = $request->nis;
            $siswa->name = $request->name;
            $siswa->kelas = $request->kelas;
            $siswa->password_view = $request->password;
            $siswa->password = password_hash($request->password, PASSWORD_DEFAULT);
            
            $query = $siswa->save();

            if(!$query){
                return response()->json(['code'=>0,'msg'=>'Gagal menambahkan data']);
            }else{
                return response()->json(['code'=>1,'msg'=>'Berhasil menambahkan data']);
            }
        }
    }

    public function getSiswa()
    {
        $siswa = User::where('role', 2)->get();

        return DataTables::of($siswa)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        return '<div class="btn-group">
                                    <button class="btn btn-sm btn-primary" data-id="'.$row['id'].'" id="editSiswa">Update</button>
                                    <button class="btn btn-sm btn-danger" data-id="'.$row['id']. '" id="deleteSiswa">Delete</button>
                                </div>';
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
    }

    public function detailSiswa(Request $request)
    {
        $siswa_id = $request->siswa_id;
        $siswaDetails = User::find($siswa_id);
        return response()->json(['details'=>$siswaDetails]);
    }

    public function updateSiswa(Request $request)
    {
        $siswa_id = $request->id;

        $validator = \Validator::make($request->all(), [
            'nis' => 'required',
            'name' => 'required',
            'kelas' => 'required',
            'password_view' => 'required',
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0, 'error'=>$validator->errors()->toArray()]);
        }else {
            $siswa = User::find($siswa_id);
            $siswa->name = $request->name;
            $siswa->kelas = $request->kelas;
            $siswa->password_view = $request->password_view;
            $siswa->password = password_hash($request->password_view, PASSWORD_DEFAULT);

            $query = $siswa->save();

            if($query){
                return response()->json(['code'=>1, 'msg'=>'Berhasil mengubah data']);
            }else{
                return response()->json(['code'=>0, 'msg'=>'Gagal mengubah data']);
            }
        }
    }

    public function deleteSiswa(Request $request)
    {
        $siswa_id = $request->siswa_id;
        $query = User::find($siswa_id)->delete();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'Berhasil menghapus data']);
        }else{
            return response()->json(['code'=>0, 'msg'=>'Gagal menghapus data']);
        }
    }
}
