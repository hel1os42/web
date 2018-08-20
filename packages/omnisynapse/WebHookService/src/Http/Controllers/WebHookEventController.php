<?php

namespace OmniSynapse\WebHookService\Http\Controllers;

use App\Http\Exceptions\UnauthorizedException;
use App\Traits\FractalToIlluminatePagination;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use OmniSynapse\WebHookService\Repositories\Contracts\WebHookEventRepository;
use Prettus\Repository\Criteria\RequestCriteria;

class WebHookEventController extends Controller
{
    use AuthorizesRequests,
        ValidatesRequests,
        DispatchesJobs,
        FractalToIlluminatePagination;

    /**
     * @var AuthManager $auth
     */
    protected $auth;

    /**
     * @var \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * @var WebHookEventRepository
     */
    private $repository;

    public function __construct(AuthManager $authManager, WebHookEventRepository $repository)
    {
        $this->auth       = $authManager;
        $this->guard      = $this->auth->guard('jwt');
        $this->repository = $repository;

        $this->repository->pushCriteria(new RequestCriteria(request()));
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if (!$this->guard->user()->isAdmin()) {
            throw new UnauthorizedException('Permission denied');
        }

        return response()->json($this->repository->paginate());
    }
}
