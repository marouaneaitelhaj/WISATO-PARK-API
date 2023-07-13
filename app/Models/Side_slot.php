<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Side_slot extends Model
{
    use HasFactory;
    protected $table = 'side_slots';
    protected $fillable = [
        'side_id',
        'category_id',
    ];
    public function side()
    {
        return $this->belongsTo(Sides::class);
    }
    public function Category()
    {
        return $this->belongsTo(Category::class);
    }
    public function active_parking()
    {
        $currentTime = now();
        return $this->hasOne('App\Models\Parking', 'slot_id')->where('table_name', 'side_slots')->where('done', false)->where('in_time', '<=', $currentTime)->where(function ($query) use ($currentTime) { $query->where('done', false) ->orWhere('out_time', '>=', $currentTime); });
    }
}
