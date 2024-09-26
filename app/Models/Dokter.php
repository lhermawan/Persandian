<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dokter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idprofesi',
        'kodedokter',
        'namalengkap',
        'pendidikan',
        'pendidikan_nonformal',
        'foto',
        'status',
        'created_by',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profesi()
    {
        return $this->belongsTo('App\Models\Profesi', 'idprofesi', 'id');
    }

    /**
     * The attributes fields will be Carbon-ized.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

}
