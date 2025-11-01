<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'observations',
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
     * Relacionamento tem vários" com a model PackagesVsExam
     */
    public function packages()
    {
        return $this->hasMany(PackagesVsExam::class);
    }

    /**
     * Carga associativa de relacionamento entre pacotes
     *
     * @return void
     */
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'packages_vs_exams')
            ->withPivot('group_id', 'laterality', 'comment')
            ->withTimestamps()
            ->wherePivotNull('deleted_at'); // Garante não carregar na relação as associações apagadas
    }
}
