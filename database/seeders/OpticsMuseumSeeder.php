<?php

namespace Database\Seeders;

use App\Models\Exhibit;
use App\Models\ExhibitGroup;
use App\Models\Museum;
use App\Models\Photo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OpticsMuseumSeeder extends Seeder
{
    private const OPTICS_MUSEUM_NAME = 'Музей оптики Университета ИТМО';
    private const OPTICS_MUSEUM_MAP_NAME = 'optics_museum_map';
    private const OPTICS_MUSEUM_MAP_FILE = 'optics_museum_scheme.jpg';
    private const EXHIBIT_GROUP_PHOTOS_DIR = 'exhibit_group'; // todo move to class
    private const EXHIBIT_PHOTOS_DIR = 'exhibit';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->cleanDir('public/' . self::EXHIBIT_GROUP_PHOTOS_DIR);
        $this->cleanDir('public/' . self::EXHIBIT_PHOTOS_DIR);
        $this->cleanDir('public/map');

        /** @var Museum $museum */
        $museum = Museum::query()->where('name', self::OPTICS_MUSEUM_NAME)->first();
        if ($museum === null) {
            $museumPhoto = $this->createOpticsMuseumPhoto();
            $museum = $this->createOpticsMuseum($museumPhoto);
        }
        $this->createExhibitGroups($museum);
    }

    private function createOpticsMuseumPhoto(): Photo
    {
        /** @var Photo $museumPhoto
         */
        $museumPhoto = Photo::query()->where('name', self::OPTICS_MUSEUM_MAP_NAME)->first();
        if ($museumPhoto !== null) {
            return $museumPhoto;
        }
        return $this->copyPhoto('default_images/'.self::OPTICS_MUSEUM_MAP_FILE, 'public/map/'.self::OPTICS_MUSEUM_MAP_FILE);
    }

    private function createOpticsMuseum(Photo $photo): Model
    {
        return Museum::query()->create([
            'name' => self::OPTICS_MUSEUM_NAME,
            'address' => 'Санкт-Петербург, Биржевая линия, д. 14',
            'description' => 'Основная экспозиция музея посвящена истории оптики, её развитию и достижениям. Оптика как наука зародилась еще в Древнем Египте и с тех пор прочно вошла в нашу жизнь. Её изучают не только в составе физики, но и в смежных дисциплинах.
В залах нашего музея вы увидите голограммы, узнаете, как их создают, где уже применяется голография и как она может изменить нашу жизнь. Познакомитесь с историей осветительных приборов: как человек от горящей палки дошел до энергосберегающей лампочки и лазера. Среди экспонатов найдете бронзовое китайское зеркало, хранящее маленькую тайну.
Часть музейных экспонатов является интерактивной. Вы сможете посмотреть первые мультики, увидеть старинный стереоскоп, поуправлять молниями в шарах. В зале зеркал вы прикоснетесь к изобретениям великого ученого Леонардо да Винчи.',
            'map_photo_id' => $photo->id,
        ]);
    }

    private function createExhibitGroups(Museum $museum): void
    {
        $encodedExhibits = Storage::get('default_images/exhibits.json');
        $exhibitGroupsToSeed = json_decode($encodedExhibits, true);

        $totalGroups = count($exhibitGroupsToSeed);
        foreach ($exhibitGroupsToSeed as $idx => $exhibitGroupData) {
            $exhibitGroupData['number'] = $idx + 1;
            $exhibitGroupData['map_coordinates'] = [$idx / $totalGroups * 100, $idx / $totalGroups * 100]; // todo

            ExhibitGroup::query()->where('name', $exhibitGroupData['name'])->delete();

            $dataToCreate = collect($exhibitGroupData)->only(['name', 'map_coordinates', 'short_description', 'description', 'number'])->all();
            /** @var ExhibitGroup $exhibitGroup */
            $exhibitGroup = $museum->exhibitGroups()->create($dataToCreate);
            $this->makePhotos($exhibitGroup, $exhibitGroupData['photos'], self::EXHIBIT_GROUP_PHOTOS_DIR);

            foreach ($exhibitGroupData['exhibits'] as $exhibitData) {
                Exhibit::query()->where('name', $exhibitData['name'])->delete();
                $dataToCreate = collect($exhibitData)->only(['name', 'short_description', 'description', 'number'])->all();
                /** @var Exhibit $exhibit */
                $exhibit = $exhibitGroup->exhibits()->create($dataToCreate);
                $this->makePhotos($exhibit, $exhibitData['photos'], self::EXHIBIT_PHOTOS_DIR);
            }
        }

        $exhibitGroups = ExhibitGroup::query()->get()->all();
        foreach ($exhibitGroups as $exhibitGroup) {
            if ($exhibitGroup->number > 1) {
                $prevGroup = ExhibitGroup::query()->where('number', $exhibitGroup->number - 1)->firstOrFail();
                $exhibitGroup->previousGroup()->associate($prevGroup)->save();
            }
            if ($exhibitGroup->number < $totalGroups) {
                $nextGroup = ExhibitGroup::query()->where('number', $exhibitGroup->number + 1)->firstOrFail();
                $exhibitGroup->nextGroup()->associate($nextGroup)->save();
            }
        }
    }

    private function makePhotos(Model $model, array &$paths, string $storageDir): void
    {
        foreach ($paths as $photoName) {
            $name = "$storageDir/$photoName";
            $photo = $this->copyPhoto("default_images/$name", "public/$name");
            $model->photos()->attach($photo->id);
        }
    }

    private function copyPhoto(string $srcPath, string $descPath): Photo
    {
        $dotIdx = strrpos($descPath, '.');
        $descPathFull = substr($descPath, 0 , $dotIdx) . '_' . time() . substr($descPath, $dotIdx);
        Storage::copy($srcPath, $descPathFull);
        return Photo::createFromPath($descPathFull);
    }

    private function cleanDir(string $dir): void
    {
        foreach (Storage::files($dir) as $file) {
            Storage::delete($file);
        }
    }
}
