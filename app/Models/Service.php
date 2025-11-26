<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'category_id',
        'price',
    ];  

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }   
}
