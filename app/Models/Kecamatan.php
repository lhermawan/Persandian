<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profesi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profesiDokter',
        'icon',
        'keterangan',
        'status',
    ];

    /**
     * The attributes fields will be Carbon-ized.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
    ];
}
