<?php

namespace App\Http\Controllers;

use App\Models\pointsModel;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct()
    {
        $this->points = new pointsModel();
        $this->users = new User();
    }

    public function landingpage()
    {
        $data = [
            'title' => 'PGWL',
            'points_count' => $this->points->count(),
            'users_count' => $this->users->count(),
        ];

        return view('home', $data);
    }

    public function peta()
    {
        $data = [
            'title' => 'Peta',
        ];

        return view('map', $data);
    }

    public function tabel()
    {
        $data = [
            'title' => 'Tabel',
            'points' => $this->points->all(),
        ];

        return view('table', $data);
    }

    public function tentang()
    {
        $data = [
            'title' => 'Tentang',
        ];

        return view('tentang', $data);
    }
}
