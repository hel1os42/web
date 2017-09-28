<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceFilterRequest;
use App\Models\Place;
use App\Models\User;
use App\Repositories\Implementation\PlaceRepositoryEloquent;
use App\Repositories\PlaceRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @var AuthManager|PHPUnit_Framework_MockObject_MockObject
     */
    private $authManager;
    private $guard;
    private $user;

    /**
     * @before
     */
    public function before()
    {
        $this->placeRepository = $this->getMockBuilder(PlaceRepositoryEloquent::class)->disableOriginalConstructor()->getMock();
        $this->authManager     = $this->getMockBuilder(AuthManager::class)->disableOriginalConstructor()->getMock();
        $this->guard           = $this->getMockBuilder(Guard::class)->disableOriginalConstructor()->getMock();
        $this->user            = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();

        $this->authManager->method('guard')->with()->willReturn($this->guard);
        $this->guard->method('user')->with()->willReturn($this->user);
        $this->guard->method('id')->with()->willReturn($this->user->method('getId'));

        $this->controller = new PlaceController($this->placeRepository, $this->authManager);
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
        $request         = $this->createMock(PlaceFilterRequest::class);
        $paginator       = $this->getMockBuilder(LengthAwarePaginator::class)->getMock();
        $builder         = $this->getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();
        $responseFactory = $this->getMockBuilder(ResponseFactory::class)->disableOriginalConstructor()->getMock();
        $response        = new Response();

        app()->instance(\Illuminate\Contracts\Routing\ResponseFactory::class, $responseFactory);

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

        $returnValue = $this->controller->index($request);

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
        $returnValue = $this->controller->show($request, $uuid);

        self::assertSame($response, $returnValue);
    }

    /**
     * @test
     * @dataProvider showOwnerPlaceData
     *
     * @param bool $placeExists
     * @param bool $withOffers
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit_Framework_Exception
     * @throws \PHPUnit_Framework_MockObject_RuntimeException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function showOwnerPlaceTest(bool $placeExists, bool $withOffers)
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
            ->willReturn($placeExists ? $place : null);

        if ($withOffers) {
            $request
                ->expects($placeExists ? self::once() : self::never())
                ->method('get')
                ->with('with', '')
                ->willReturn('offers');

            $place
                ->expects($placeExists ? self::once() : self::never())
                ->method('append')
                ->with('offers')
                ->willReturnSelf();
        }

        $place
            ->expects($placeExists ? self::once() : self::never())
            ->method('toArray')
            ->with()
            ->willReturn($placesArray);

        $responseFactory
            ->expects($placeExists ? self::once() : self::never())
            ->method('__call')
            ->with('render', ['profile.place.show', $placesArray])
            ->willReturn($response);

        if (!$placeExists) {
            $this->expectException(NotFoundHttpException::class);
        }

        // test
        $returnValue = $this->controller->showOwnerPlace($request);

        if ($placeExists) {
            self::assertSame($response, $returnValue);
        }
    }

    // data

    public function showOwnerPlaceData()
    {
        return [
            [true, false],
            [false, false],
            [true, true],
        ];
    }

    public function showData()
    {
        return [
            [$this->generateUuid(), false],
            [$this->generateUuid(), true],
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

    public function indexData()
    {
        return [
            [[$this->generateUuid(), $this->generateUuid()], 49, 27, 10],
            [[$this->generateUuid()], 100, 450, 10],
        ];
    }
}
