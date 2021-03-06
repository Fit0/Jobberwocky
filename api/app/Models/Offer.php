<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    public function skills()
    {
        return $this->belongsToMany('\App\Models\Skill');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }
}
