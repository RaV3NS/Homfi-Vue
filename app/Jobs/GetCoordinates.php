<?php

namespace App\Jobs;

use App\Coordinate;
use App\OsmQueue;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GetCoordinates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $district;
    private $city;
    private $street;
    private $address;
    private $uuid;
    private $phase;
    private $streetId;
    private $uri = 'https://nominatim.openstreetmap.org/search?format=json&limit=1';//
    private const COUNTRY = 'Україна';
    public const PHASE_WITH_LETTER = 1;
    public const PHASE_WITHOUT_LETTER = 2;
    public const PHASE_CHANGE_NUMBER = 3;
    public const PHASE_WITHOUT_NUMBER = 4;
    public const PHASE_WITHOUT_STREET = 5;
    public const PHASE_WITHOUT_CITY = 6;
    private $lang;

    /**
     * Create a new job instance.
     *
     * @param $city
     * @param $street
     * @param $address
     * @param $uuid
     * @param $phase
     * @param $streetId
     * @param $district
     * @param string $lang
     */
    public function __construct($city, $street, $address, $uuid, $phase, $streetId, $lang = 'uk')
    {
//        $this->district = $district;
        $this->city = $city;
        $this->street = $street;
        $this->address = $address;
        $this->uuid = $uuid;
        $this->phase = $phase;
        $this->streetId = $streetId;
        $this->lang = $lang;
        $this->uri .= '&accept-language='.$lang;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if ($this->city && $this->street) {
                $coordinates = Coordinate::query()->where('street_id', '=', $this->streetId)
                    ->where('address', '=', $this->address)->first();
                if ($coordinates) {
                    OsmQueue::query()->create([
                        'lat' => $coordinates->lat,
                        'lng' => $coordinates->lng,
                        'uuid' => $this->uuid
                    ]);
                    return;
                }
            }
            if (!$this->city) {
                $this->callNominatim(self::COUNTRY);
                return;
            }

            if (!$this->street) {
                if (!$this->callNominatim(self::COUNTRY, $this->city)) {
                    GetCoordinates::dispatch(null, null, null, $this->uuid, self::PHASE_WITHOUT_CITY,
                        $this->streetId, $this->lang)->onQueue('coordinates');
                }
                return;
            }

            if (!$this->address) {
                if (!$this->callNominatim(self::COUNTRY, $this->city, $this->street)) {
                    GetCoordinates::dispatch($this->city, null, null, $this->uuid,
                        self::PHASE_WITHOUT_STREET,
                        $this->streetId, $this->lang)->onQueue('coordinates');
                }
                return;
            }

            if ($this->callNominatim(self::COUNTRY, $this->city, $this->street, $this->address)) {
                return;
            }

            switch ($this->phase) {
                case self::PHASE_WITH_LETTER:
                    if (preg_match('/^\W*\w$/iu', $this->address)) {
                        GetCoordinates::dispatch($this->city, $this->street, preg_replace('/\w/u', '', $this->address),
                            $this->uuid, self::PHASE_WITHOUT_LETTER, $this->streetId, $this->lang)->onQueue('coordinates');
                    } else if(preg_match('/^\d+$/iu', trim($this->address))) {
                        $number = preg_replace('/^(\d+).*$/u', '$1', $this->address);
                        GetCoordinates::dispatch($this->city, $this->street, (int)$number > 2 ? $number - 2 : $number + 2,
                            $this->uuid, self::PHASE_CHANGE_NUMBER, $this->streetId, $this->lang)->onQueue('coordinates');
                    } else {
                        GetCoordinates::dispatch($this->city, $this->street, null,
                            $this->uuid, self::PHASE_WITHOUT_NUMBER, $this->streetId, $this->lang)->onQueue('coordinates');
                    }
                    break;
                case self::PHASE_WITHOUT_LETTER:
                    $number = preg_replace('/^(\d+).*$/u', '$1', $this->address);
                    GetCoordinates::dispatch($this->city, $this->street, (int)$number > 2 ? $number - 2 : $number + 2,
                        $this->uuid, self::PHASE_CHANGE_NUMBER, $this->streetId, $this->lang)->onQueue('coordinates');
                    break;
                case self::PHASE_CHANGE_NUMBER:
                    GetCoordinates::dispatch($this->city, $this->street, null,
                        $this->uuid, self::PHASE_WITHOUT_NUMBER, $this->streetId, $this->lang)->onQueue('coordinates');
                    break;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage() . ' ' . $e->getTraceAsString());
        }
        return;
    }

    private function callNominatim($country, $city = null, $street = null, $address = null)
    {
        $client = new Client();
        //search by city-country-address
        $uri = $this->uri . '&country=' . $country
           // . ($this->district ? '&state=' . $this->filterValue($this->district)
            . ($city ? '&city=' . $this->filterValue($city)
            . ($street ? '&street=' . $this->filterValue($street) . ($address ? '+' . urlencode($address) : '') : '') : '') ;//: '')

        $response = $client->get($uri);

        $parsed = json_decode($response->getBody(), true);
        if (count($parsed) > 0) {
            OsmQueue::query()->create(['lat' => $parsed[0]['lat'], 'lng' => $parsed[0]['lon'], 'uuid' => $this->uuid]);
            if ($this->streetId) {
                Coordinate::query()->create([
                    'lat' => $parsed[0]['lat'],
                    'lng' => $parsed[0]['lon'],
                    'street_id' => $this->streetId,
                    'address' => $address
                ]);
            }
        } else {
            //search by classic query
            $uri = $this->uri . '&q=' . $country . ($city ? '+' . $this->filterValue($city) .
                    ($street ? '+' . $this->filterValue($street) . ($address ? '+' . $this->filterValue($address) : '') : '') : '');

            $client = new Client();

            $response = $client->get($uri);

            $parsed = json_decode($response->getBody(), true);

            if (count($parsed) > 0) {
                OsmQueue::query()->create(['lat' => $parsed[0]['lat'], 'lng' => $parsed[0]['lon'], 'uuid' => $this->uuid]);
                if ($this->streetId) {
                    Coordinate::query()->create([
                        'lat' => $parsed[0]['lat'],
                        'lng' => $parsed[0]['lon'],
                        'street_id' => $this->streetId,
                        'address' => $address
                    ]);
                }
            }
        }

        return count($parsed) > 0;
    }

    private function filterValue($value)
    {
        return urlencode(trim(preg_replace('/[\\\(\\/].*[\\\)\\/]/ui', '', $value)));
    }
}
