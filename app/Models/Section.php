<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;
    public $guarded=[];

    public function products():hasMany{

        return $this->hasMany(Product::class,'section_id');
    }

    public function invoices():hasMany{

        return $this->hasMany(Invoice::class,'section_id');
    }
}
