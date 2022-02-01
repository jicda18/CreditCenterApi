<?php

namespace App\Exceptions;

use App\Traits\ExceptionRenderResponse;
use Exception;
use Spatie\Permission\Models\Permission;

class PermissionRequiredException extends Exception
{
    use ExceptionRenderResponse;

    public function __construct(string $name)
    {

        $permission = null_prevent_property(Permission::where('name', '=', $name)->first(), 'description', '');

        $this->message = "Require el permiso '{$permission}' para realizar esta acción";
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return $this->response($this->message);
    }
}
