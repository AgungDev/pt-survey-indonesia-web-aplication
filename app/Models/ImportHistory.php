<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportHistory extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'import_histories';

    protected $fillable = [
        'filename',
        'file_path',
        'total_rows',
        'success_rows',
        'failed_rows',
        'status',
        'approved_by',
        'approved_at',
        'industry_id',
        'company_id',
        'review_comment',
        'reviewed_at',
        'started_at',
        'finished_at',
        'created_by',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'approved_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
