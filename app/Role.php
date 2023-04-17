<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GenerateUuid;

class Role extends Model
{
    use GenerateUuid;
    
    protected $fillable = [
        'name', 'slug', 'description'
    ];
    
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
