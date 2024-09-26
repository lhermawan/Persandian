<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'berita';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    protected $fillable = ['kode_news', 'judul', 'isi', 'gambar', 'url','status','created_by', 'updated_at'];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

}
