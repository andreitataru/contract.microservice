<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseContract extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contractId', 'hostId', 'studentId'
    ];
}
