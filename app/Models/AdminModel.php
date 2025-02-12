<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AdminModel extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'admin_username',
        'admin_password'
    );

    public static function getAdmin (){
        $admin = self::all();

        return $admin;
    }

    public static function getAdminById (int $admin_id){
        $admin = self::find($admin_id);

        return $admin;
    }

    public static function createAdmin ($data){
        $admin = self::create($data);

        return $admin;
    }

    public static function updateAdmin (int $admin_id, $data){
        $admin = self::find($admin_id);
        $admin->update($data);

        return $admin;
    }

    public static function deleteAdmin (int $admin_id){
        $admin = self::find($admin_id);
        $admin->destroy($admin);

        return $admin;
    }

    
}
