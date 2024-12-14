<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika tidak mengikuti konvensi
    protected $table = 'mahasiswas';

    // Menentukan kolom yang bisa diisi
    protected $fillable = [
        'nama',
        'npm',
        'prodi',
        'foto',
    ];
}
