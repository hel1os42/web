<?php

use Illuminate\Database\Seeder;
use App\Models\IdentityProvider;
use App\Repositories\IdentityProviderRepository;

class IdentityProviderSeeder extends Seeder
{

    /**
     * @var IdentityProviderRepository
     */
    protected $idpRepository;

    public function __construct(IdentityProviderRepository $idpRepository)
    {
        $this->idpRepository = $idpRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $providers = [
            IdentityProvider::PROVIDER_FACEBOOK  => 'Facebook',
            IdentityProvider::PROVIDER_TWITTER   => 'Twitter',
            IdentityProvider::PROVIDER_INSTAGRAM => 'Instagram',
            IdentityProvider::PROVIDER_VK        => 'VK',
        ];

        foreach ($providers as $alias => $name) {
            $provider = $this->idpRepository->firstOrNew([
                'alias' => $alias,
            ]);

            $provider->setName($name)
                ->save();
        }
    }
}
