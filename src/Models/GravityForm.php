<?php

namespace ReesMcIvor\GravityForms\Models;

use Illuminate\Database\Eloquent\Model;
use ReesMcIvor\Forms\Models\AnswerTypes\VarcharAnswer;

class GravityForm extends Model
{

    public function entries()
    {
        return $this->hasMany(GravityFormEntry::class);
    }
}
