<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'code', 'description'];

    public function posts()
    {
        return $this->hasMany(Posts::class, 'website_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'website_id');
    }
}
