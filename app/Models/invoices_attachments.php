<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class
invoices_attachments extends Model
{
    public $guarded=[];
    use HasFactory;
    public function invoice():belongsTo{

        return $this->belongsTo(Invoice::class);
    }
}
