<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelUnauthorizedException;
use App\Http\Resources\CollectionResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const WITH_DELIMITER = ',';

    private $page_size_key = 'per_page';
    private $page_number_key = 'page';
    private $sort_field_key = 'sort_by';
    private $sort_direction_key = 'sort_direction';
    private $with_key = 'include';

    /**
     * The resource to transform model.
     *
     * @var \App\Http\Resources\ApiResource resource
     */
    protected $resource;

    /**
     * Petition request.
     *
     * @var \Illuminate\Http\Request request
     */
    protected $request;

    /**
     * Petition request.
     *
     * @var array http_query_keys
     */
    protected $http_query_keys;

    /**
     * Petition request.
     *
     * @var string default_key_sort
     */
    protected $default_key_sort;

    /**
     * function name that don't required Authentication
     *
     * @var string[] withoutAuth
     */
    protected $withoutAuth = [];

    public function __construct()
    {
        $this->request = request();

        $this->http_query_keys = [
            $this->page_size_key,
            $this->page_number_key,
            $this->sort_field_key,
            $this->sort_direction_key,
        ];

        // Todo: uncomment
        // $this->middleware('auth:api')->except($this->withoutAuth);
        $this->middleware('transform:' . $this->resource);
    }

    protected function showOne(Model $model, int $status_code = 200)
    {
        if (!$model->authorized(auth_company(), Auth::user())) {
            throw new ModelUnauthorizedException($model);
        }

        return (new $this->resource($model))
            ->response()
            ->setStatusCode($status_code);
    }

    protected function idx(Builder $query)
    {
        $query = $this->filter($query);

        $query = $this->sort($query);

        $items = $this->paginate($query);

        return $this->showAll($items);

        // Todo: implement authorized
    }

    protected function change(Request $request, array $rules, Model $model, bool $store)
    {
        $this->validate($request, $rules);

        $validations = $this->otherValidations($request, $model, $store);

        if ($validations !== null && count($validations) > 0) {
            throw ValidationException::withMessages($validations);
        }

        $data = $this->requestToArray($request, $store);

        $model->fill($data);

        $model = $this->beforeToSave($request, $model, $store);

        $model->save();

        $model = $this->afterToSave($request, $model, $store);

        return $this->showOne($model, $store ? 201 : 200);
    }

    protected function filter(Builder $query): Builder
    {
        $model_keys = $this->resource::getModelKey();
        $filter_options = $this->request->only($model_keys->all());

        if (count($filter_options) === 0) {
            return $query;
        }

        foreach ($filter_options as $field => $value) {
            /**
             * if start with '**' or end with '**' then like
             * i.e: &name=**abc then get all where name start with 'abc'
             *      &name=abc** then get all where name end with 'abc'
             *      &name=**abd** then get all where name contains 'abc'
             */
            if (substr($value, 0, 2) === '**' || substr($value, strlen($value) - 2) === '**') {
                $val = $value;
                if (substr($value, 0, 2) === '**') {
                    $val = '%' . substr($val, 2);
                }

                if (substr($value, strlen($value) - 2) === '**') {
                    $val = substr($val, 0, -2) . '%';
                }

                $query = $query->where($field, 'like', $val);
                continue;
            }

            /**
             * if start with '~>' then >=
             * i.e: &age=~>18 then get all where age greater or equal than 18
             */
            if (substr($value, 0, 2) === '~>') {
                $val = substr($value, 2);
                $query = $query->where($field, '>=', $val);
                continue;
            }

            /**
             * if start with '~<' then <=
             * i.e: &age=~<18 then get all where age lower or equal than 18
             */
            if (substr($value, 0, 2) === '~<') {
                $val = substr($value, 2);
                $query = $query->where($field, '<=', $val);
                continue;
            }

            /**
             * if start with '>' then >
             * i.e: &age=>18 then get all where age greater than 18
             */
            if (substr($value, 0, 1) === '>') {
                $val = substr($value, 1);
                $query = $query->where($field, '>', $val);
                continue;
            }

            /**
             * if start with '<' then <
             * i.e: &age=<18 then get all where age lower than 18
             */
            if (substr($value, 0, 1) === '<') {
                $val = substr($value, 1);
                $query = $query->where($field, '<', $val);
                continue;
            }

            /**
             * if contains '||' then between
             * i.e: &date=2020-01-01||2020-01-31 then get all where date is between 2020-01-01 and 2020-01-31
             */
            if (strpos($value, '||') !== false) {
                $range = explode('||', $value);
                $query = $query->whereBetween($field, $range);
                continue;
            }

            /**
             * if don't match with other then =
             * i.e: &name=abc then get all where name = 'abc'
             */
            $query = $query->where($field, '=', $value);
        }

        return $query;
    }

    protected function sort(Builder $query): Builder
    {
        $by = $this->default_key_sort;
        $asc = 'asc';

        if ($this->request->has($this->sort_field_key)) {
            $model_key = $this->resource::toModelAttribute($this->request->get($this->sort_field_key));

            if ($model_key !== null) {
                $by = $model_key;
            }
        }

        if ($this->request->has($this->sort_direction_key) && $this->request->get($this->sort_direction_key) === 'desc') {
            $asc = 'desc';
        }

        $query = $query->orderBy($by, $asc);

        return $query;
    }

    protected function paginate(Builder $query): LengthAwarePaginator
    {
        $rules = [
            $this->page_size_key => 'integer|min:2|max:128'
        ];

        $this->validate($this->request, $rules);

        $perPage = 15;
        if (request()->has($this->page_size_key)) {
            $perPage = (int) $this->request->per_page;
        }

        // Todo: only queryble keys
        return $query->paginate($perPage, ['*'], $this->page_number_key)->appends($this->request->all());
    }

    protected function showAll(LengthAwarePaginator $items, int $status_code = 200)
    {
        return (new CollectionResource($this->resource, $items))
            ->response()
            ->setStatusCode($status_code);
    }

    protected function includeRelations(Builder $query, array $valid_relation): Builder
    {
        /**
         * &include=user,deal,article
         */
        if (!$this->request->has($this->with_key) || count($valid_relation) === 0) {
            return $query;
        }

        $relations = collect(explode(static::WITH_DELIMITER, $this->request->get($this->with_key)))
            ->flip()
            ->only($valid_relation)
            ->keys()
            ->all();


        foreach ($relations as $relation) {
            $query = $query->with($relation);
        }

        return $query;
    }

    protected function includeRelationsToModel(Model $model, array $valid_relation): Model
    {
        if (!$this->request->has($this->with_key) || count($valid_relation) === 0) {
            return $model;
        }

        $relations = collect(explode(static::WITH_DELIMITER, $this->request->get($this->with_key)))
            ->flip()
            ->only($valid_relation)
            ->keys()
            ->all();


        foreach ($relations as $relation) {
            $model->{$relation};
        }

        return $model;
    }

    protected function noChangeErrorResponse()
    {
        return $this->errorResponse('Must be send some change.', 422);
    }

    protected function modelRelationIndex(Model $model, string $name, array $relations)
    {
        $query = $this->includeRelations($model->{$name}()->getQuery(), $relations);

        return $this->idx($query);
    }

    abstract protected function otherValidations(Request $request, Model $model, bool $store): ?array;

    abstract protected function requestToArray(Request $request, bool $store): array;

    abstract protected function beforeToSave(Request $request, Model $model, bool $store): Model;

    abstract protected function afterToSave(Request $request, Model $model, bool $store): Model;
}
