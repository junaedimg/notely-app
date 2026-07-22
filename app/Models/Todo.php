<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Todo extends Model
{
    protected $fillable = [
        'note_id',
        'title',
        'description',
        'status',
        'is_important',
        'is_urgent',
        'repeat_type',
        'interval_value',
        'interval_unit',
        'days_of_week',
        'day_of_month',
        'month_of_year',
        'repeat_anchor',
        'end_type',
        'end_date',
        'end_count',
        'completed_count',
        'next_due_at',
        'reminder_time',
        'paused_until',
    ];

    protected function casts(): array
    {
        return [
            'is_important' => 'boolean',
            'is_urgent' => 'boolean',
            'days_of_week' => 'array',
            'end_date' => 'datetime',
            'next_due_at' => 'datetime',
            'paused_until' => 'datetime',
        ];
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(TodoHistory::class);
    }

    public function getQuadrantAttribute(): string
    {
        if ($this->is_important && $this->is_urgent) return 'do';
        if ($this->is_important && !$this->is_urgent) return 'plan';
        if (!$this->is_important && $this->is_urgent) return 'delegate';
        return 'eliminate';
    }
}
