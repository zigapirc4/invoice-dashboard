<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'invoice_number',
        'date',
        'due_date',
        'amount',
        'status',
        'description',
        'sent_at',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
