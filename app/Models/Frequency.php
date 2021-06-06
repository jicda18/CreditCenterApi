<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Frequency extends ApiModel
{
    protected $table = 'tbFrequencies';
    protected $primaryKey = 'frequency_id';
    protected $fillable = [
        'function_name',
        'label',
    ];
    protected $hidden = ['frequency_id'];

    protected $casts = [
        'frequency_id' => 'integer',
    ];

    /**
     * Get all of the loans for the Frequency
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'frequency_id', 'frequency_id');
    }

    // Todo: frequency functions and return
}
