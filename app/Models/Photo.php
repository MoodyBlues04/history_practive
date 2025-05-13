<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

/**
 * @property int $id
 * @property ?string $name
 * @property ?string $description
 * @property string $path
 * @property string $created_at
 * @property string $updated_at
 */
class Photo extends Model
{
    use HasFactory;

    private const PUBLIC_PREFIX = 'public';
    private const STORAGE_PREFIX = 'storage';

    protected $fillable = [
        'name',
        'description',
        'path',
    ];

    public static function createFromPath(string $path): Photo
    {
        /** @var Photo */
        return self::query()->create(['path' => $path]);
    }

    public function getPublicUrl(): string
    {
        return str_replace(self::PUBLIC_PREFIX, self::STORAGE_PREFIX, asset($this->path));
    }

    public function getFileContents(): string
    {
        return file_get_contents(storage_path($this->path));
    }
}
