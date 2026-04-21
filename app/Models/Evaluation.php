<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'janitor_id', 'area_id', 'evaluated_by',
        'eval_date', 'eval_time', 'noted_by', 'comments',
        'section_a_total', 'section_b_total', 'section_c_total',
        'total_score', 'rating_label',
    ];

    protected $casts = ['eval_date' => 'date'];

    public function janitor()   { return $this->belongsTo(Janitor::class); }
    public function area()      { return $this->belongsTo(Area::class); }
    public function evaluator() { return $this->belongsTo(User::class, 'evaluated_by'); }
    public function scores()    { return $this->hasMany(EvaluationScore::class); }

    public function scopeForJanitor($q, int $id)  { return $q->where('janitor_id', $id); }
    public function scopeForArea($q, int $id)      { return $q->where('area_id', $id); }
    public function scopeForEvaluator($q, int $id) { return $q->where('evaluated_by', $id); }

    public function scopeInDateRange($q, ?string $from, ?string $to)
    {
        if ($from) $q->whereDate('eval_date', '>=', $from);
        if ($to)   $q->whereDate('eval_date', '<=', $to);
        return $q;
    }

    public static function computeRatingLabel(int $score): string
    {
        if ($score >= 90) return 'Excellent';
        if ($score >= 70) return 'Satisfactory';
        return 'Needs Improvement';
    }
}
