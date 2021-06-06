<?php

namespace App\Models;

use App\Traits\UseChangeLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiModel extends Model
{
    use HasFactory, SoftDeletes, UseChangeLog;
}
