<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Skill"},
     *     path="/api/skills",
     *     summary="Show all skills for jobs",
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

        $skills = \App\Models\Skill::all();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'categories' => $skills
        ]);
    }


    /**
     * @OA\Get(
     *     tags={"Skill"},
     *     path="/api/showSkill/{id}",
     *     summary="Displays the details of the skill",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="skill identifier",
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
        $skills = \App\Models\Skill::find($id);

        if (is_object($skills)) {
            $resp = array(
                'code' => 200,
                'status' => 'success',
                'category' => $skills
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
     *     tags={"Skill"},
     *     path="/api/storeSkill",
     *     summary="Add a new Skill",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(format="string", default="Laravel", description="name of the new skill", property="name"),
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
            $skill = new \App\Models\Skill();
            $skill->name = $data;
            $skill->save();

            $resp = array(
                'code' => 200,
                'status' => 'success',
                'skill' => $skill
            );
        } else {
            $resp = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Do not save the skill.'
            );
        }

        //Devolver resultado
        return response()->json($resp, $resp['code']);
    }

    /**
     * @OA\Put  (
     *     tags={"Skill"},
     *     path="/api/updateSkill/{id}",
     *     summary="Upgrade an existing skill.",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="skill identifier",
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
     *                 @OA\Property(format="string", default="Laravel", description="name of the new skill", property="name"),
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
    function update($id,Request $request)
    {
        $data['id'] = $id;
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
                \App\Models\Skill::where('id', $data['id'])->update($data);

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
     *     tags={"Skill"},
     *     path="/api/delSkill/{id}",
     *     summary="Delete a selected skill",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="skill identifier",
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
            \App\Models\Skill::where('id', $id)->delete();
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
