<?php

namespace App\Models;

use App\Models\Traits\HasPhotos;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $short_description
 * @property string $description
 * @property int $exhibit_group_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ExhibitGroup $exhibitGroup
 * @property Collection $photos
 */
class Exhibit extends Model
{
    use HasFactory, HasPhotos;

    protected $fillable = [
        'name',
        'short_description',
        'description',
    ];

    public function exhibitGroup(): BelongsTo
    {
        return $this->belongsTo(ExhibitGroup::class);
    }

    public function photos(): BelongsToMany
    {
        return $this->belongsToMany(Photo::class, 'exhibit_photo_pivot');
    }
}
