<?php

namespace App\Http\Controllers\Advert;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfferLink\WriteRequest;
use App\Repositories\OfferLinkRepository;
use App\Repositories\OfferRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OfferLinkController extends Controller
{
    /**
     * @var OfferLinkRepository
     */
    private $offerLinkRepository;

    /**
     * @var OfferRepository
     */
    private $offerRepository;

    public function __construct(
        OfferLinkRepository $offerLinkRepository,
        OfferRepository $offerRepository,
        AuthManager $authManager
    )
    {
        $this->offerLinkRepository = $offerLinkRepository;
        $this->offerRepository = $offerRepository;

        parent::__construct($authManager);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('offer_links.index');

        $paginator = $this->offerLinkRepository
            ->scopeUser($this->user())
            ->paginate(1000);

        return \response()->render('advert.offer_links.index', $paginator->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  WriteRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(WriteRequest $request)
    {
        $this->authorize('offer_links.create');

        $data = $request->only(['tag', 'title', 'description']);
        $data['user_id'] = auth()->user()->getKey();

        $offerLink = $this->offerLinkRepository->create($data);

        return \response()->render('advert.offer_links.create', $offerLink->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offerLink = $this->offerLinkRepository->find($id);

        $this->authorize('offer_links.view', $offerLink);

        return \response()->render('advert.offer_links.show', $offerLink->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  WriteRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(WriteRequest $request, $id)
    {
        $offerLink = $this->offerLinkRepository->find($id);

        $this->authorize('offer_links.update', $offerLink);

        $data = $request->only(['tag', 'title', 'description']);
        $data['user_id'] = auth()->user()->getKey();

        $offerLink = $this->offerLinkRepository->update($data, $id);

        return \response()->render('advert.offer_links.update', $offerLink->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offerLink = $this->offerLinkRepository->find($id);

        $this->authorize('offer_links.delete', $offerLink);

        $this->offerLinkRepository->delete($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
