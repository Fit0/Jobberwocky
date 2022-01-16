<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
     *     tags={"Offer"},
     *     path="/api/offers",
     *     summary="Show all offers of jobs",
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

        $offers =  \App\Models\Offer::all();

        $offerList = [];

        $offerList = $this->getOffersExternal();

        if (is_object($offers)) {
            foreach ($offers as $offer) {
                $offerList[] = array(
                    'id' => $offer->id,
                    'name' => $offer->name,
                    'description' => $offer->description,
                    'skill' => $offer->skills,
                    'country' => $offer->country->name,
                    'remote' => $offer->remote,
                    'salary' => $offer->salary,
                    'public' => $offer->created_at
                );
            }
            $data = [
                'code' => 200,
                'status' => 'success',
                'offer' => $offerList
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => '404 Not Found.'
            ];
        }

        return response()->json($data, $data['code']);
    }

    /**
     * @OA\Get(
     *     tags={"Offer"},
     *     path="/api/showOffer/{id}",
     *     summary="Displays the details of the offer",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Offer identifier",
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
    public function showOffer($id)
    {
        $offer = \App\Models\Offer::find($id);

        if (is_object($offer)) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'offer' => array(
                    'id' => $offer->id,
                    'name' => $offer->name,
                    'description' => $offer->description,
                    'skill' => $offer->skills,
                    'country' => $offer->country->name,
                    'remote' => $offer->remote,
                    'salary' => $offer->salary,
                    'public' => $offer->created_at
                )
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Selected offer does not exist.'
            ];
        }

        return response()->json($data, $data['code']);
    }

    /**
     * @OA\Get(
     *     tags={"Offer"},
     *     path="/api/searchOffer/{name}/{salaryMin}/{salaryMax}/{country_id}",
     *     summary="Displays the details of the offer",
     *      @OA\Parameter(
     *          name="name",
     *          in="path",
     *          required=true,
     *          description="Name of the offer",
     *          explode=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="salaryMin",
     *          in="path",
     *          required=true,
     *          description="Salary minimum",
     *          explode=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *      @OA\Parameter(
     *          name="salaryMax",
     *          in="path",
     *          required=true,
     *          description="Salary maximun",
     *          explode=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *      @OA\Parameter(
     *          name="country_id",
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
    function searchOffer(string $name, int $salaryMin, int $salaryMax, int $country_id)
    {

        $country  = \App\Models\Country::find($country_id);

        $offerList[] = $this->getOffersExternal($name, $salaryMin, $salaryMax, $country->name);

        $offers = \App\Models\Offer::where('country_id', $country_id)
            ->Where('name', 'like', '%' . $name . '%')
            ->whereBetween('salary', [$salaryMin, $salaryMax])
            ->orderBy('created_at', 'DESC')
            ->get();

        if (is_object($offers)) {
            foreach ($offers as $offer) {
                $offerList[] = array(
                    'id' => $offer->id,
                    'name' => $offer->name,
                    'description' => $offer->description,
                    'skill' => $offer->skills,
                    'country' => $offer->country->name,
                    'remote' => $offer->remote,
                    'salary' => $offer->salary,
                    'public' => $offer->created_at
                );
            }
            $data = [
                'code' => 200,
                'status' => 'success',
                'offer' => $offerList
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Has no offers for these search criteria.'
            ];
        }

        return response()->json($data, $data['code']);
    }


    /**
     * @OA\Post  (
     *     tags={"Offer"},
     *     path="/api/storeOffer",
     *     summary="Add a new offer fito",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(format="string", default="New job offer", description="name of the new job offer", property="name"),
     *                 @OA\Property(format="string", default="Description of the new job offer", description="Description of the new job offer", property="description"),
     *                 @OA\Property(format="boolean", default="1", description="remote job", property="remote"),
     *                 @OA\Property(format="number", default="42000", description="Salary of the job", property="salary"),
     *                 @OA\Property(format="number", default="1", description="country id", property="country_id"),
     *                 @OA\Property(format="string", default="5, 2", description="id skills separated by comma", property="skill"),
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
        $data['name'] =  $request->input('name', null);
        $data['description'] =  $request->input('description', null);
        $data['remote'] =  $request->input('remote', null);
        $data['salary'] =  $request->input('salary', null);
        $data['country_id'] =  $request->input('country_id', null);
        $data['skill'] =  explode(',', $request->input('skill', null));

        if (!empty($data)) {
            $offer = new \App\Models\Offer();
            $offer->name = $data['name'];
            $offer->description = $data['description'];
            $offer->remote = $data['remote'];
            $offer->salary = $data['salary'];
            $offer->country_id = $data['country_id'];
            $offer->save();

            $lastId = \App\Models\Offer::latest('id')->first();

            foreach ($data['skill'] as $skill) {
                if (!empty($skill)) {
                    $lastId->skills()->attach($skill);
                }
            }

            $resp = array(
                'code' => 200,
                'status' => 'success',
                'country' => $offer
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
     *     tags={"Offer"},
     *     path="/api/updateOffer/{id}",
     *     summary="Upgrade an existing offer.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Offer identifier",
     *          explode=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(format="string", default="New job offer", description="name of the new job offer", property="name"),
     *                 @OA\Property(format="string", default="Description of the new job offer", description="Description of the new job offer", property="description"),
     *                 @OA\Property(format="boolean", default="1", description="remote job", property="remote"),
     *                 @OA\Property(format="number", default="42000", description="Salary of the job", property="salary"),
     *                 @OA\Property(format="number", default="1", description="country id", property="country_id"),
     *                 @OA\Property(format="string", default="5, 2", description="skills", property="skills")
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
    function update($id, Request $request)
    {

        $data['id'] =  $id;
        $data['name'] =  $request->input('name', null);
        $data['description'] =  $request->input('description', null);
        $data['remote'] =  $request->input('remote', null);
        $data['salary'] =  $request->input('salary', null);
        $data['country_id'] =  $request->input('country_id', null);
        $data['skill'] =  explode(',', $request->input('skill', null));

        if (!empty($data)) {
            //Validar datos
            $validate = Validator::make($data, [
                'id' => 'required'
            ]);

            if ($validate->fails()) {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Do not save the register.'
                );
            } else {
                $dataUpdate = array(
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'remote' => $data['remote'],
                    'salary' => $data['salary'],
                    'country_id' => $data['country_id']
                );

                \App\Models\Offer::where('id', $data['id'])->update($dataUpdate);

                $offer = \App\Models\Offer::where('id', $data['id'])->first();
                $offer->skills()->detach();

                foreach ($data['skill'] as $skill) {
                    $offer->skills()->attach($skill);
                }

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
     *     tags={"Offer"},
     *     path="/api/delOffer/{id}",
     *     summary="Delete a selected offer",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Offer identifier",
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
            $offer = \App\Models\Offer::where('id', $id)->first();
            $offer->skills()->detach();
            $offer->delete();

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

    private function getUrlApi(): string
    {
        return $this->urlApiExternal;
    }

    private function getOffersExternal(string $name = null, float $salaryMin = null, float $salaryMax = null, string $country = null)
    {
        $resultArray = [];

        $arg = !is_null($name) ? 'name=' . $name : '';
        $arg .= !is_null($salaryMin) ? '&salary_min=' . $salaryMin : '';
        $arg .= !is_null($salaryMin) ? '&salary_max=' . $salaryMax : '';
        $arg .= !is_null($country) ? '&country=' . $country : '';

        $endpoint = env('API_EXTERNAL') . '/jobs?' . $arg;
        $offerList = new \GuzzleHttp\Client();
        $response = $offerList->request('GET', $endpoint);
        $data = json_decode($response->getBody(), true);

        if (!empty($data)) {
            foreach ($data as $offer) {
                foreach ($offer[3] as $skill) {
                    $skillOffer = array(
                        'id' => 0,
                        'name' => $skill,
                        "created_at" => null,
                        "updated_at" => null,
                        "pivot" => null
                    );
                }

                $resultArray[] = array(
                    'id' => 0,
                    'name' => $offer[0],
                    'description' => $offer[0],
                    'skill' => $skillOffer,
                    'country' => $offer[2],
                    'remote' => 0,
                    'salary' => $offer[1],
                    'public' => null
                );
            }
        }

        return $resultArray;
    }
}
