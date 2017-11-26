<?php

namespace App\Http\Controllers;

use App\Repositories\RoleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleController extends Controller
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function index(): Response
    {
        $this->authorize('roles.list');

        $roles = $this->roleRepository->all();

        if ($roles === null) {
            throw new NotFoundHttpException();
        }

        return \response()->render('role.list', ['roles' => $roles->toArray()]);
    }

    /**
     * Category show
     *
     * @param string $uuid
     *
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \LogicException
     */
    public function show(string $uuid)
    {
        $role = $this->roleRepository->find($uuid);

        $this->authorize('roles.show', $role);

        if ($role === null) {
            throw new NotFoundHttpException();
        }

        return response()->render('role.show', $role->toArray());
    }
}
