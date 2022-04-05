<?php

namespace App\Models;

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
}
