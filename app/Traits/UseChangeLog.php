<?php

namespace App\Traits;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait UseChangeLog
{
    /**
     * Is DB has attribute.
     *
     * @param   string   $attribute
     * @return  bool
     */
    protected function hasAttribute(string $attribute): bool
    {
        return Schema::hasColumn($this->getTable(), $attribute);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function (Model $model) {
            $attribute = 'uuid';
            if ($model->hasAttribute($attribute)) {
                $model->{$attribute} = Str::uuid()->toString();
            }
        });

        static::created(function (Model $model) {
            $news = $model->getAttributes();

            $model->addLog(1, null, $news);
        });

        static::updated(function (Model $model) {
            $news = $model->getAttributes();
            $olds = $model->getOriginal();

            $model->addLog(3, $olds, $news);
        });

        static::deleted(function (Model $model) {
            $olds = $model->getOriginal();

            $model->addLog(2, $olds, null);
        });
    }

    /**
     * Insert new Log.
     *
     * @param   int   $type
     * @param   mixed   $olds
     * @param   mixed   $news
     * @return  void
     */
    protected function addLog(int $type, $olds, $news)
    {
        $user = Auth::user();
        $request = request();
        $route = $request->route();

        Log::create([
            'model' => get_class($this),
            'reference_id' => $this->getKey(),
            'log_type_id' => $type,
            'user_id' => null_prevent_property($user, 'user_id', 0),
            'ip' => $request->ip(),
            'route' => null_prevent_function($route, 'getName', null, ''),
            'at' => Carbon::now(),
            'old' => $olds,
            'new' => $news,
        ]);
    }

    public function changes()
    {
        return $this->hasMany(Log::class, 'reference_id', $this->getKeyName())->where('model', '=', get_class($this));
    }
}
