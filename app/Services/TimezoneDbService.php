<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 27.03.2018
 * Time: 16:48
 */

namespace App\Services;

use App\Exceptions\Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class TimezoneDbService
{

    const BASE_API_URL = 'http://api.timezonedb.com';

    /**
     * @param float|null $latitude
     * @param float|null $longitude
     *
     * @return \DateTimeZone
     * @throws Exception
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function getTimezoneByLocation(float $latitude = null, float $longitude = null): \DateTimeZone
    {
        if ($latitude == null || $latitude == null) {
            $this->error('Object latitude or longitude not setted.');
        }
        $apiKey = config('app.timezonedb_key');

        $xmlResult = new \SimpleXMLElement($this->sendRequest(Request::METHOD_GET,
            sprintf('?lat=%f&lng=%f&key=%s', $latitude, $longitude, $apiKey)));

        if (empty($xmlResult->zoneName)) {
            $this->error($xmlResult->message);
        }

        $result = $xmlResult->zoneName;

        return new \DateTimeZone($result);
    }

    /**
     * @param string $method
     * @param string $path
     *
     * @return string
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    private function sendRequest(string $method, string $path)
    {
        $client = new Client(['base_uri' => self::BASE_API_URL]);

        return $client->request($method, $path)->getBody()->getContents();
    }

    /**
     * @throws Exception
     */
    private function error($message = null)
    {
        throw new Exception(sprintf('Enable to get timezone, API message: %s', $message));
    }
}