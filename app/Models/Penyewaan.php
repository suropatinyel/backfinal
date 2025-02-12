<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penyewaan extends Model
{
    use HasFactory;

    protected $table = 'penyewaan';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'penyewaan_pelanggan_id',
        'penyewaan_tglsewa',
        'penyewaan_tglkembali',
        'penyewaan_sttspembayaran',
        'penyewaan_sttskembali',
        'penyewaan_totalharga'
    );

    public static function getPenyewaan (){
        $penyewaan = self::all();

        return $penyewaan;
    }

    public static function getPenyewaanById (int $penyewaan_id){
        $penyewaan = self::find($penyewaan_id);

        return $penyewaan;
    }

    public static function createPenyewaan ($data){
        $penyewaan = self::create($data);

        return $penyewaan;
    }

    public static function updatePenyewaan (int $penyewaan_id, $data){
        $penyewaan = self::find($penyewaan_id);
        $penyewaan->update($data);

        return $penyewaan;
    }

    public static function deletePenyewaan (int $penyewaan_id){
        $penyewaan = self::find($penyewaan_id);
        $penyewaan->destroy($penyewaan);

        return $penyewaan;
    }


}
