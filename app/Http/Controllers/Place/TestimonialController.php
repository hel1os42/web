<?php

namespace App\Http\Controllers\Place;

use App\Http\Controllers\Controller;
use App\Http\Requests\Place\Testimonial\CreateRequest;
use App\Http\Requests\Place\Testimonial\UpdateRequest;
use App\Models\Testimonial;
use App\Repositories\PlaceRepository;
use App\Repositories\TestimonialRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Response;

class TestimonialController extends Controller
{
    /**
     * @var TestimonialRepository
     */
    private $testimonialRepository;

    /**
     * @var PlaceRepository
     */
    private $placeRepository;

    /**
     * TestimonialController constructor.
     *
     * @param AuthManager           $authManager
     * @param TestimonialRepository $testimonialRepository
     * @param PlaceRepository       $placeRepository
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        AuthManager $authManager,
        TestimonialRepository $testimonialRepository,
        PlaceRepository $placeRepository
    ) {
        $this->testimonialRepository = $testimonialRepository;
        $this->placeRepository       = $placeRepository;

        parent::__construct($authManager);
    }

    /**
     * @param string $placeId
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(string $placeId)
    {
        $place = $this->placeRepository->find($placeId);

        $this->authorize('places.testimonials.list', $place);

        $testimonials = $this->testimonialRepository->getByPlace($place);

        return \response()->render('place.testimonial.index', $testimonials->paginate());
    }

    /**
     * @param CreateRequest $createRequest
     * @param string        $placeId
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function store(CreateRequest $createRequest, string $placeId)
    {
        $place = $this->placeRepository->find($placeId);

        $this->authorize('places.testimonials.create', $place);

        $data = $createRequest->only(['text', 'stars']);
        $data = [
            'text'     => $data['text'],
            'user_id'  => $this->user()->getId(),
            'place_id' => $place->getId(),
            'stars'    => (int)$data['stars'],
            'status'   => is_null($data['text'])
                ? Testimonial::STATUS_APPROVED
                : Testimonial::STATUS_INBOX
        ];

        $testimonial = $this->testimonialRepository->createOrUpdateIfExist($data, $place, $this->user());

        return \response()->render('place.testimonial.show', $testimonial, Response::HTTP_CREATED,
            route('places.testimonials.index', $place->getId()));
    }

    /**
     * @param UpdateRequest $createRequest
     * @param string        $placeId
     * @param string        $testimonialId
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function update(UpdateRequest $createRequest, string $placeId, string $testimonialId)
    {
        $place       = $this->placeRepository->find($placeId);
        $testimonial = $this->testimonialRepository->find($testimonialId);

        $this->authorize('places.testimonials.update', [$place, $testimonial]);

        $data = [
            'text'   => $createRequest->get('text', $testimonial->getText()),
            'stars'  => $createRequest->get('stars', $testimonial->getStars()),
            'status' => $createRequest->get('status'),
        ];

        $testimonial = $this->testimonialRepository->update($data, $testimonial->getId());

        return \response()->render('place.testimonial.show', $testimonial);
    }
}
