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

    protected $appends = array('age');

    protected static function newFactory()
    {
        return GravityFormFactory::new();
    }

    public function form()
    {
        return $this->belongsTo(GravityForm::class, 'gravity_form_id');
    }
    
    public function getAgeAttribute()
    {
        return $this->created_at->diffForHumans();
    }
    
}
