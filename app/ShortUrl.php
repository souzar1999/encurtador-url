<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    protected $table = 'short_url';
    protected $primaryKey = 'id';

    protected $fillable = [
        'url', 'hash', 'creater', 'access_count'
    ];
}
