<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerTacking extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_type',
        'container_type',
        'transport_type',
        'container_id',
        'shipment',
        'user_id',
    ];

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(ContainerTackingPhoto::class);
    }
}
