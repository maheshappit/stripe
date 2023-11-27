<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Conference;

class UniqueConferenceEmailArticle implements Rule
{
    protected $conferenceId;

    public function __construct($conferenceId = null)
    {
        $this->conferenceId = $conferenceId;
    }

    public function passes($attribute, $value)
    {
        return !Conference::where('conference', request()->input('conference'))
            ->where('email', request()->input('email'))
            ->where('article', $value)
            ->where('id', '!=', $this->conferenceId)
            ->exists();
    }

    public function message()
    {
        return 'The combination of conference, email, and article must be unique.';
    }
}
