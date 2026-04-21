<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Janitor extends Model
{
    protected $fillable = ['name', 'employee_id', 'is_active', 'user_id'];
    protected $casts    = ['is_active' => 'boolean'];

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'janitor_area');
    }

    public function evaluators()
    {
        return $this->belongsToMany(User::class, 'evaluator_janitor');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function userAccount()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}