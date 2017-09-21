<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\PhotoRequest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PhotoController
 * @package App\Http\Controllers\Profile
 */
class PhotoController extends Controller
{
    /**
     * @param PhotoRequest $request
     * @return Response|\Illuminate\Routing\Redirector
     */
    public function store(PhotoRequest $request)
    {
        $path = $request->file('photo')->storeAs('profile/photos', auth()->id());

        if ($path === false) {
            \response()->error(Response::HTTP_NOT_ACCEPTABLE, 'Can\'t save picture.');
        }

        return $request->wantsJson() ?
            \response()->render('', [], Response::HTTP_CREATED, route('profile.photo.show')) :
            \redirect(route('profile.photo.show'));
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

        $file = 'profile/photos/' . $userUuid;

        $extension = (new Filesystem())->extension($file);

        if (false === Storage::has($file)) {
            return \response()->error(Response::HTTP_NOT_FOUND);
        }

        $file = Storage::get($file);

        switch ($extension) {
            case 'jpg':
                $contentType = 'image/jpeg';
                break;
            case 'png':
                $contentType = 'image/png';
                break;
            default:
                $contentType = 'image/jpeg';
                break;
        }

        return \response($file, 200)->header('Content-Type', $contentType);
    }
}
