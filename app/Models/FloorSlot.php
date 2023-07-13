<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FloorSlot extends Model
{
    use HasFactory;

    protected $fillable = ['floor_id', 'categorie_id', 'name'];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function Category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }
    public function active_parking()
    {
        $currentTime = now();
        return $this->hasOne('App\Models\Parking', 'slot_id')->where('table_name', 'floor_slots')->where('done', false)->where('in_time', '<=', $currentTime)->where(function ($query) use ($currentTime) { $query->where('done', false) ->orWhere('out_time', '>=', $currentTime); });
    }
}
