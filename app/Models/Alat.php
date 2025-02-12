<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'alat_kategori_id',
        'alat_nama',
        'alat_deskripsi',
        'alat_hargaperhari',
        'alat_stok'
    );

    public static function getAlat (){
        $alat = self::all();

        return $alat;
    }

    public static function getAlatById (int $alat_id){
        $alat = self::find($alat_id);

        return $alat;
    }

    public static function createAlat ($data){
        $alat = self::create($data);

        return $alat;
    }

    public static function updateAlat (int $alat_id, $data){
        $alat = self::find($alat_id);
        $alat->update($data);

        return $alat;
    }

    public static function deleteAlat (int $alat_id){
        $alat = self::find($alat_id);
        $alat->destroy($alat);

        return $alat;
    }

}
