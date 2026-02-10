<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RehabTemplateQuestion extends Model
{
    //
    protected $guarded = [];
    public function questions()
    {
        return $this->hasMany(RehabTemplateQuestion::class);
    }
}
