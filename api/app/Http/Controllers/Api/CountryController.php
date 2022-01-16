<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Country"},
     *     path="/api/country",
     *     summary="Show all countries for jobs",
     *     @OA\Response(
     *         response=200,
     *         description="ok."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error has occurred."
     *     )
     * )
     */
    public function index()
    {

        $countries = \App\Models\Country::all();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'categories' => $countries
        ]);
    }


    /**
     * @OA\Get(
     *     tags={"Country"},
     *     path="/api/showCountry/{id}",
     *     summary="Displays the details of a country",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="country identifier",
     *          explode=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ok."
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="404 Not Found."
     *     )
     *
     * )
     */
    public function show($id)
    {
        $countries = \App\Models\Country::find($id);

        if (is_object($countries)) {
            $resp = array(
                'code' => 200,
                'status' => 'success',
                'category' => $countries
            );
        } else {
            $resp = array(
                'code' => 404,
                'status' => 'error',
                'message' => '404 Not Found.',
            );
        }

        return response()->json($resp, $resp['code']);
    }

    /**
     * @OA\Post  (
     *     tags={"Country"},
     *     path="/api/storeCountry",
     *     summary="Add a new Country",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(format="string", default="Spain", description="name of the new country", property="name"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ok.",
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="404 Not Found."
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data =  $request->input('name', null);
        if (!empty($data)) {
            $country = new \App\Models\Country();
            $country->name = $data;
            $country->save();

            $resp = array(
                'code' => 200,
                'status' => 'success',
                'country' => $country
            );
        } else {
            $resp = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Do not save the country.'
            );
        }

        //Devolver resultado
        return response()->json($resp, $resp['code']);
    }

    /**
     * @OA\Put  (
     *     tags={"Country"},
     *     path="/api/updateCountry",
     *     summary="Upgrade an existing country.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(format="number", default="1", description="id of the country", property="id"),
     *                 @OA\Property(format="string", default="Spain", description="name of the country", property="name"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ok.",
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="404 Not Found."
     *     )
     * )
     */
    function update(Request $request)
    {
        $data['id'] =  $request->input('id', null);
        $data['name'] =  $request->input('name', null);

        if (!empty($data)) {
            //Validar datos
            $validate = Validator::make($data, [
                'id' => 'required',
                'name' => 'required'
            ]);

            if ($validate->fails()) {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Do not save the register.'
                );
            } else {
                \App\Models\Country::where('id', $data['id'])->update($data);

                $resp = array(
                    'code' => 200,
                    'status' => 'success'
                );
            }
        } else {
            $resp = array(
                'code' => 404,
                'status' => 'error',
                'message' => '404 Not Found.'
            );
        }

        return response()->json($resp, $resp['code']);
    }


    /**
     * @OA\Delete(
     *     tags={"Country"},
     *     path="/api/delCountry/{id}",
     *     summary="Delete a selected country",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Country identifier",
     *          explode=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ok."
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="404 Not Found."
     *     )
     *
     * )
     */
    function destroy($id)
    {
        if (!empty($id)) {
            \App\Models\Country::where('id', $id)->delete();
            $data = array(
                'code' => 200,
                'status' => 'success'
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => '404 Not found.',
            );
        }

        return response()->json($data, $data['code']);
    }
}
