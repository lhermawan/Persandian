<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteStatus extends Model
{
    use HasFactory;
    protected $table = 'website_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'status',
        'ip_address',
        'ssl_status',
        'ssl_expiry_date',
        'response_time',
        'checked_at',
        'created_at',
        'updated_at',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class, 'url', 'url'); // Assuming 'url' is the foreign key in Website
    }
}
