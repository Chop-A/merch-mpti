<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'transactions';
    protected $fillable = [
        'id', 'user_id', 'product_id' 
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function User() {
        return $this->hasOne(User::class);
    }

    public function Product() {
        return $this->hasMany(Product::class);
    }
}
