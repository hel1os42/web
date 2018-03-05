<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 27.02.2018
 * Time: 20:51
 */

namespace App\Http\Controllers\User\Favorite;

use App\Http\Requests\User\Favorite\PlaceRequest;
use Symfony\Component\HttpFoundation\Response;

class PlaceController extends FavoriteController
{
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

        return \response()->render('user.favorite.place.index', $user->favoritePlaces()->paginate());
    }

    /**
     * @param PlaceRequest $request
     * @param string|null  $userId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function store(PlaceRequest $request, string $userId = null): Response
    {
        $userId = $this->confirmUuid($userId);

        $user = $this->userRepository->find($userId);

        $this->authorize('users.favorites.create', $user);

        $user->favoritePlaces()->attach($request->get('place_id'));

        return \response()->render('user.favorite.place.index', $user->favoritePlaces()->paginate(),
            Response::HTTP_CREATED, route('users.favorite.places.index', $userId));
    }

    /**
     * @param string|null $userId
     * @param string|null $placeId
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function destroy(string $userId = null, string $placeId = null): Response
    {
        if (!$placeId) {
            $placeId = $userId;
            $userId  = null;
        }

        $userId = $this->confirmUuid($userId);

        $user = $this->userRepository->find($userId);

        $this->authorize('users.favorites.destroy', $user);

        $user->favoritePlaces()->detach([$placeId]);

        return \response()->render(null, [], Response::HTTP_NO_CONTENT);

    }
}
