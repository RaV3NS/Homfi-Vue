<?php

namespace App\Console\Commands;

use App\City;
use App\District;
use App\Subway;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UpdateSubwayTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:subway {--no-download}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update subways downloading OSM data';

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
        $filename = storage_path('tmp/ukraine.osm.pbf');
        try {
            $storagePath = storage_path('tmp/osm');
            if (!$this->option('no-download')) {
                try {
                    if (!is_dir($storagePath)) {
                        mkdir($storagePath, 0777, true);
                    }
                    if (is_file($filename)) {
                        unlink($filename);
                    }
                } catch (\Exception $e) {
                    Log::error('refresh osm mkdir|unlink error: ' . $e->getMessage());
                    throw $e;
                }
                try {
                    $osmFile = file_get_contents(env('OSM_FILE_URL'));
                    file_put_contents($filename, $osmFile);
                } catch (\Exception $e) {
                    Log::error('refresh osm download error: ' . $e->getMessage());
                    throw $e;
                }
                $this->info('file downloaded');
                $this->info('started parcing');
                $kyivPoly = file_get_contents('http://polygons.openstreetmap.fr/get_poly.py?id=' . env('OSM_KYIV_ID') . '&params=0');
                $dniproPoly = file_get_contents('http://polygons.openstreetmap.fr/get_poly.py?id=' . env('OSM_DNIPRO_ID') . '&params=0');
                $kharkivPoly = file_get_contents('http://polygons.openstreetmap.fr/get_poly.py?id=' . env('OSM_KHARKIV_ID') . '&params=0');
                file_put_contents($storagePath . DIRECTORY_SEPARATOR . 'kyiv.poly', $kyivPoly);
                file_put_contents($storagePath . DIRECTORY_SEPARATOR . 'dnipro.poly', $dniproPoly);
                file_put_contents($storagePath . DIRECTORY_SEPARATOR . 'kharkiv.poly', $kharkivPoly);
                try {
                    foreach (['kyiv', 'dnipro', 'kharkiv'] as $city) {
                        $cmd = '"' . env('OSMOSIS_PATH') . '" --read-pbf "' . $filename . '" --bounding-polygon file="' . $storagePath . DIRECTORY_SEPARATOR . $city . '.poly' . '" --tf accept-nodes station=subway --tf reject-nodes railway=disused,abandoned --tf reject-nodes station=disused --tf reject-relations --tf reject-ways --write-xml "' . $storagePath . DIRECTORY_SEPARATOR . $city . '-subway.osm"';
                        shell_exec($cmd);
                    }
                } catch (\Exception $e) {
                    Log::error('refresh streets rename error: ' . $e->getMessage());
                    throw $e;
                }
            }
        } catch (\Exception $e) {
            return;
        }
        foreach ([
                     'kyiv' => '5cb61671-749b-11df-b112-00215aee3ebe', //city meest uuid
                     'dnipro' => '50c5951b-749b-11df-b112-00215aee3ebe',
                     'kharkiv' => '87162365-749b-11df-b112-00215aee3ebe'
                 ] as $k => $v) {
            try {
                $stream = fopen($storagePath . DIRECTORY_SEPARATOR . $k . '-subway.osm', 'r');
                $parser = xml_parser_create();

                $cityId = City::query()->where('uuid', '=', $v)->firstOrFail()->id;
                $obj = simplexml_load_string(fread($stream,
                    filesize($storagePath . DIRECTORY_SEPARATOR . $k . '-subway.osm')));
                foreach ($obj as $value) {
                    $name = $name_ru = $name_uk = $old_name = $old_name_ru = $old_name_uk = $lat = $lon = $wiki = null;
                    $subway = false;
                    if ($value->getName() == 'node') {
                        if ($value->attributes()->lat) {
                            $lat = (string)$value->attributes()->lat;
                        }
                        if ($value->attributes()->lon) {
                            $lon = (string)$value->attributes()->lon;
                        }
                        foreach ($value->children() as $child) {
                            if ($child->attributes()->k && (string)$child->attributes()->k == 'name') {
                                $name = (string)$child->attributes()->v;
                            }
                            if ($child->attributes()->k && (string)$child->attributes()->k == 'name:ru') {
                                $name_ru = (string)$child->attributes()->v;
                            }
                            if ($child->attributes()->k && (string)$child->attributes()->k == 'name:uk') {
                                $name_uk = (string)$child->attributes()->v;
                            }
                            if ($child->attributes()->k && (string)$child->attributes()->k == 'old_name') {
                                $old_name = (string)$child->attributes()->v;
                            }
                            if ($child->attributes()->k && (string)$child->attributes()->k == 'old_name:ru') {
                                $old_name_ru = (string)$child->attributes()->v;
                            }
                            if ($child->attributes()->k && (string)$child->attributes()->k == 'old_name:uk') {
                                $old_name_uk = (string)$child->attributes()->v;
                            }
                            if ($child->attributes()->k && (string)$child->attributes()->k == 'station') {
                                $subway = (string)$child->attributes()->v == 'subway';
                            }
                        }
                        if ($subway && $name) {
                            Subway::query()->updateOrCreate(
                                ['osm_id' => (string)$value->attributes()->id],
                                [
                                    'name' => $name,
                                    'city_id' => $cityId,
                                    'name_ru' => $name_ru,
                                    'name_uk' => $name_uk,
                                    'old_name' => $old_name,
                                    'old_name_ru' => $old_name_ru,
                                    'old_name_uk' => $old_name_uk,
                                    'lat' => $lat,
                                    'lon' => $lon,
                                    'translit' => Str::slug($name, '_', 'ru') . '_metro'
                                ]);
                        }
                    }
                }
                xml_parse($parser, '', true); // завершить разбор
                xml_parser_free($parser);
                fclose($stream);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                continue;
            }
        }
        return;
    }
}
