<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class players extends Model
{
    use HasFactory;
    protected $fillable = ['first_name','last_name','age','weight','height','photo','active'];
}
