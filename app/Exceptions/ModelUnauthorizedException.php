<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModelUnauthorizedException extends Exception
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $NotModelException = new ModelNotFoundException("", 0, $this);
        $NotModelException->setModel($this->model);

        throw $NotModelException;
    }
}
