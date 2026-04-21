<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationScore extends Model
{
    protected $fillable = ['evaluation_id', 'section', 'field_key', 'is_compliant', 'points_earned'];

    protected $casts = ['is_compliant' => 'boolean'];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }
}