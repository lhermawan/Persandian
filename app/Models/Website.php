<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;
    protected $table = 'website';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'author',
        'status',
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

    public function websiteStatus()
    {
        return $this->hasOne(WebsiteStatus::class, 'url', 'url'); // Assuming 'url' is the foreign key in WebsiteStatus
    }
}
