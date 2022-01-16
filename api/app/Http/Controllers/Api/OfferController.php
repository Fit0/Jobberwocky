<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
* @OA\Info(title="API Jobberwocky", version="1.0")
*
* @OA\Server(url="http://localhost:251")
*/
class OfferController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/offers",
    *     summary="Show all offers of jobs",
    *     @OA\Response(
    *         response=200,
    *         description="Show all offers of jobs."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="An error has occurred."
    *     )
    * )
    */
    public function index()
    {
        return \App\Models\Offer::all();
    }
}
