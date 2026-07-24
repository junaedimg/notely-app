<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'is_pinned',
        'color',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
        ];
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }

    public function getColorHexAttribute(): ?string
    {
        return match($this->color) {
            'yellow' => '#f59e0b',
            'blue' => '#3b82f6',
            'green' => '#22c55e',
            'red' => '#ef4444',
            'purple' => '#a855f7',
            default => null,
        };
    }

    public function getColorBgAttribute(): ?string
    {
        return match($this->color) {
            'yellow' => '#fefce8',
            'blue' => '#eff6ff',
            'green' => '#f0fdf4',
            'red' => '#fef2f2',
            'purple' => '#faf5ff',
            default => null,
        };
    }
}
