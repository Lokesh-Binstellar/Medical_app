<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
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
        'role_id',
        
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

    /**
     * Get the user that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class, 'id', 'role_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'role_id', 'role_id');
    }



    public function hasRolePermission($module)
    {
        if ($this->permissions()->where('module', $module)->first()) {
            return true;
        }
        return false;
    }

    public function hasRoleCRUDPermission($module, $permission)
    {
        if ($this->permissions()->where([['module', $module], [$permission, 1]])->first()) {
            return true;
        }
        return false;
    }



    public function pharmacies()
    {
        return $this->hasOne(Pharmacies::class);
    }
    public function laboratories()
    {
        return $this->hasOne(Laboratories::class);
    }

    public function deliveryProfile()
    {
        return $this->hasOne(DeliveryPerson::class, 'user_id', 'id');
    }

     public function routeNotificationForFcm()
    {
        return $this->fcm_token;  // column in your users table with device token
    }


    public function labSlots()
{
    return $this->hasMany(LabSlot::class, 'laboratory_id');
}

}
