<?php

namespace App\Models;

use App\Models\ConferencesData;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Validation\Rule;
use App\Rules\UniqueConferenceEmailArticle;



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
        'email_sent_status',
        'email_sent_date',
        'client_status',
    ];

    public static function rules($id = null)
    {
        return [
            'conference' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', new UniqueConferenceEmailArticle($id)],
            'article' => 'required|string|max:255',
            // Add other validation rules as needed
        ];
    }

    public function postedby()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
