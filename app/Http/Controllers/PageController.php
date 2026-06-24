<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\pointsModel;

class PageController extends Controller
{
    protected $pointsModel;

    public function __construct()
    {
        $this->pointsModel = new pointsModel();
    }

    /**
     * Landing / home view (simple dashboard)
     */
    public function landingpage()
    {
        // 1. Menghitung jumlah Puskesmas (yang namanya mengandung kata Puskesmas)
        $puskesmas_count = DB::table('points')
            ->where('name', 'LIKE', '%Puskesmas%')
            ->count();

        // 2. Menghitung jumlah Rumah Sakit (Faskes selain Puskesmas)
        $points_count = DB::table('points')
            ->where('name', 'NOT LIKE', '%Puskesmas%')
            ->count();

        // 3. Menghitung jumlah User terdaftar secara dinamis dari tabel users
        $users_count = DB::table('users')->count();

        // Mengirimkan ketiga data kuantitas ke halaman beranda (home.blade.php)
        return view('home', compact('points_count', 'puskesmas_count', 'users_count'));
    }

    /**
     * Peta view
     */
    public function peta()
    {
        return view('map');
    }

    /**
     * Tabel view
     */
    public function tabel()
    {
        $points = DB::table('points')
            ->select('id', 'name', 'description', 'image', 'created_at')
            ->get()
            ->map(function ($item) { return (array) $item; })
            ->toArray();

        return view('table', ['points' => $points]);
    }

    /**
     * Tentang view
     */
    public function tentang()
    {
        return view('tentang');
    }
}
