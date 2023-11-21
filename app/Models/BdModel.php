<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BdModel extends Model
{
    use HasFactory;

    protected $table='bd';

    protected $fillable = [
        'create_date',
        'email_sent_date',
        'company_source',
        'contact_source',
        'database_creator_name',
        'technology',
        'client_speciality',
        'client_name',
        'street',
        'city',
        'state',
        'zip_code',
        'country',
        'website',
        'first_name',
        'last_name',
        'designation',
        'email',
        'email_response_1',
        'email_response_2',
        'rating',
        'followup',
        'linkedin_link',
        'employee_count',
        'user_id',
        'created_at',
        'updated_at',
        'user_created_at',
        'user_updated_at',
        'download_count',
    ];
}
