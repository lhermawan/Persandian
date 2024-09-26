<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mart_poliklinik extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mart_poliklinik';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function poliklinik()
    {
        return $this->belongsTo('App\Models\Poliklinik', 'idpoliklinik', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dokter()
    {
        return $this->belongsTo('App\Models\Dokter', 'iddokter', 'id');
    }
}
