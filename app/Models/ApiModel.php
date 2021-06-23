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
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * get the model class name.
     *
     * @return string
     */
    public function getClassName()
    {
        return str_replace(__NAMESPACE__ . '\\', '', get_class($this));
    }

    /**
     * get the resource associate to the model.
     *
     * @return string
     */
    public function getResourceClass()
    {
        return "App\Http\Resources\\{$this->getClassName()}Resource";
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        $property = 'uuid';

        if ($this->hasAttribute($property)) {
            return $property;
        }

        return $this->getKeyName();
    }
}
