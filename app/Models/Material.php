<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Material extends Model
{
    use HasFactory;
    protected $table = 'material';
    protected $fillable = [
        'material_number',
        'material_name',
        'unit'
    ];

    /**
     * Get all of the pfeps for the Material
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    /**
     * Get the primary pfep associated with the Material
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function primaryPfep(): HasOne
    {
        return $this->hasOne(Pfep::class)->where('is_primary', 1);
    }

    public function pfeps(): HasMany
    {
        // ✅ FIX: Order by primary first, then by model name
        return $this->hasMany(Pfep::class)->orderBy('is_primary', 'desc')->orderBy('model');
    }
}
