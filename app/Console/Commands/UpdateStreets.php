<?php

namespace App\Console\Commands;

use App\City;
use App\District;
use App\Region;
use App\Street;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UpdateStreets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:streets {--no-download} {--part-streets} {--districts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update streets from meest express';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('starting downloading');
        try {
            if (!$this->option('no-download')) {
                try {
                    if (!is_dir(storage_path('tmp/meest'))) {
                        mkdir(storage_path('tmp/meest'), 0777, true);
                    }
                    $filename = storage_path('tmp/meest.zip');
                    if (is_file($filename)) {
                        unlink($filename);
                    }
                } catch (\Exception $e) {
                    Log::error('refresh streets mkdir|unlink error: ' . $e->getMessage());
                    throw $e;
                }
                try {
                    $meestArchive = file_get_contents(env('MEEST_FILE_URL'));
                    file_put_contents($filename, $meestArchive);
                } catch (\Exception $e) {
                    Log::error('refresh streets download error: ' . $e->getMessage());
                    throw $e;
                }
                try {
                    $zipArchive = new \ZipArchive();
                    $zipArchive->open($filename);
                    $zipArchive->extractTo(storage_path('tmp/meest/'));
                    $zipArchive->close();
                } catch (\Exception $e) {
                    Log::error('refresh streets extract error: ' . $e->getMessage());
                    throw $e;
                }
                try {
                    rename(storage_path('tmp/meest/Улицы.txt'), storage_path('tmp/meest/streets.csv'));
                    rename(storage_path('tmp/meest/Города.txt'), storage_path('tmp/meest/cities.csv'));
                    rename(storage_path('tmp/meest/Районы.txt'), storage_path('tmp/meest/regions.csv'));
                    rename(storage_path('tmp/meest/Области.txt'), storage_path('tmp/meest/districts.csv'));
                } catch (\Exception $e) {
                    Log::error('refresh streets rename error: ' . $e->getMessage());
                    throw $e;
                }
            }
        } catch (\Exception $e) {
            return;
        }
        $this->info('finished downloading');

        $this->info('starting districts');
        $districtIds = [];
        if (($handle = fopen(storage_path('tmp/meest/districts.csv'), "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, "\n")) !== false) {
                foreach ($data as $value) {
                    $values = explode(';', iconv('windows-1251', 'UTF-8', $value));
                    $data = ['name_uk' => $this->my_mb_ucfirst($values[1]), 'name_ru' => $this->my_mb_ucfirst($values[2])];
                    $district = District::query()->updateOrCreate(['uuid' => $values[0]], $data);
                    $districtIds[] = $district->id;
                }
            }
            fclose($handle);
        }
        District::query()->whereNotIn('id', $districtIds)->delete();
        $this->info('finished districts');
        if ($this->option('districts')) {
            exit;
        }

        $this->info('starting regions');
        $regionIds = [];
        if (($handle = fopen(storage_path('tmp/meest/regions.csv'), "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, "\n")) !== false) {
                foreach ($data as $value) {
                    $values = explode(';', iconv('windows-1251', 'UTF-8', $value));
                    $district = District::query()->where(['uuid' => $values[3]])->firstOrFail();
                    $region = Region::query()->updateOrCreate(['uuid' => $values[0]],
                        ['name_uk' => $values[1], 'name_ru' => $values[2], 'district_id' => $district->id]);
                    $regionIds[] = $region->id;
                }
            }
            fclose($handle);
        }
        Region::query()->whereNotIn('id', $regionIds)->delete();
        $this->info('finished regions');
        $i = 0;
        $this->info('starting cities');
        City::query()->delete();
        if (($handle = fopen(storage_path('tmp/meest/cities.csv'), "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, "\n")) !== false) {
                foreach ($data as $value) {
                    $values = explode(';', iconv('windows-1251', 'UTF-8', $value));
                    $region = Region::query()->where(['uuid' => $values[4]])->first();
                    if ($region) {
                        $insert = ['name_uk' => $values[1], 'name_ru' => $values[2], 'region_id' => $region->id, 'translit'=> Str::slug($values[2], '_', 'ru')];
                        $city = City::query()->withTrashed()->updateOrCreate(['uuid' => $values[0]], $insert);
                        $i++;
                        if ($i % 1000 == 0) {
                            $this->info($i . ' cities refreshed');
                        }
                        $city->restore();
                    } else {
                        $this->info('missing region for city ' . $values[1] . ' - ' . $values[2] . ' - ' . $values[3]);
                    }
                }
            }
            fclose($handle);
        }
        $this->info('finished cities. ' . $i . ' cities total');

        $this->info('starting streets');
        $i = 0;
        Street::query()->delete();
        if (($handle = fopen(storage_path('tmp/meest/streets.csv'), "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, "\n")) !== false) {
                try {
                    foreach ($data as $value) {
                        $values = explode(';', iconv('windows-1251', 'UTF-8', $value));
                        if (substr($values[3], 0, 1) !== '*') {
                            $city = City::query()->where('uuid', '=', $values['5'])->firstOrFail();
                            if ($city) {
                                $street = Street::query()->withTrashed()->updateOrCreate([
                                    'uuid' => $values[0]
                                ],
                                    [
                                        'prefix' => $values[1],
                                        'prefix_ru' => $values[2],
                                        'name_uk' => $values[3],
                                        'name_ru' => $values[4],
                                        'city_id' => $city->id,
                                        'translit'=> Str::slug(implode('_', [$values[2], $values[4]]), '_', 'ru') . '_ulica'
                                    ]);
                                $i++;
                                $street->restore();
                            }
                        }
                        if ($i % 1000 == 0) {
                            $this->info($i . ' streets refreshed');
                        }
                        if ($i === 1000 && $this->option('part-streets')) {
                            break 2;
                        }
                    }
                } catch (ModelNotFoundException $exception) {
                    $this->info(json_encode($values));
                }
            }
            fclose($handle);
        }
        $this->info('finished streets. ' . $i . ' streets total');

        return;
    }
}
