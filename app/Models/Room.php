<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'room_code',
        'name',
        'slug',
        'room_type',
        'description',
        'monthly_price',
        'room_size',
        'availability',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    public function facilities()
    {
        return $this->belongsToMany(
            Facility::class,
            'room_facility',  
            'room_id',         
            'facility_id'      
        );
    }
}