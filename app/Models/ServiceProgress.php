<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProgress extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_progress';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'service_key',
        'status',
        'current_step',
        'payload',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    /**
     * Get the user that owns the service progress.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
