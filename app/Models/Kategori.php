<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'kategori_nama'
    );

    public static function getKategori (){
        $kategori = self::all();

        return $kategori;
    }

    public static function getKategoriById (int $kategori_id){
        $kategori = self::find($kategori_id);

        return $kategori;
    }

    public static function createKategori ($data){
        $kategori = self::create($data);

        return $kategori;
    }

    public static function updateKategori (int $kategori_id, $data){
        $kategori = self::find($kategori_id);
        $kategori->update($data);

        return $kategori;
    }

    public static function deleteKategori (int $kategori_id){
        $kategori = self::find($kategori_id);
        $kategori->destroy($kategori);

        return $kategori;
    }

}
