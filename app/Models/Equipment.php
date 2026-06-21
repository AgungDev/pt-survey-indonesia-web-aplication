<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'equipments';

    protected $fillable = [
        'equipment_name',
        'equipment_category',
        'location',
        'unit_number',
        'serial_number',
        'model_type',
        'brand',
        'capacity',
    ];

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }
}
