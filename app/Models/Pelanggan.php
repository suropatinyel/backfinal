<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'pelanggan_nama',
        'pelanggan_alamat',
        'pelanggan_notelp',
        'pelanggan_email'
    );

    public static function getPelanggan (){
        $pelanggan = self::all();

        return $pelanggan;
    }

    public static function getPelangganById (int $pelanggan_id){
        $pelanggan = self::find($pelanggan_id);

        return $pelanggan;
    }

    public static function createPelanggan ($data){
        $pelanggan = self::create($data);

        return $pelanggan;
    }

    public static function updatePelanggan (int $pelanggan_id, $data){
        $pelanggan = self::find($pelanggan_id);
        $pelanggan->update($data);

        return $pelanggan;
    }

    public static function deletePelanggan (int $pelanggan_id){
        $pelanggan = self::find($pelanggan_id);
        $pelanggan->destroy($pelanggan);

        return $pelanggan;
    }

}
