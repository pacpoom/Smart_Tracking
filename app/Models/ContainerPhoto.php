<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_transaction_id',
        'file_path',
    ];

    /**
     * Get the transaction that owns the photo.
     */
    public function containerTransaction()
    {
        return $this->belongsTo(ContainerTransaction::class);
    }
}
