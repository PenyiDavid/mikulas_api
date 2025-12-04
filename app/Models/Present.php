<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Present extends Model
{
    protected $fillable = ['name', 'present_type_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function present_type(){
        return $this->belongsTo(PresentType::class);
    }

    public function children(){
        return $this->belongsToMany(Child::class)->withPivot('quantity');
    }
}
