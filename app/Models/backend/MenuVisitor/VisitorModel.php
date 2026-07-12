<?php

namespace App\Models\backend\MenuVisitor;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VisitorModel extends Model
{
    protected $table = 'visitors';

    protected $guarded = [];

    protected $fillable = [
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'device_type',
        'url',
        'route_name',
        'referrer',
        'session_id',
        'is_bot',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
    ];

    public function scopeHuman(Builder $query): Builder
    {
        return $query->where('is_bot', false);
    }
}
