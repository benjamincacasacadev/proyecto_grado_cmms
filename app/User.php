<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\RefreshesPermissionCache;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // =====================================================================
    //                          RELACIONES
    // =====================================================================

    public function rolUser(){
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function pivotUsers(){
        return $this->belongsToMany(WorkOrders::class,'user_work_orders','user_id','work_orders_id');
    }

    // =====================================================================
    //                          FUNCIONES
    // =====================================================================

    public function getFullNameAttribute(){
        return $this->name.' '.$this->ap_paterno.' '.$this->ap_materno;
    }

    public function getName(){
        return '<a href="/users/show/'.code($this->id).'" target="_blank" class="text-yellowdark font-weight-bold">'.$this->fullName.'</a>';
    }

    // =====================================================================
    //                          SCOPES
    // =====================================================================
    public function scopeNombreUsuario($query,$val){
        if ($val!="") {
            $query->where(\DB::raw("CONCAT(COALESCE(name,''), ' ', COALESCE(ap_paterno,''), ' ', COALESCE(ap_materno,''))"), 'like', "%{$val}%");
        }
    }

    public function scopeRolUser($query,$val){
        if ($val!="") {
            $query->whereHas('rolUser', function ($qw) use ($val) {
                $qw->where('name','like','%'.$val.'%');
            });
        }
    }
}
