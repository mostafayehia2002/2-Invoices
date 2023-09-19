<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    public $guarded=[];
    use SoftDeletes;

    public function section():belongsTo{

        return $this->belongsTo(Section::class);
    }

    public function details():hasMany{

        return $this->hasMany(invoices_details::class,'invoice_id');
    }
    public function attachment():hasMany{
        return $this->hasMany(invoices_attachments::class,'invoice_id');
    }


}
