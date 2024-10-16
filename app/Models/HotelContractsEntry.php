<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelContractsEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'hotel_id',
        'rooms_needed',
        'rooms_type',
        'pilgrims',
        'group_name',
        'nusuk_id',
        'start_date',
        'end_date',
        'hotel_cost',
        'room_cost',
        'meal_cost',
        'days_meals',
        'food_cost',
        'hotel_cost_1person',
        'food_cost_1person',
        'org_id'
    ];
    public function hotel()
{
    return $this->belongsTo(HotelContractsBuyer::class, 'hotel_id');
}

}
