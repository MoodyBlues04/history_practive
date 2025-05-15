<?php

namespace App\Models;

use App\Models\Casts\CoordinatesCast;
use App\Models\Traits\HasPhotos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Collection\Collection;

/**
 * @property int $id
 * @property int $number
 * @property string $name
 * @property float[] $map_coordinates
 * @property string $short_description
 * @property string $description
 * @property int $museum_id
 * @property ?int $next_exhibit_group_id
 * @property ?int $previous_exhibit_group_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Collection $photos
 * @property Collection $exhibits
 * @property Museum $museum
 * @property ?ExhibitGroup $nextGroup
 * @property ?ExhibitGroup $previousGroup
 */
class ExhibitGroup extends Model
{
    use HasFactory, HasPhotos;

    protected $fillable = [
        'name',
        'number',
        'map_coordinates',
        'short_description',
        'description',
    ];

    protected $casts = [
        'map_coordinates' => CoordinatesCast::class,
    ];

    public function museum(): BelongsTo
    {
        return $this->belongsTo(Museum::class);
    }

    public function exhibits(): HasMany
    {
        return $this->hasMany(Exhibit::class);
    }

    public function previousGroup(): BelongsTo
    {
        return $this->belongsTo(ExhibitGroup::class, 'previous_exhibit_group_id');
    }

    public function nextGroup(): BelongsTo
    {
        return $this->belongsTo(ExhibitGroup::class, 'next_exhibit_group_id');
    }

    public function photos(): BelongsToMany
    {
        return $this->belongsToMany(Photo::class, 'exhibit_group_photo_pivot');
    }

    public function getMapTop(): float
    {
        return $this->map_coordinates[0];
    }

    public function getMapLeft(): float
    {
        return $this->map_coordinates[1];
    }
}
