<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;


class Store extends Model
{
    use HasFactory;

    protected $fillable = ["title", "description"];

    public function views() {
        //class, pivot_table_name, current models key, foreign models key
        return $this->belongsToMany(User::class, "views", "store_id", "user_id");
    }

    public function reviews() {
        //class, pivot_table_name, current models key, foreign models key
        return $this->belongsToMany(User::class, "reviews", "store_id", "user_id")->withPivot("review");
    }
}
