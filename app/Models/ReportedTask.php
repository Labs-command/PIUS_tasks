<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class ReportedTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject',
        'text',
        'answer',
        'reason_comment',
        'author_id',
    ];

    protected $primaryKey = 'task_id';
    protected $keyType = 'string';
    public $incrementing = false;

    public static function boot(): void
    {
        parent::boot();

        self::creating(
            function ($model) {
                if (!$model->task_id) {
                    $model->task_id = Uuid::uuid4()->toString();
                }
            }
        );
    }
}
