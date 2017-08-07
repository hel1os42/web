<?php
/**
 * Created by PhpStorm.
 * User: mobix
 * Date: 07.08.2017
 * Time: 17:33
 */

namespace App\Http\Controllers;

use App\Models\ActivationCode;
use Hashids\Hashids;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class RedemptionController extends Controller
{

    /**
     * @param string $offerId
     * @return Response
     */
    public function getActivationCode(string $offerId): Response
    {
        $code = new ActivationCode();
        $code->offer_id = $offerId;
        $code->user_id = auth()->user()->id;
        $code->save();


        $hashids = new Hashids('NAU', 3, 'abcdefghijklmnopqrstuvwxyz');
        $code->code = $hashids->encode($code->id);
        $code->update();

        return dd($code);
    }

//    public function redemption(): Response
//    {
//
//    }
}