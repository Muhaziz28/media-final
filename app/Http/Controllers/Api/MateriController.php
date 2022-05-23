<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $kd_id = $request->input('kd_id');

        if($id)
        {
            $materi = Materi::with('kd')->find($id);

            if($materi)
            {
                return ResponseFormatter::success(
                    $materi,
                    'Data materi berhasil didapat!'
                );
            }else{
                return ResponseFormatter::error(
                    null,
                    'Data materi gagal didapat!',
                    404
                );
            }
        }

        $materi = Materi::with('kd');

        if($kd_id)
        {
            $materi = $materi->where('kd_id', $kd_id);
        }
        
        return ResponseFormatter::success(
            $materi->paginate($limit),
            'Data materi berhasil didapat'
        );

    }
}
