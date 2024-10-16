<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
protected $table = 'packages';

protected $fillable = [
    'makkah',
    'shifting_makkah',
    'madinah',
    'package_id',
    'org_id',
    'package_nickname',
    'package_name_arabic',
    'package_name_english',
    'package_type',
    'camp',
    'country',
    'description_arabic',
    'description_english',
];
}
