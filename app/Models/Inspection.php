<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspection extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'inspections';

    protected $fillable = [
        'survey_timestamp',
        'equipment_id',
        'inspector_id',
        'inspection_type',
        'inspection_result',
        'recommendation',
        'unit_photo',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'survey_timestamp' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function findings()
    {
        return $this->hasMany(InspectionFinding::class);
    }

    public function photos()
    {
        return $this->hasMany(InspectionPhoto::class);
    }
}
