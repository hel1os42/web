<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferLink\WriteRequest;
use App\Models\OfferLink;
use App\Models\Place;
use App\Repositories\OfferLinkRepository;
use App\Repositories\PlaceRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OfferLinkController extends Controller
{
    /**
     * @var OfferLinkRepository
     */
    private $offerLinkRepository;

    /**
     * @var PlaceRepository
     */
    private $placeRepository;

    public function __construct(
        OfferLinkRepository $offerLinkRepository,
        PlaceRepository $placeRepository,
        AuthManager $authManager)
    {
        $this->offerLinkRepository = $offerLinkRepository;
        $this->placeRepository     = $placeRepository;

        parent::__construct($authManager);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $placeUuid)
    {
        $place = $this->findPlace($placeUuid);

        $this->authorize('offer_links.index', $place);

        $data = $this->offerLinkRepository
            ->scopePlace($place)
            ->paginate(1000);

        return \response()->render('offer_links.index', $data->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  WriteRequest $request
     * @param   string $placeUuid
     * @return \Illuminate\Http\Response
     */
    public function store(WriteRequest $request, string $placeUuid)
    {
        $place = $this->findPlace($placeUuid);

        $this->authorize('offer_links.create', $place);

        $data             = $request->only(['tag', 'title', 'description']);
        $data['place_id'] = $place->getKey();

        $offerLink = $this->offerLinkRepository->create($data);

        return \response()->render('offer_links.show', $offerLink->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  string $placeUuid
     * @param  int $identifier
     * @return \Illuminate\Http\Response
     */
    public function show(string $placeUuid, int $identifier)
    {
        $place     = $this->findPlace($placeUuid);
        $offerLink = $this->findOfferLink($place, $identifier);

        return \response()->render('offer_links.show', $offerLink->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  WriteRequest $request
     * @param  int $identifier
     * @return \Illuminate\Http\Response
     */
    public function update(WriteRequest $request, string $placeUuid, int $identifier)
    {
        $place     = $this->findPlace($placeUuid);
        $offerLink = $this->findOfferLink($place, $identifier);

        $this->authorize('offer_links.update', $offerLink);

        $data             = $request->only(['tag', 'title', 'description']);
        $data['place_id'] = $place->getKey();

        $updatedOfferLink = $this->offerLinkRepository->update($data, $identifier);

        return \response()->render('offer_links.show', $updatedOfferLink->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $placeUuid
     * @param  int $identifier
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $placeUuid, int $identifier)
    {
        $place     = $this->findPlace($placeUuid);
        $offerLink = $this->findOfferLink($place, $identifier);

        $this->authorize('offer_links.delete', $offerLink);

        if ($offerLink->getUsages() > 0) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, trans('errors.offer_link_is_used'));
        }

        $this->offerLinkRepository->delete($identifier);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param string $placeUuid
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return Place
     */
    private function findPlace(string $placeUuid): Place
    {
        return $this->placeRepository->find($placeUuid);
    }

    /**
     * @param Place $place
     * @param int $identifier
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return mixed
     */
    private function findOfferLink(Place $place, int $identifier): OfferLink
    {
        return $this->offerLinkRepository
            ->scopePlace($place)
            ->find($identifier);
    }
}
