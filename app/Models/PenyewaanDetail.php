<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenyewaanDetail extends Model
{
    use HasFactory;

    protected $table = 'penyewaan_detail';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'penyewaan_detail_penyewaan_id',
        'penyewaan_detail_alat_id',
        'penyewaan_detail_jumlah',
        'penyewaan_detail_subharga'
    );

    public static function getPenyewaanDetail (){
        $PenyewaanDetail = self::all();

        return $PenyewaanDetail;
    }

    public static function getPenyewaanDetailById (int $PenyewaanDetail_id){
        $PenyewaanDetail = self::find($PenyewaanDetail_id);

        return $PenyewaanDetail;
    }

    public static function createPenyewaanDetail ($data){
        $PenyewaanDetail = self::create($data);

        return $PenyewaanDetail;
    }

    public static function updatePenyewaanDetail (int $PenyewaanDetail_id, $data){
        $PenyewaanDetail = self::find($PenyewaanDetail_id);
        $PenyewaanDetail->update($data);

        return $PenyewaanDetail;
    }

    public static function deletePenyewaanDetail (int $PenyewaanDetail_id){
        $PenyewaanDetail = self::find($PenyewaanDetail_id);
        $PenyewaanDetail->destroy($PenyewaanDetail);

        return $PenyewaanDetail;
    }

}
