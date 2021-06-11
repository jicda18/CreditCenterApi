<?php

namespace App\Models;

use App\Traits\UseChangeLog;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiModel extends Model
{
    use HasFactory, SoftDeletes, UseChangeLog;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = DateTime::ISO8601;
}
