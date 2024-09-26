<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poliklinik extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'poliklinik';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kodepoli',
        'icon',
        'namapoliklinik',
        'keterangan',
        'interval_antrian',
        'created_by',
        'status',
    ];

    /**
     * The attributes fields will be Carbon-ized.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'deleted_at',
    ];

}
