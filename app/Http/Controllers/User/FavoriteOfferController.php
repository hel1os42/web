<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 27.02.2018
 * Time: 20:51
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Favorite\OfferRequest;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FavoriteOfferController extends Controller
{
    private $userRepository;

    /**
     * FavoriteOfferController constructor.
     *
     * @param AuthManager    $authManager
     * @param UserRepository $userRepository
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(AuthManager $authManager, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct($authManager);
    }

    /**
     * @param string|null $userId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(string $userId = null): Response
    {
        $userId = $this->confirmUuid($userId);

        $user = $this->userRepository->find($userId);

        $this->authorize('users.favorites.list', $user);

        return \response()->render('user.favorite.offer.index', $user->favoriteOffers()->paginate());
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

        $user = $this->userRepository->find($userId);

        $this->authorize('users.favorites.create', $user);

        $user->favoriteOffers()->attach($request->get('offer_id'));

        return \response()->render('user.favorite.offer.create', $user->favoriteOffers()->paginate(),
            Response::HTTP_CREATED, route('users.show', $userId));
    }

    /**
     * @param string|null $userId
     * @param string|null $offerId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function destroy(string $userId = null, string $offerId = null): Response
    {
        if(!$offerId) {
            $offerId = $userId;
            $userId = null;
        }

        $userId = $this->confirmUuid($userId);

        $user = $this->userRepository->find($userId);

        $this->authorize('users.favorites.destroy', $user);

        $user->favoriteOffers()->detach([$offerId]);

        return \response()->render('user.favorite.offer.index', [], Response::HTTP_NO_CONTENT);

    }
}
