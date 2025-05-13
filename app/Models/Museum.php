<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Collection\Collection;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $map_photo_id
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ?Photo $map
 * @property Collection $exhibitGroups
 */
class Museum extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'description',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(Photo::class, 'map_photo_id');
    }

    public function exhibitGroups(): HasMany
    {
        return $this->hasMany(ExhibitGroup::class);
    }
}
