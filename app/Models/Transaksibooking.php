<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksibooking extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kodebooking',
        'idpoliklinik',
        'kode_keluarga',
        'tanggalbooking',
        'jampraktek_awal',
        'jampraktek_akhir',
        'iddokter',
        'note',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dokter()
    {
        return $this->belongsTo('App\Models\Dokter', 'iddokter', 'id');
    }

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
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function keluarga()
    {
        return $this->belongsTo('App\Models\Keluarga', 'kode_keluarga', 'kode_keluarga');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusTransaksi()
    {
        return $this->belongsTo('App\Models\Param', 'status', 'xn1')->whereRaw('keterangan = "statusBooking"');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function antrian()
    {
        return $this->belongsTo('App\Models\Antrian', 'kodebooking', 'kodebooking')->orderBy('jam_kedatangan','asc');
    }

    /**
     * The attributes fields will be Carbon-ized.
     *
     * @var array
     */
//    protected $dates = [
//        'tanggalbooking',
//        'created_at',
//    ];
    protected $casts = [
        'tanggalbooking' => 'datetime',
        'created_at' => 'datetime',
    ];

}
