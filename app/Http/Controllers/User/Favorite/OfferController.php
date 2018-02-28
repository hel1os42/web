<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 27.02.2018
 * Time: 20:51
 */

namespace App\Http\Controllers\User\Favorite;

use App\Http\Requests\User\Favorite\OfferRequest;
use App\Repositories\OfferRepository;
use App\Repositories\User\FavoriteOfferRepository;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends FavoriteController
{
    private $offerRepository;

    private $favoriteOfferRepository;

    /**
     * FavoriteOfferController constructor.
     *
     * @param AuthManager    $authManager
     * @param UserRepository $userRepository
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        AuthManager $authManager,
        UserRepository $userRepository,
        FavoriteOfferRepository $favoriteOfferRepository,
        OfferRepository $offerRepository
    ) {
        $this->offerRepository         = $offerRepository;
        $this->favoriteOfferRepository = $favoriteOfferRepository;
        parent::__construct($authManager, $userRepository);
    }

    /**
     * @param string|null $userId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function index(string $userId = null): Response
    {
        $userId = $this->confirmUuid($userId);

        $user = $this->userRepository->find($userId);

        $this->authorize('users.favorites.list', $user);

        return \response()->render('user.favorite.offer.index',
            $this->favoriteOfferRepository->getByUser($user)->paginate());
    }

    /**
     * @param OfferRequest $request
     * @param string|null  $userId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function store(OfferRequest $request, string $userId = null): Response
    {
        $userId = $this->confirmUuid($userId);
        $user   = $this->userRepository->find($userId);

        $this->authorize('users.favorites.create', $user);
        $offer = $this->offerRepository->find($request->get('offer_id'));

        $this->favoriteOfferRepository->create(['user_id' => $user->getId(), 'offer_id' => $offer->getId()]);

        return \response()->render('user.favorite.offer.create', $user->favoriteOffers()->paginate(),
            Response::HTTP_CREATED, route('users.show', $userId));
    }

    /**
     * @param string|null $userId
     * @param string|null $offerId
     *
     * @return Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function destroy(string $userId = null, string $offerId = null): Response
    {
        if (!$offerId) {
            $offerId = $userId;
            $userId  = null;
        }

        $userId = $this->confirmUuid($userId);

        $user = $this->userRepository->find($userId);

        $this->authorize('users.favorites.destroy', $user);

        $favorite = $this->favoriteOfferRepository->findByUserIdAndOfferId($user->getId(), $offerId);
        $favorite->delete();

        return \response()->render('user.favorite.offer.index', [], Response::HTTP_NO_CONTENT);
    }
}
