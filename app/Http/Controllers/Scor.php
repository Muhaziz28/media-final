<?php

namespace App\Http\Controllers;

use App\Imports\ScorImport;
use App\Models\Nilai;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use DataTables;

class Scor extends Controller
{
    public function index()
    {
        return view('admin.scor');
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $nama_file = $file->getClientOriginalName();
        $file->move('file', $nama_file);

        // dd($file);

        Excel::import(new ScorImport, public_path('/file/' . $nama_file));

        $notifikasi = array(
            'title' => 'Success',
            'message' => 'Data berhasil diimport',
            'alert' => 'success'
        );
        return redirect('admin/scor')->with($notifikasi);
    }

    public function getNilai()
    {
        $nilai = Nilai::all();
        return DataTables::of($nilai)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                                                 <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editNilai">Update</button>
                                                 <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteNilai">Delete</button>
                                           </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function detailNilai(Request $request)
    {
        $nilai_id = $request->nilai_id;
        $nilaiDetails = Nilai::find($nilai_id);
        return response()->json(['details' => $nilaiDetails]);
    }

    public function updateNilai(Request $request)
    {
        $nilai_id = $request->id;

        $validator = \Validator::make($request->all(), [
            'nis' => 'required',
            'nama' => 'required',
            'kelas' => 'required',
            'kd' => 'required',
            'scor' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $nilai = Nilai::find($nilai_id);

            $nilai->nis = $request->nis;
            $nilai->nama = $request->nama;
            $nilai->kelas = $request->kelas;
            $nilai->kd = $request->kd;
            $nilai->scor = $request->scor;
            $query = $nilai->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Gagal mengubah data']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Berhasil mengubah data']);
            }
        }
    }

    public function deleteNilai(Request $request)
    {
        $nilai_id = $request->nilai_id;
        $nilai = Nilai::find($nilai_id);
        $query = $nilai->delete();

        if (!$query) {
            return response()->json(['code' => 0, 'msg' => 'Gagal menghapus data']);
        } else {
            return response()->json(['code' => 1, 'msg' => 'Berhasil menghapus data']);
        }
    }
}
