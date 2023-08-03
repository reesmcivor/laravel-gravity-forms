<?php

namespace ReesMcIvor\GravityForms\Models;

use Illuminate\Database\Eloquent\Model;
use ReesMcIvor\Forms\Models\AnswerTypes\VarcharAnswer;

class GravityForm extends Model
{
    protected $casts = [
        'fields' => 'array',
    ];

    protected $fillable = [
        'name',
        'fields',
    ];

    public function entries()
    {
        return $this->hasMany(GravityFormEntry::class);
    }
}
