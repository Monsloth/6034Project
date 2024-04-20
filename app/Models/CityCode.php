<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityCode extends Model
{
    protected $table = 'city_code'; // 你的表名
    use HasFactory;
    protected $fillable = ['city', 'citycode'];

}
