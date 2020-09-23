<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
	protected $fillable = ['name', 'price'];
	public $timestamps = true;
	
    public function ingredient() {
        return $this->belongsTo(User::class);
    }
}
