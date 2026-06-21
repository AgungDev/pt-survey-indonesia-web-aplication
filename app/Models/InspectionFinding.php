<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspectionFinding extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'inspection_findings';

    protected $fillable = [
        'inspection_id',
        'finding_number',
        'finding_description',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    public function photos()
    {
        return $this->hasMany(InspectionPhoto::class);
    }
}
