<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{
    use HasFactory;

    protected $table = 'bom';

    /**
     * Get the vc_master that owns the bom.
     */
    public function vcMaster()
    {
        return $this->belongsTo(VcMaster::class, 'vc_code_id');
    }

    /**
     * Get the child material for the bom.
     */
    public function childMaterial()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
