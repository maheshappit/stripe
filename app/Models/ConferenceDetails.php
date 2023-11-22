<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceDetails extends Model
{
    use HasFactory;

    protected $table='conference_details';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'country',
        'topic_id',
        'conference_id',
        'user_id',
        'user_created_at',
        'user_updated_at'
        
        
    ];
}
