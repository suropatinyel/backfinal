<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PelangganData extends Model
{
    use HasFactory;

    protected $table = 'pelanggan_data';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'pelanggan_data_pelanggan_id',
        'pelanggan_data_jenis',
        'pelanggan_data_file'
    );

    public static function getPelangganData (){
        $pelangganData = self::all();

        return $pelangganData;
    }

    public static function getPelangganDataById (int $pelangganData_id){
        $pelangganData = self::find($pelangganData_id);

        return $pelangganData;
    }

    public static function createPelangganData ($data){
        $pelangganData = self::create($data);

        return $pelangganData;
    }

    public static function updatePelangganData (int $pelangganData_id, $data){
        $pelangganData = self::find($pelangganData_id);
        $pelangganData->update($data);

        return $pelangganData;
    }

    public static function deletePelangganData (int $pelangganData_id){
        $pelangganData = self::find($pelangganData_id);
        $pelangganData->destroy($pelangganData);

        return $pelangganData;
    }

}
