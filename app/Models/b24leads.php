<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class b24leads extends Model
{
    use HasFactory;
    protected $table = 'tbl_b24leads';
    protected $guarded = [];

    public function b24leadInvoices() {
        return $this->belongsTo(b24leadsInvoices::class,'id','lead_id');
    }
}
