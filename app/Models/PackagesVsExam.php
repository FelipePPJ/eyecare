<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PackagesVsExam extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'package_id',
        'exam_id',
        'group_id',
        'laterality',
        'comment',
        'deleted_at',
    ];

    /**
     * Include Mutator
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * Relacionamento "pertence a" com a model Group
     */
    public function groups()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Relacionamento "pertence a" com a model Package
     */
    public function packages()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Relacionamento "pertence a" com a model Exam
     */
    public function exams()
    {
        return $this->belongsTo(Exam::class);
    }
}
