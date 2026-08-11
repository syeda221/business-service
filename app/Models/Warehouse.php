<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'notes',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
