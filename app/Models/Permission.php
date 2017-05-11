<?php
namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $table = "permissions";

    protected $fillable = [
        'id', 'pid', 'name', 'display_name', 'description'
    ];
}