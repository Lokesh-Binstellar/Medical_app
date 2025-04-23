<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'module_permission';
    protected $fillable = [
        'role_id',
        'module',
        'create',
        'read',
        'update',
        'delete'
    ];
    public static function checkCRUDPermissionToUser($checkPR, $checkPermission)
    {
        $loggedInUser = Auth::user();
        $CRUDData = '';

        $isSuper = 0;
        if ($loggedInUser->role_id == 1) {
            $isSuper = 1;
        } else {
            $CRUDData = Permission::where('role_id', $loggedInUser->role_id)->where('module', $checkPR)->value($checkPermission);
        }

        if ($CRUDData == 1 || $isSuper == 1) {
            return true;
        } else {
            return false;
        }
    }


    public static function isSuperAdmin()
    {
        $loggedInUser = Auth::user();
        $isSuper = 0;
        if ($loggedInUser->role_id == 1) {
            $isSuper = 1;
        }
        return $isSuper;
    }
}
