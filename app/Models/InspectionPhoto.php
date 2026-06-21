<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspectionPhoto extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'inspection_photos';

    protected $fillable = [
        'inspection_id',
        'finding_id',
        'photo_url',
        'photo_type',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    public function finding()
    {
        return $this->belongsTo(InspectionFinding::class, 'finding_id');
    }
}
