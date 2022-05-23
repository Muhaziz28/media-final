<?php

namespace App\Http\Controllers;

use App\Models\Kd;
use Illuminate\Http\Request;
use DataTables;

class KdController extends Controller
{
    public function index()
    {
        return view('admin.kd');
    }

    public function add(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'kd' => 'required',
            'judul_kd' => 'required',
            'keterangan' => '',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $kd = new Kd();

            $kd->kd = $request->kd;
            $kd->judul_kd = $request->judul_kd;
            $kd->keterangan = $request->keterangan;
            $query = $kd->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => 'Gagal menambahkan data']);
            } else {
                return response()->json(['code' => 1, 'msg' => 'Berhasil menambahkan data']);
            }
        }
    }

    public function getKd()
    {
        $kd = Kd::all();
        return DataTables::of($kd)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                                                <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editKd">Update</button>
                                                <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteKd">Delete</button>
                                          </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function detailKd(Request $request)
    {
        $kd_id = $request->kd_id;
        $kdDetails = Kd::find($kd_id);
        return response()->json(['details' => $kdDetails]);
    }

    public function updateKd(Request $request)
    {
        $kd_id = $request->id;

        $validator = \Validator::make($request->all(), [
            'kd' => 'required|unique:kds,kd,' . $kd_id,
            'judul_kd' => 'required',
            'keterangan' => 'required'
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {

            $kd = Kd::find($kd_id);
            $kd->kd = $request->kd;
            $kd->judul_kd = $request->judul_kd;
            $kd->keterangan = $request->keterangan;
            $query = $kd->save();

            if ($query) {
                return response()->json(['code' => 1, 'msg' => 'kd have Been updated']);
            } else {
                return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
            }
        }
    }

    public function deleteKd(Request $request)
    {
        $kd_id = $request->kd_id;
        $query = Kd::find($kd_id)->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Berhasil menghapus data']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Gagal menghapus data']);
        }
    }
}
