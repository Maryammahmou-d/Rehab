<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelContractsBuyer extends Model
{
    use HasFactory;
protected $fillable = [
    'title',
    'rooms_needed',
    'rooms_provided',
    'rooms_type',
    'rooms_category',
    'hotel_zone1',
    'hotel_zone2',
    'hotel_zone3',
    'contract_file',
    'contract_title'
];
}
