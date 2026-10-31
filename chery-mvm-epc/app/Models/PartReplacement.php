<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartReplacement extends Model
{
    protected $fillable = ['part_id', 'replacement_code'];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}