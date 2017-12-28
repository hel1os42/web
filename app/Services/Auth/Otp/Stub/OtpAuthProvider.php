<?php

namespace App\Services\Auth\Otp\Stub;

use App\Services\Auth\Otp\OtpAuth;
use GuzzleHttp\Client;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class OtpAuthProvider extends ServiceProvider implements OtpAuth
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
     * OtpAuthProvider constructor.
     *
     * @param Application $app
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->client  = new Client([
            'base_uri' => config('otp.sendpulse.base_api_url')
        ]);
        $this->token = $this->getToken();
    }

    /**
     * @param string $phoneNumber
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function generateCode(string $phoneNumber): void
    {
        $data = [
            'phones' => json_encode($phoneNumber),
            'body' => 'test',
            'transliterate' => 0
        ];
        $this->request(HttpRequest::METHOD_POST, '/sms/send', $data);
    }

    public function validateCode(string $phoneNumber, string $codeToCheck): string
    {
        // TODO: Implement validateCode() method.
    }

    /**
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function getToken()
    {
        $authPath      = config('otp.sendpulse.auth_path');
        $authData      = config('otp.sendpulse.auth_data');

        $authResponse = $this->client->request(HttpRequest::METHOD_POST, $authPath, ['form_params' => $authData]);

        $data = json_decode($authResponse->getBody());

        return $data->access_token;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $data
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    private function request(string $method, string $path, array $data)
    {
        $authorizationHeader = ['Authorization' => "Bearer " . $this->token];
        return $this->client->request($method, $path, array_merge($authorizationHeader, ['form_params' => $data]));
    }

}
