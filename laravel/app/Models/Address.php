<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cep',
        'country',
        'state',
        'city',
        'street',
        'street_number',
        'street_complement'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCity($query, $city)
    {
        return $query->where('city', $city);
    }
}

