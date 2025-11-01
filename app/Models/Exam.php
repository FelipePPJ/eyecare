<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'group_id',
        'laterality',
        'comment',
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
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Relacionamento tem vários" com a model PackagesVsExam
     */
    public function packages()
    {
        return $this->hasMany(PackagesVsExam::class);
    }
}
