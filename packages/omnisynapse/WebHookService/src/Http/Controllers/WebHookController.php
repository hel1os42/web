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
use OmniSynapse\WebHookService\Criteria\WebHook\UserCriteria;
use OmniSynapse\WebHookService\Http\Requests\WebHookRequest;
use OmniSynapse\WebHookService\Models\WebHook;
use OmniSynapse\WebHookService\Presenters\WebHookPresenter;
use OmniSynapse\WebHookService\Repositories\Contracts\WebHookRepository;

class WebHookController extends Controller
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
     * @var WebHookRepository
     */
    private $repository;

    public function __construct(AuthManager $authManager, WebHookRepository $repository)
    {
        $this->auth  = $authManager;
        $this->guard = $this->auth->guard('jwt');

        if (is_null($this->guard->user())) {
            throw new UnauthorizedException();
        }

        $repository->pushCriteria(new UserCriteria($this->guard->user()));

        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->repository
            ->setPresenter(WebHookPresenter::class)
            ->paginate();

        $responseData = $this->getIlluminatePagination($data)
            ->toArray();

        return response()->json($responseData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WebHookRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(WebHookRequest $request)
    {
        $this->authorize('create', WebHook::class);

        $data = $request->all();

        $data['user_id'] = $this->guard->user()->getAuthIdentifier();

        $webHook = $this->repository->create($data);

        $responseData = $this->repository
            ->setPresenter(WebHookPresenter::class)
            ->parserResult($webHook);

        return response()->json($responseData, JsonResponse::HTTP_CREATED);
    }

    /**
     *  Display the specified resource.
     *
     * @param int $webHookId
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(int $webHookId)
    {
        $webHook = $this->repository->find($webHookId);

        $this->authorize('view', $webHook);

        $responseData = $this->repository
            ->setPresenter(WebHookPresenter::class)
            ->parserResult($webHook);

        return response()->json($responseData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param WebHookRequest $request
     * @param int            $webHookId
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(WebHookRequest $request, int $webHookId)
    {
        $webHook = $this->repository->update($request->only(['url', 'events']), $webHookId);

        $this->authorize('update', $webHook);

        $responseData = $this->repository
            ->setPresenter(WebHookPresenter::class)
            ->parserResult($webHook);

        return response()->json($responseData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $webHookId
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $webHookId)
    {
        $webHook = $this->repository->find($webHookId);

        $this->authorize('delete', $webHook);

        $this->repository->delete($webHookId);

        return response()->json([], JsonResponse::HTTP_NO_CONTENT);
    }
}
