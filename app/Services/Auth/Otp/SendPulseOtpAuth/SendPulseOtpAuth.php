<?php

namespace App\Services\Auth\Otp\SendPulseOtpAuth;

use App\Exceptions\Exception;
use App\Services\Auth\Otp\OtpAuth;
use GuzzleHttp\Client;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class StubOtpAuth
 *
 * This class is just a Stub for phone otp
 *
 * @package App\Helpers
 */
class SendPulseOtpAuth implements OtpAuth
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $token;

    /**
     * @var Client
     */
    private $configData;

    /**
     * SendPulseOtpAuth constructor.
     */
    public function __construct()
    {
        $this->configData = config('otp.gate_data.sendpulse');
        $this->client = new Client([
            'base_uri' => $this->configData['base_api_url']
        ]);
    }

    /**
     * @param string $phoneNumber
     *
     * @throws UnprocessableEntityHttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function generateCode(string $phoneNumber): void
    {
        $this->token = $this->getToken();
        $code        = random_int(100000, 999999);
        $data        = [
            'phones'        => json_encode([$phoneNumber]),
            'body'          => 'NAU verification code: ' . $code,
            'transliterate' => "0"
        ];
        try {
            $result = $this->request(HttpRequest::METHOD_POST, '/sms/send', $data);
        } catch (Exception $exception) {
            logger('Can\'t send otp code.' . $exception->getMessage());
            throw new UnprocessableEntityHttpException('Can\'t send otp code.');
        }
        if (!isset($result->result) || $result->result === false) {
            logger('Can\'t send otp code.' . json_encode($result));
            throw new UnprocessableEntityHttpException('Can\'t send otp code.');
        }

        Cache::put($phoneNumber, Hash::make($code), 15);
    }

    /**
     * @param string $phoneNumber
     * @param string $codeToCheck
     *
     * @return bool
     */
    public function validateCode(string $phoneNumber, string $codeToCheck): bool
    {
        return $this->checkOtpCode($phoneNumber, $codeToCheck);
    }

    /**
     * @return mixed
     * @throws UnprocessableEntityHttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function getToken()
    {
        $authPath = $this->configData['auth_path'];
        $authData = $this->configData['auth_data'];

        if (empty($authData['client_id']) || empty($authData['client_secret'])) {
            logger('OTP provider config is not set.');
            throw new UnprocessableEntityHttpException('Can\'t send otp code.');
        }

        try {
            $authResponse = $this->client->request(HttpRequest::METHOD_POST, $authPath, ['form_params' => $authData]);
        } catch (Exception $exception) {
            logger('Can\'t send otp code.' . $exception->getMessage());
            throw new UnprocessableEntityHttpException('Can\'t send otp code.');
        }

        $data = json_decode($authResponse->getBody());

        return $data->access_token;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $data
     *
     * @return mixed
     * @throws UnprocessableEntityHttpException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function request(string $method, string $path, array $data)
    {
        $authorizationHeader = ['headers' => ['Authorization' => "Bearer " . $this->token]];
        try {
            $result = $this->client
                ->request($method,
                    $path, array_merge($authorizationHeader,
                        ['form_params' => $data]))
                ->getBody();
        } catch (Exception $exception) {
            logger('Can\'t send otp code.' . $exception->getMessage());
            throw new UnprocessableEntityHttpException('Can\'t send otp code.');
        }

        return json_decode($result);
    }

    /**
     * @param string $phoneNumber
     * @param string $codeToCheck
     *
     * @return bool
     */
    private function checkOtpCode(string $phoneNumber, string $codeToCheck): bool
    {
        return Cache::has($phoneNumber)
            ? Hash::check($codeToCheck, Cache::get($phoneNumber))
            : false;
    }
}
