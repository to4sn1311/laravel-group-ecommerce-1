<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role){
        return $this->roles->where('name', $role)->count() > 0;
    }

    public function hasAnyRole($roleNames)
    {
        return $this->roles()->whereIn('name', $roleNames)->exists();
    }

    public function hasPermission($permissionName)
    {
        return $this->roles()->whereHas('permissions', function($query) use ($permissionName) {
            $query->where('name', $permissionName);
        })->exists();
    }

    /**
     * Scope để lọc người dùng theo role, loại trừ Super Admin
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array|null $roles
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRole($query, $roles = null)
    {
        // Luôn loại trừ Super Admin
        $query->whereDoesntHave('roles', function($q) {
            $q->where('name', 'Super Admin');
        });

        // Nếu không có role cụ thể, chỉ cần loại trừ Super Admin
        if (empty($roles)) {
            return $query;
        }

        // Lọc theo role(s) được chỉ định
        if (is_array($roles)) {
            return $query->whereHas('roles', function ($q) use ($roles) {
                $q->whereIn('name', $roles);
            });
        }

        return $query->whereHas('roles', function ($q) use ($roles) {
            $q->where('name', $roles);
        });
    }
}
