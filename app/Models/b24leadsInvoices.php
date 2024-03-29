<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class b24leadsInvoices extends Model
{
    use HasFactory;
    protected $table = 'tbl_b24leads_invoices';
    protected $guarded = [];

    public function b24lead() {
        return $this->belongsTo(b24leads::class,'lead_id');
    }
}
