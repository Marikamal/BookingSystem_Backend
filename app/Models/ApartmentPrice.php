<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ValidForRange;

class ApartmentPrice extends Model
{
    use HasFactory, ValidForRange;

    protected $fillable = ['apartment_id', 'start_date', 'end_date','price'];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

 
}
