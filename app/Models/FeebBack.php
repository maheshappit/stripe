<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeebBack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'article',
        'country',
        'conference',
        'client_status',
        'comment',
        'comment_created_date',
        'comment_updated_date'
       
    ];
}
