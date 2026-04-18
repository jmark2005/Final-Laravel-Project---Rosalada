<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholarship_id',
        'name',
        'description',
        'is_mandatory'
    ];

    protected $casts = [
        'is_mandatory' => 'boolean'
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
}