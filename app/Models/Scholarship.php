<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'provider',
        'slots',
        'amount',
        'deadline',
        'status'
    ];

    protected $casts = [
        'deadline' => 'date',
        'amount' => 'decimal:2'
    ];

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function getRemainingSlotsAttribute()
    {
        $approvedCount = $this->applications()
            ->where('status', 'approved')
            ->count();
        
        return $this->slots - $approvedCount;
    }
}