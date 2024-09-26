<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'antrian';

    protected $dates = [
        'created_at',
    ];
}
