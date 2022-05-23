<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Wisata extends Model
{
    public function getLokasi($id = '')
    {
        $results = DB::table('wisata')
            ->select('nama', 'alamat', 'gambar')
            ->where('id', $id)
            ->get();
        return $results;
    }
    public function allLokasi()
    {
        $results = DB::table('wisata')
            //     ->select('id', 'nama')
            // // ->where('id', 'like', '%' . request('search') . '%')
            //     ->get();
            // return $results;

            // ->select('id', 'nama')
            ->where('nama', 'LIKE', '%' . request('search') . '%')
            ->orWhere('id', 'LIKE', '%' . request('search') . '%')
            ->get();
        return $results;
    }
}
