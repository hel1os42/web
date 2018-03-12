<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 28.02.2018
 * Time: 12:50
 */

namespace App\Http\Controllers\User\Favorite;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Auth\AuthManager;

class FavoriteController extends Controller
{
    protected $userRepository;

    /**
     * FavoritePlaceController constructor.
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
}
