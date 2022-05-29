<?php

namespace App\Http\Controllers;

use App\Models\Kd;
use App\Models\Materi;
use Illuminate\Http\Request;
use DataTables;
use Validator;

class MateriController extends Controller
{
    public function index()
    {
        // get all kd
        $data['kd'] = Kd::all();
        return view('admin.materi', $data);
    }

    public function addMateri(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'kd_id' => 'required',
            'judul' => 'required|string',
            'video' => 'required|string',
            'soal'  => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file' => 'required|mimes:pdf',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            // path for image
            $pathImage = 'uploads/image/';
            $image = $request->file('image');
            $filename_image = time() . '.' . $image->getClientOriginalExtension();

            // path for materi/file
            $pathMateri = 'uploads/materi/';
            $file = $request->file('file');
            $filename_file = time() . '.' . $file->getClientOriginalExtension();

            $uploadImage = $image->storeAs($pathImage, $filename_image, 'public');
            $uploadFile = $file->storeAs($pathMateri, $filename_file, 'public');

            Materi::create([
                'kd_id' => $request->kd_id,
                'judul' => $request->judul,
                'video' => $request->video,
                'soal'  => $request->soal,
                'image' => $uploadImage,
                'file' => $uploadFile,
            ]);

            if ($uploadImage || $uploadFile) {
                $notifikasi = [
                    'message' => 'Data berhasil ditambahkan!',
                    'alert' => 'success'
                ];
                return redirect()->route('admin.materi')->with($notifikasi);
            }
        }
    }

    public function deleteMateri(Request $request)
    {
        $materi = Materi::find($request->materi_id);
        $pathImage = 'uploads/image/';
        $pathMateri = 'uploads/materi/';
        $imagePath = $pathImage . $materi->image;
        $materiPath = $pathMateri . $materi->file;
        if ($materi->image != null && \Storage::disk('public')->exists($imagePath)) {
            \Storage::disk('public')->delete($imagePath);
        }
        if ($materi->file != null && \Storage::disk('public')->exists($materiPath)) {
            \Storage::disk('public')->delete($materiPath);
        }

        $query = $materi->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => 'Data berhasil dihapus!']);
        } else {
            return response()->json(['code' => 0, 'msg' => 'Data gagal dihapus!']);
        }
    }

    public function getMateri()
    {
        $materi = Materi::with('kd');
        return DataTables::of($materi)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                                    <button class="btn btn-sm btn-primary" data-id="' . $row['id'] . '" id="editMateri">Update</button>
                                    <button class="btn btn-sm btn-danger" data-id="' . $row['id'] . '" id="deleteMateri">Delete</button>
                                </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
