<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerandaSetting extends Model
{
    use HasFactory;

    protected $table = 'beranda_settings';
    protected $fillable = ['key', 'value'];
}