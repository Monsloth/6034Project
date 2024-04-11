<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelIdea extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'destination', 'start_date', 'end_date', 'tags'];
    protected $primaryKey = 'idea_id';
}
