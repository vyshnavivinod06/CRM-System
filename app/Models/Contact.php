<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'business',
        'company_name',
        'source_type',
        'source_id',
    ];

    protected function casts(): array
    {
        return [
            'business' => 'boolean',
        ];
    }
}
