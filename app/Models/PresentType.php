<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresentType extends Model
{
    protected $fillable = ['name'];
    
    protected $hidden = ['created_at', 'updated_at'];
    public function presents(){
        return $this->hasMany(Present::class);
    }
}
