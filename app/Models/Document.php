<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'application_id',
        'document_type',
        'file_name',
        'file_path',
        'status'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}