<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerTackingPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_tacking_id',
        'photo_type',
        'file_path',
    ];

    public function containerTacking()
    {
        return $this->belongsTo(ContainerTacking::class);
    }
}
