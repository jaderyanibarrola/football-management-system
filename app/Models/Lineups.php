<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lineups extends Model
{
    use HasFactory;
    protected $fillable = ['team_id','player_id'];
}
