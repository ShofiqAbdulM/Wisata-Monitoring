<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wisata;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->Wisata = new Wisata();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $results = $this->Wisata->allLokasi();
        // $lokasi = [
        //     'id' => $results,
        //     'nama' => $results
        // ];

        $users = User::count();
        $widget = [
            'users' => $users,
            //...
        ];

        return view('home', compact('widget'), compact('results'));
    }
    public function lokasi($id = '')
    {

        $results = $this->Wisata->getLokasi($id);
        return json_encode($results);
    }
}
