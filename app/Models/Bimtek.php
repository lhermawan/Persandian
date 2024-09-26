<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bimtek extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bimtek';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_desa',
        'tgl_bimtek',
        'jenis_bimtek',
        'surat',
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


    public function desa()
    {
        return $this->belongsTo('App\Models\Desa', 'id_desa', 'id');
    }

    public function kecamatan()
    {
        return $this->belongsTo('App\Models\Desa', 'id_desa', 'id');
    }

    protected $dates = [
        'created_at',
        'updated_at'
    ];

}
