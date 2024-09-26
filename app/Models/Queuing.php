<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queuing extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'queuing';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'data',
        'status',
        'idsubject_notifikasi',
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
