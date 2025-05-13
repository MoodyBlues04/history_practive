<?php

namespace Database\Seeders;

use App\Models\Exhibit;
use App\Models\ExhibitGroup;
use App\Models\Museum;
use App\Models\Photo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
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
        $exhibitGroupsToSeed = [
            [
                'name' => 'Голограммы у входа', // todo пока unique - по ним prev и next
                'map_coordinates' => '1.0,1.0', // todo find out cool format & logic
                'short_description' => 'ряд из голограмм у входа, крутые',
                'description' => 'ряд из голограмм у входа, крутые. А это их длинное описание',
                'next_group_name' => 'Голограммы у входа дубликат для теста', // todo
                'previous_group_name' => 'Голограммы у входа дубликат для теста',
                'photos' => [
                    'gologramms_near_entry.jpg'
                ],

                'exhibits' => [
                    [
                        'name' => 'Голограмма у входа',
                        'short_description' => 'голограмма у входа, крутая',
                        'description' => 'голограмма у входа, крутая. А это ее длинное описание',
                        'photos' => [
                            'gologramm_near_entry_1.jpg'
                        ]
                    ],
                ],
            ],
            [
                'name' => 'Голограммы у входа дубликат для теста',
                'map_coordinates' => '1.0,1.0',
                'short_description' => 'ряд из голограмм у входа, крутые',
                'description' => 'ряд из голограмм у входа, крутые. А это их длинное описание',
                'next_group_name' => 'Голограммы у входа',
                'previous_group_name' => 'Голограммы у входа',
                'photos' => [
                    'gologramms_near_entry.jpg'
                ],

                'exhibits' => [
                    [
                        'name' => 'Голограмма у входа дубль',
                        'short_description' => 'голограмма у входа, крутая',
                        'description' => 'голограмма у входа, крутая. А это ее длинное описание',
                        'photos' => [
                            'gologramm_near_entry_1.jpg'
                        ]
                    ],
                ],
            ],
        ];

        $exhibitGroups = [];

        foreach ($exhibitGroupsToSeed as $exhibitGroupData) {
            ExhibitGroup::query()->where('name', $exhibitGroupData['name'])->delete();

            $dataToCreate = collect($exhibitGroupData)->only(['name', 'map_coordinates', 'short_description', 'description'])->all();
            /** @var ExhibitGroup $exhibitGroup */
            $exhibitGroup = $museum->exhibitGroups()->create($dataToCreate);
            $this->makePhotos($exhibitGroup, $exhibitGroupData['photos'], self::EXHIBIT_GROUP_PHOTOS_DIR);

            foreach ($exhibitGroupData['exhibits'] as $exhibitData) {
                Exhibit::query()->where('name', $exhibitData['name'])->delete();
                $dataToCreate = collect($exhibitData)->only(['name', 'short_description', 'description'])->all();
                /** @var Exhibit $exhibit */
                $exhibit = $exhibitGroup->exhibits()->create($dataToCreate);
                $this->makePhotos($exhibit, $exhibitData['photos'], self::EXHIBIT_PHOTOS_DIR);
            }

            $exhibitGroups[] = [$exhibitGroup, $exhibitGroupData['next_group_name'], $exhibitGroupData['previous_group_name']];
        }

        foreach ($exhibitGroups as $exhibitGroupData) {
            [$exhibitGroup, $nextName, $prevName] = $exhibitGroupData;
            if ($nextName !== null) {
                $nextGroup = ExhibitGroup::query()->where('name', $nextName)->firstOrFail();
                $exhibitGroup->nextGroup()->associate($nextGroup)->save();
            }
            if ($prevName !== null) {
                $previousGroup = ExhibitGroup::query()->where('name', $prevName)->firstOrFail();
                $exhibitGroup->previousGroup()->associate($previousGroup)->save();
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
}
