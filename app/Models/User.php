<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role_id', 'is_active'];
    protected $hidden   = ['password', 'remember_token'];
    protected $casts    = [
        'is_active' => 'boolean',
        'password'  => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /** Areas this evaluator is assigned to */
    public function assignedAreas(): BelongsToMany
    {
        return $this->belongsToMany(Area::class, 'evaluator_area');
    }

    /** All janitors in the evaluator's assigned areas (auto, no manual linking) */
    public function getAssignedJanitorsThroughAreasAttribute()
    {
        $areaIds = $this->assignedAreas->pluck('id');
        return Janitor::whereHas('areas', fn($q) => $q->whereIn('areas.id', $areaIds))
            ->with('areas')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evaluated_by');
    }

    public function janitor(): HasOne
    {
        return $this->hasOne(Janitor::class, 'user_id');
    }

    public function isAdmin(): bool     { return $this->role->slug === 'admin'; }
    public function isEvaluator(): bool { return $this->role->slug === 'evaluator'; }
    public function isJanitor(): bool   { return $this->role->slug === 'janitor'; }
}