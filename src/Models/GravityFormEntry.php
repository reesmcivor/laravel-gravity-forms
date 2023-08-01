<?php

namespace ReesMcIvor\GravityForms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ReesMcIvor\Forms\Database\Factories\ChoiceFactory;
use ReesMcIvor\GravityForms\Database\Factories\GravityFormFactory;

class GravityFormEntry extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return GravityFormFactory::new();
    }

    public function entries()
    {
        return $this->hasMany(GravityFormEntry::class);
    }
}
