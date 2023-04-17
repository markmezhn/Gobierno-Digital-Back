<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GenerateUuid;

class RoleUser extends Model
{
    use GenerateUuid;

    protected $table = "role_user";
    
    protected $fillable = [
        'user_id', 'role_id' 
    ];
    
    protected $hidden = [
        'created_at', 'updated_at',
    ]; 

    
    public function role(){
        return $this->belongsTo('App\Role', 'role_id', 'id');
    } 

    
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    } 
}
