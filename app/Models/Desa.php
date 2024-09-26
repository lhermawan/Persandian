<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'desa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kecamatan',
        'desa',
        'website',
        'tte',
        'jenis',
        'sosialisasi',
        'status',
        'created_by',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
//    public function profesi()
//    {
//        return $this->belongsTo('App\Models\Profesi', 'idprofesi', 'id');
//    }

    /**
     * The attributes fields will be Carbon-ized.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

}
