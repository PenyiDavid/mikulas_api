<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $fillable = ['name', 'age'];
    protected $hidden = ['created_at', 'updated_at'];

    public function presents(){
        return $this->belongsToMany(Present::class)->withPivot('quantity');
    }
}
