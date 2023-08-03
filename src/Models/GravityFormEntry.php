<?php

namespace ReesMcIvor\GravityForms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ReesMcIvor\GravityForms\Database\Factories\GravityFormFactory;

class GravityFormEntry extends Model
{
    use HasFactory;
    use HasTimestamps;

    protected $casts = [
        'entry' => 'array',
        'fields' => 'array',
    ];

    protected $fillable = [
        'id',
        'gravity_form_id',
        'entry',
        'fields',
        'created_at',
        'updated_at'
    ];

    protected static function newFactory()
    {
        return GravityFormFactory::new();
    }

    public function entries()
    {
        return $this->hasMany(GravityFormEntry::class);
    }
}
