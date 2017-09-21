<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\PictureRequest;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PictureController
 * @package App\Http\Controllers\Profile
 */
class PictureController extends Controller
{
    /**
     * @param PictureRequest $request
     * @return Response|\Illuminate\Routing\Redirector
     */
    public function store(PictureRequest $request)
    {
        $path  = storage_path('app') . '/profile/pictures/' . auth()->id() . '.jpg';
        $image = (new ImageManager)->make($request->file('picture'))->fit('192', '192')->encode('jpg', 80)->save($path);

        if ($image === false) {
            \response()->error(Response::HTTP_NOT_ACCEPTABLE, 'Can\'t save picture.');
        }

        return $request->wantsJson() ?
            \response()->render('', [], Response::HTTP_CREATED, route('profile.picture.show')) :
            \redirect(route('profile.picture.show'));
    }

    /**
     * @param string|null $userUuid
     * @return Response
     */
    public function show(string $userUuid = null): Response
    {
        if (is_null($userUuid)) {
            $userUuid = \auth()->id();
        }

        $file = 'profile/pictures/' . $userUuid . '.jpg';

        if (false === Storage::has($file)) {
            return \response()->error(Response::HTTP_NOT_FOUND);
        }

        $file = Storage::get($file);


        return \response($file, 200)->header('Content-Type', 'image/jpeg');
    }
}
