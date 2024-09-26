<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counterbooking extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'counterbooking';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tanggalpraktek',
        'idpraktek',
        'kuota',
        'sequence',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detailjadwal()
    {
        return $this->belongsTo('App\Models\Jadwalpraktek', 'idpraktek', 'id');
    }

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
