<?php

namespace App\Http\Controllers;

use App\Http\Requests\Place\CreateUpdateRequest;
use App\Http\Requests\PlaceFilterRequest;
use App\Models\Place;
use App\Models\User;
use App\Repositories\Implementation\PlaceRepositoryEloquent;
use App\Repositories\PlaceRepository;
use App\Services\Implementation\PlaceService;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Auth\Access\Gate;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Webpatser\Uuid\Uuid;

class PlaceControllerTest extends TestCase
{
    /**
     * @var PlaceController
     */
    private $controller;
    /**
     * @var PlaceRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $placeRepository;
    /**
     * @var PlaceService|PHPUnit_Framework_MockObject_MockObject
     */
    private $placeService;
    /**
     * @var AuthManager|PHPUnit_Framework_MockObject_MockObject
     */
    private $authManager;
    /**
     * @var Guard|PHPUnit_Framework_MockObject_MockObject
     */
    private $guard;
    /**
     * @var User|PHPUnit_Framework_MockObject_MockObject
     */
    private $user;
    /**
     * @var Generator
     */
    private $faker;
    /**
     * @var Gate||PHPUnit_Framework_MockObject_MockObject
     */
    private $authorizeGate;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->faker = Faker::create();
    }


    /**
     * @before
     */
    public function before()
    {
        $this->placeRepository = $this->getMockBuilder(PlaceRepositoryEloquent::class)->disableOriginalConstructor()->getMock();
        $this->authManager     = $this->getMockBuilder(AuthManager::class)->disableOriginalConstructor()->getMock();
        $this->guard           = $this->getMockBuilder(Guard::class)->disableOriginalConstructor()->getMock();
        $this->user            = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $this->authorizeGate   = $this->getMockBuilder(Gate::class)->disableOriginalConstructor()->getMock();

        $this->authManager->method('guard')->with()->willReturn($this->guard);
        $this->guard->method('user')->with()->willReturn($this->user);
        $this->guard->method('id')->with()->willReturn($this->user->method('getId'));

        app()->instance(\Illuminate\Contracts\Auth\Access\Gate::class, $this->authorizeGate);

        $this->configureTestUser();

        $this->controller = new PlaceController($this->authManager);
    }

    private function configureTestUser()
    {
        $userData = [
            'id'   => $this->faker->uuid,
            'name' => $this->faker->uuid,
        ];
        $this->user->method('getId')->willReturn($userData['id']);
        $this->user->method('getName')->willReturn($userData['name']);
    }

    // tests

    /**
     * @test
     * @dataProvider indexData
     *
     * @param array $categoryIds
     * @param float $latitude
     * @param float $longitude
     * @param int   $radius
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
     */
    public function indexTest($categoryIds, $latitude, $longitude, $radius)
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $request         = $this->createMock(PlaceFilterRequest::class);
        $paginator       = $this->getMockBuilder(LengthAwarePaginator::class)->getMock();
        $builder         = $this->getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();
        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();
        $response        = new Response();

        app()->instance(\Illuminate\Contracts\Routing\ResponseFactory::class, $responseFactory);

        $this->authorizeGate
            ->expects(self::once())
            ->method('authorize')
            ->with('places.list')
            ->willReturn(true);

        $this->placeRepository
            ->expects(self::once())
            ->method('getByCategoriesAndPosition')
            ->with($categoryIds, $latitude, $longitude, $radius)
            ->willReturn($builder);

        $request
            ->expects(self::exactly(4))
            ->method('__get')
            ->willReturnCallback(function ($name) use ($categoryIds, $latitude, $longitude, $radius) {
                switch ($name) {
                    case 'category_ids':
                        return $categoryIds;
                    case 'latitude':
                        return $latitude;
                    case 'longitude':
                        return $longitude;
                    case 'radius':
                        return $radius;
                }

                return null;
            });

        $builder
            ->expects(self::once())
            ->method('paginate')
            ->with()
            ->willReturn($paginator);

        $responseFactory
            ->expects(self::once())
            ->method('__call')
            ->with('render', ['place.index', $paginator])
            ->willReturn($response);

        // Test

        $returnValue = $this->controller->index($request, $this->placeRepository);

        self::assertSame($response, $returnValue);
    }

    /**
     * @test
     * @dataProvider showData
     *
     * @param      $uuid
     * @param bool $withOffers
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
     */
    public function showTest($uuid, bool $withOffers)
    {
        $place           = $this->getMockBuilder(Place::class)->disableOriginalConstructor()->getMock();
        $request         = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();
        $response        = new Response();

        app()->instance(\Illuminate\Contracts\Routing\ResponseFactory::class, $responseFactory);

        $placesArray = [$uuid, $withOffers];

        $this->placeRepository
            ->expects(self::once())
            ->method('find')
            ->with($uuid)
            ->willReturn($place);

        $this->authorizeGate
            ->expects(self::once())
            ->method('authorize')
            ->with('places.show', $place)
            ->willReturn(true);

        if ($withOffers) {
            $request
                ->expects(self::once())
                ->method('get')
                ->with('with', '')
                ->willReturn('offers');

            $place
                ->expects(self::once())
                ->method('append')
                ->with('offers')
                ->willReturnSelf();
        }

        $place
            ->expects(self::once())
            ->method('toArray')
            ->with()
            ->willReturn($placesArray);

        $responseFactory
            ->expects(self::once())
            ->method('__call')
            ->with('render', ['place.show', $placesArray])
            ->willReturn($response);

        // test
        $returnValue = $this->controller->show($request, $uuid, $this->placeRepository);

        self::assertSame($response, $returnValue);
    }

    /**
     * @test
     * @dataProvider showOwnerPlaceData
     *
     * @param bool $withOffers
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
     */
    public function showOwnerPlaceTest(bool $withOffers)
    {
        $place           = $this->getMockBuilder(Place::class)->disableOriginalConstructor()->getMock();
        $request         = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();
        $response        = new Response();

        app()->instance(\Illuminate\Contracts\Routing\ResponseFactory::class, $responseFactory);

        $placesArray = [$withOffers];

        $this->placeRepository
            ->expects(self::once())
            ->method('findByUser')
            ->with($this->user)
            ->willReturn($place);

        $this->authorizeGate
            ->expects(self::once())
            ->method('authorize')
            ->with('my.place.show')
            ->willReturn(true);

        if ($withOffers) {
            $request
                ->expects(self::once())
                ->method('get')
                ->with('with', '')
                ->willReturn('offers');

            $place
                ->expects(self::once())
                ->method('append')
                ->with('offers')
                ->willReturnSelf();
        }

        $place
            ->expects(self::once())
            ->method('toArray')
            ->with()
            ->willReturn($placesArray);

        $responseFactory
            ->expects(self::once())
            ->method('__call')
            ->with('render', ['advert.profile.place.show', $placesArray])
            ->willReturn($response);

        // test
        $returnValue = $this->controller->showOwnerPlace($request, $this->placeRepository);

        self::assertSame($response, $returnValue);
    }

    /**
     * @test
     * @dataProvider showPlaceOffersData
     *
     * @param string $uuid
     *
     * @throws \App\Exceptions\TokenException
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
     */
    public function showPlaceOffersTest(string $uuid)
    {
        $place           = $this->getMockBuilder(Place::class)->disableOriginalConstructor()->getMock();
        $builder         = $this->getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();
        $pagination      = $this->getMockBuilder(LengthAwarePaginator::class)->disableOriginalConstructor()->getMock();
        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();
        $response        = new Response();

        app()->instance(\Illuminate\Contracts\Routing\ResponseFactory::class, $responseFactory);

        $this->authorizeGate
            ->expects(self::once())
            ->method('authorize')
            ->with('places.offers.list')
            ->willReturn(true);

        $this->placeRepository
            ->expects(self::once())
            ->method('find')
            ->with($uuid)
            ->willReturn($place);

        $place
            ->expects(self::once())
            ->method('offers')
            ->with()
            ->willReturn($builder);

        $builder
            ->expects(self::once())
            ->method('paginate')
            ->with()
            ->willReturn($pagination);

        $responseFactory
            ->expects(self::once())
            ->method('__call')
            ->with('render', ['user.offer.index', $pagination])
            ->willReturn($response);

        $returnValue = $this->controller->showPlaceOffers($uuid, $this->placeRepository);
        self::assertSame($response, $returnValue);
    }

    /**
     * @test
     * @dataProvider createData
     *
     * @param array $data
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
     */
    public function createTest(array $data)
    {
        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();
        $response        = new Response();

        app()->instance(\Illuminate\Contracts\Routing\ResponseFactory::class, $responseFactory);

        $this->authorizeGate
            ->expects(self::once())
            ->method('authorize')
            ->with('my.place.create')
            ->willReturn(true);

        $responseFactory
            ->expects(self::once())
            ->method('__call')
            ->with('render', ['advert.profile.place.create', $data])
            ->willReturn($response);

        $returnValue = $this->controller->create($this->placeRepository);
        self::assertSame($response, $returnValue);
    }

    /**
     * @test
     * @dataProvider storeData
     *
     * @param array $data
     * @param array $placeArray
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
     */
    public function storeTest(array $data, array $placeArray)
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        $place           = $this->getMockBuilder(Place::class)->disableOriginalConstructor()->getMock();
        $request         = $this->getMockBuilder(CreateUpdateRequest::class)->disableOriginalConstructor()->getMock();
        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();
        $response        = new Response();

        app()->instance(\Illuminate\Contracts\Routing\ResponseFactory::class, $responseFactory);

        $this->authorizeGate
            ->expects(self::once())
            ->method('authorize')
            ->with('my.place.create')
            ->willReturn(true);

        $this->placeRepository
            ->expects(self::once())
            ->method('createForUserOrFail')
            ->with($data, $this->user)
            ->willReturn($place);

        $place
            ->expects(self::once())
            ->method('toArray')
            ->with()
            ->willReturn($placeArray);

        $categoriesSet = array_key_exists('category_ids', $data);

        if ($categoriesSet) {
            $categoriesRelation = $this->getMockBuilder(BelongsToMany::class)->disableOriginalConstructor()->getMock();
            $place
                ->expects(self::once())
                ->method('categories')
                ->with()
                ->willReturn($categoriesRelation);

            $request
                ->expects(self::once())
                ->method('__get')
                ->with('category_ids')
                ->willReturn($data['category_ids']);

            $categoriesRelation
                ->expects(self::once())
                ->method('attach')
                ->with($data['category_ids']);
        }

        $request
            ->expects(self::once())
            ->method('all')
            ->with()
            ->willReturn($data);

        $request
            ->expects(self::once())
            ->method('has')
            ->with('category_ids')
            ->willReturn($categoriesSet);

        $responseFactory
            ->expects(self::once())
            ->method('__call')
            ->with('render', ['profile.place.show', $placeArray, Response::HTTP_CREATED, route('profile.place.show')])
            ->willReturn($response);

        $returnValue = $this->controller->store($request, $this->placeRepository);
        self::assertSame($response, $returnValue);
    }

    /**
     * @test
     * @dataProvider updateData
     *
     * @param array $data
     * @param array $placeArray
     * @param bool  $isPut
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
     */
    public function updateTest(array $data, array $placeArray, bool $isPut)
    {
        $placeId            = $this->faker->uuid;
        $place              = $this->getMockBuilder(Place::class)->disableOriginalConstructor()->getMock();
        $request            = $this->getMockBuilder(CreateUpdateRequest::class)->disableOriginalConstructor()->getMock();
        $categoriesRelation = $this->getMockBuilder(BelongsToMany::class)->disableOriginalConstructor()->getMock();
        $responseFactory    = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();
        $response           = new Response();
        $this->placeService = $this->getMockBuilder(PlaceService::class)->disableOriginalConstructor()->getMock();

        app()->instance(\Illuminate\Contracts\Routing\ResponseFactory::class, $responseFactory);

        if ($isPut) {
            $place
                ->expects(self::once())
                ->method('getFillableWithDefaults')
                ->with()
                ->willReturn([]);
        }

        $this->authorizeGate
            ->expects(self::once())
            ->method('authorize')
            ->with('places.update', $place)
            ->willReturn(true);

        $place
            ->expects(self::once())
            ->method('__get')
            ->with('id')
            ->willReturn($placeId);

        $request
            ->expects(self::once())
            ->method('__get')
            ->with('category_ids')
            ->willReturn($data['category_ids']);

        $place
            ->expects(self::once())
            ->method('categories')
            ->with()
            ->willReturn($categoriesRelation);

        $place
            ->expects(self::once())
            ->method('toArray')
            ->with()
            ->willReturn($placeArray);

        $categoriesRelation
            ->expects(self::once())
            ->method('sync')
            ->with($data['category_ids']);

        $this->placeRepository
            ->expects(self::once())
            ->method('findByUser')
            ->with($this->user)
            ->willReturn($place);

        $request
            ->expects(self::once())
            ->method('all')
            ->with()
            ->willReturn($data);

        $request
            ->expects(self::once())
            ->method('isMethod')
            ->with('put')
            ->willReturn($isPut);

        $this->placeRepository
            ->expects(self::once())
            ->method('update')
            ->with($data, $placeId)
            ->willReturn($place);

        $responseFactory
            ->expects(self::once())
            ->method('__call')
            ->with('render', ['profile.place.show', $placeArray, Response::HTTP_CREATED, route('profile.place.show')])
            ->willReturn($response);

        $returnValue = $this->controller->update($request, $this->placeRepository, $this->placeService);
        self::assertSame($response, $returnValue);
    }

    // data

    public function updateData()
    {
        return [
            [$this->fakePlaceData(true), $this->fakeArray(), $this->faker->boolean],
            [$this->fakePlaceData(true), $this->fakeArray(), $this->faker->boolean],
            [$this->fakePlaceData(true), $this->fakeArray(), $this->faker->boolean],
            [$this->fakePlaceData(true), $this->fakeArray(), $this->faker->boolean],
        ];
    }

    /**
     * @param bool $withCategories
     *
     * @return array
     */
    private function fakePlaceData($withCategories = false): array
    {
        $data = [
            'name'        => $this->faker->name,
            'description' => $this->faker->text,
            'about'       => $this->faker->text,
            'address'     => $this->faker->address,
            'latitude'    => $this->faker->latitude,
            'longitude'   => $this->faker->longitude,
            'radius'      => $this->faker->randomNumber()
        ];

        if ($withCategories) {
            $categories = [];
            for ($i = 0; $i < $this->faker->randomDigit; ++$i) {
                $categories[] = $this->faker->uuid;
            }
            $data['category_ids'] = $categories;
        }

        return $data;
    }

    private function fakeArray()
    {
        $array = [];

        $count = $this->faker->numberBetween(1, 10);

        for ($i = 0; $i < $count; ++$i) {
            $array[$this->faker->randomAscii] = $this->faker->randomAscii;
        }

        return $array;
    }

    public function storeData()
    {
        return [
            [
                $this->fakePlaceData(),
                $this->fakeArray()
            ],
            [
                $this->fakePlaceData(true),
                $this->fakeArray()
            ],
        ];
    }

    public function createData()
    {
        return [
            [
                [
                    'name'                       => null,
                    'description'                => null,
                    'about'                      => null,
                    'address'                    => null,
                    'category'                   => null,
                    'retail_types'               => null,
                    'retail_types.*'             => null,
                    'latitude'                   => null,
                    'longitude'                  => null,
                    'radius'                     => null,
                    'specialities'               => null,
                    'specialities.*.retail_type' => null,
                    'specialities.*.specs'       => null,
                    'specialities.*.specs.*'     => null,
                    'tags'                       => null,
                    'tags.*'                     => null,
                ]
            ],
        ];
    }

    public function showPlaceOffersData()
    {
        return [
            [$this->generateUuid()],
            [$this->generateUuid()],
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateUuid(): string
    {
        return Uuid::generate(4)->__toString();
    }

    public function showOwnerPlaceData()
    {
        return [
            [false],
            [false],
            [true],
        ];
    }

    public function showData()
    {
        return [
            [$this->generateUuid(), false],
            [$this->generateUuid(), true],
        ];
    }

    public function indexData()
    {
        return [
            [[$this->generateUuid(), $this->generateUuid()], 49, 27, 10],
            [[$this->generateUuid()], 100, 450, 10],
        ];
    }

    private function fakeNullValueArray()
    {
        $array = [];

        $count = $this->faker->numberBetween(1, 10);

        for ($i = 0; $i < $count; ++$i) {
            $array[$this->faker->randomAscii] = null;
        }

        return $array;
    }
}
