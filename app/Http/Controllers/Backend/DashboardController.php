<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $now1 = date('Y-m-d' . ' 00:00:00');
        $now2 = date('Y-m-d' . ' 23:59:59');
        $pemeriksaans = Pemeriksaan::orderBy('created_at', 'desc')->get();
        $isiTabel = Pemeriksaan::with(['pasien', 'penyakit'])->whereBetween('created_at', [$now1, $now2])->orderBy('created_at', 'desc')->get();

        return view('backend.dashboard.index', [
            'pemeriksaans' => $pemeriksaans,
            'isiTabel' => $isiTabel
        ]);
    }
}
