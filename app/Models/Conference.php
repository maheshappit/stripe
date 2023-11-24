<?php

namespace App\Models;

use App\Models\ConferencesData;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    use HasFactory;

    protected $table='conferences';

    protected $fillable = [
        'name',
        'email',
        'article',
        'country',
        'conference',
        'user_id',
        'user_created_at',
        'user_updated_at',
    ];

    public function postedby()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
