<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'website_id'];


    public function website()
    {
        return $this->belongsTo(Website::class, 'website_code','code');
    }
}
