<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Get the roles that belong to the permission.
     */
    public function roles(){
        return $this->belongsToMany(Role::class);
    }
}
