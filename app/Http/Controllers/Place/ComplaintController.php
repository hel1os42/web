<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 05.05.2018
 * Time: 16:48
 */

namespace App\Http\Controllers\Place;


use App\Http\Controllers\Controller;
use App\Http\Requests\Place\Complaint\CreateRequest;
use App\Models\Complaint;
use App\Repositories\ComplaintRepository;
use App\Repositories\PlaceRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;

class ComplaintController extends Controller
{
    /**
     * @var PlaceRepository
     */
    private $placeRepository;

    /**
     * @var ComplaintRepository
     */
    private $complaintRepository;


    /**
     * ComplaintController constructor.
     *
     * @param AuthManager         $authManager
     * @param PlaceRepository     $placeRepository
     * @param ComplaintRepository $complaintRepository
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        AuthManager $authManager,
        PlaceRepository $placeRepository,
        ComplaintRepository $complaintRepository
    ) {
        $this->placeRepository     = $placeRepository;
        $this->complaintRepository = $complaintRepository;
        parent::__construct($authManager);
    }

    /**
     * @param string        $placeId
     * @param CreateRequest $request
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function store(string $placeId, CreateRequest $request): Response
    {
        $text  = $request->get('text');
        $place = $this->placeRepository->find($placeId);

        $this->authorize('places.complaints.create', $place);

        $complaint = $this->complaintRepository->create([
            'user_id'  => $this->user()->getId(),
            'place_id' => $placeId,
            'text'     => $text,
            'status'   => Complaint::STATUS_INBOX
        ]);

        return \response()->render('', $complaint->toArray(), Response::HTTP_CREATED, route('places.show', [$placeId]));
    }

}