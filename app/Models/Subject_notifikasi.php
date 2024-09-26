<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject_notifikasi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'param_subject_kirim_notifikasi';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    protected $fillable = [
        'subject',
        'status',
    ];

    protected $dates = [
        'created_at',
    ];
}
