<?php

namespace App\Http\Controllers;

use App\Models\Kd;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {

        $data['siswa'] = User::where('role', '!=', '1')->count();
        $data['kd'] = Kd::all()->count();
        $data['materi'] = Materi::all()->count();

        return view('admin.index', $data);
    }
}
