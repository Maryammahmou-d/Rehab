<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotels extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotel_name',
        'hotel_company',
        'hotel_city',
        'hotel_category',
        'hotel_zone',
    ];
}

