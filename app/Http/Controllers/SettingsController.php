<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SettingsController
 * @package App\Http\Controllers
 */
class SettingsController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        $this->authorize('settings.list');

        $result = setting()->all();

        return \response()->render('settings.index', ['data' => $result]);
    }

    /**
     * @return Response
     */
    public function apply(SettingsRequest $request): Response
    {
        $this->authorize('settings.update');

        $settings = $request->except('_token');

        setting($settings)->save();
        $result = setting()->all();

        return \response()->render('settings.index', ['data' => $result], Response::HTTP_ACCEPTED, route('settings.index'));
    }
}
