<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 07.08.20
 * Time: 18:03
 */

namespace App\Console\Commands;

use App\City;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-coordinate:cities {--rank}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get cities coordinates from osm service';

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
     * @throws \Exception
     */
    public function handle()
    {
        $this->info('starting parsing');
        try {
            if($this->option('rank')) {
                if (($handle = fopen(storage_path('tmp/meest/cities.csv'), "r")) !== false) {
                    $i = 0;
                    while (($data = fgetcsv($handle, 1000, "\n")) !== false) {
                        foreach ($data as $value) {
                            $values = explode(';', iconv('windows-1251', 'UTF-8', $value));

                            if($values[3] == 'город'){
                                City::query()->where('uuid', $values[0])->update(['rank' => 1]);
                            }

                            $i++;
                            if ($i % 1000 == 0) {
                                $this->info($i . ' cities rank refreshed');
                            }
                        }
                    }
                    fclose($handle);
                }

                return;
            }
            if (!$this->option('rank')) {
                for ($i = 0; $i < 300; $i++) {
                    City::whereNull('lat')
                        ->offset($i * 100)->limit(100)
                        ->each(function ($city) use ($i) {
                            if (!empty($city)) {
                                $url = $this->getUrl($city);
                                $response = $this->fileGetContentsCurl($url);

                                if (!empty($response)) {
                                    $coordinates = $this->getLatLng($response);
                                } else {
                                    $url = $this->getUrl($city, true);
                                    $response = $this->fileGetContentsCurl($url);
                                    $coordinates = $this->getLatLng($response);
                                }

                                $city->update($coordinates);

                                $sleep = $i + 2 / ($i + 1);
                                $this->info($city->id . ' city received coordinates');

                                sleep($sleep);
                            }

                        });
                }
            }


        } catch (\Exception $e) {
            Log::error('parsing coordinates error: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function getUrl($city, $query = false)
    {
        if ($query) {
            return 'https://nominatim.openstreetmap.org/search?format=json&q=' . $city->name_uk . '&country=україна';
        }

        return 'https://nominatim.openstreetmap.org/search?format=json&city=' . $city->name_uk . '&state=' . $city->region->district->name_uk . '&country=україна';
    }

    protected function fileGetContentsCurl($url)
    {
        $data = ''; // empty post
        $USERAGENT = 'Mozilla/5.0 (Linux; Android 6.0; HTC One X10 Build/MRA58K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.98 Mobile Safari/537.36';
        $opts = array(
            'http' => array(
                'header' => "Content-type: text/html\r\nContent-Length: " . strlen($data) . "\r\nUser-Agent: $USERAGENT\r\n",
                'method' => 'POST'
            ),
        );
        // Create a stream
        $context = stream_context_create($opts);
        // Open the file - get the json response using the HTTP headers set above
        $jsonfile = file_get_contents($url, false, $context);

        // decode the json
        if (!json_decode($jsonfile, TRUE)) {
            return false;
        } else {
            //if (empty(array_filter($resp))) {return false;}else{
            return json_decode($jsonfile, true);
        }
    }

    protected function getLatLng($data)
    {
        if ($data) {
            // Extract data (e.g. latitude and longitude) from the results
            $gps['lat'] = $data[0]['lat'];
            $gps['lng'] = $data[0]['lon'];
        } else {
            $gps['lat'] = $gps['lng'] = 0;
        }


        return $gps;
    }

}
