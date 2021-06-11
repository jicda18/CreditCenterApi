<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Log extends Model
{
    protected $table = 'tbLogs';
    protected $primaryKey = 'log_id';
    protected $fillable = [
        'model',
        'reference_id',
        'log_type_id',
        'user_id',
        'ip',
        'route',
        'at',
        'old',
        'new',
    ];
    protected $hidden = ['log_id'];

    protected $casts = [
        'log_id' => 'integer',
        'log_type_id' => 'integer',
        'reference_id' => 'integer',
        'user_id' => 'integer',
        'at' => 'datetime',
        'old' => AsArrayObject::class,
        'new' => AsArrayObject::class,
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Get the type that owns the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(LogType::class, 'log_type_id', 'log_type_id');
    }

    /**
     * Get the user that owns the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get all of the reference for the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reference(): HasMany
    {
        $fk = (new $this->model)->getKey();
        return $this->hasMany($this->model, $fk, 'reference_id');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function (Log $model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}
