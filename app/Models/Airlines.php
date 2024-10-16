<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airlines extends Model
{
    use HasFactory;
protected $fillable = [
    'pnr_no',
    'no_of_tickets',
    'outbound_departure_date',
    'outbound_arrival_date',
    'outbound_departure',
    'outbound_arrival',
    'return_departure',
    'return_arrival',
    'airline_provider',
    'pnr_total_ticket_price',
    'one_person_price_for_ticket',
    'org_id',
];

}
