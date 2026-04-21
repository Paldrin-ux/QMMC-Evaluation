<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['name', 'code'];

    public function janitors()
    {
        return $this->belongsToMany(Janitor::class, 'janitor_area');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}