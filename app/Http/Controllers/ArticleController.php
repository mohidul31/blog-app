<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/articles",
     * summary="Get Articles of a user",
     * tags={"articles"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=401,
     *    description="error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *     )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Articles retrieved successfully"),
     *       @OA\Property(property="data", type="object", ref="#/components/schemas/Article"
     *      ),
     *    )
     * )
     *)
     */
    public function index()
    {
        $data = Article::where("user_id", Auth::id())->get();
        return response()->json([
            'message' => 'Articles retrieved successfully',
            'data'    => $data,
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/articles",
     * summary="Create Article",
     * tags={"articles"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Basic Registration Data",
     *    @OA\JsonContent(
     *       required={"title"},
     *       @OA\Property(property="title", type="string",),
     *       @OA\Property(property="description", type="string", ),
     *    ),
     * ),
     * @OA\Response(
     *     response=422,
     *     description="Validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="The given data was invalid."),
     *        @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *              property="title",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The title field is required."},
     *              )
     *           )
     *        )
     *     )
     *  ),
     * @OA\Response(
     *    response=401,
     *    description="error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *     )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Article saved successfully"),
     *      ),
     *    )
     * )
     *)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->messages(),
            ], 422);
        }

        $article = new Article();
        $article->title = $request->title;
        $article->description = $request->description;
        $article->user_id = Auth::id();
        $article->save();
        return response()->json(['message' => 'Article saved successfully']);
    }

    /**
     * @OA\Put(
     * path="/api/articles/{id}",
     * summary="Update Article",
     * tags={"articles"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *    description="Id of article",
     *    in="path",
     *    name="id",
     *    required=true,
     *    example="1",
     *    @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Basic Registration Data",
     *    @OA\JsonContent(
     *       required={"title"},
     *       @OA\Property(property="title", type="string",),
     *       @OA\Property(property="description", type="string", ),
     *    ),
     * ),
     * @OA\Response(
     *     response=422,
     *     description="Validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="The given data was invalid."),
     *        @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *              property="title",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The title field is required."},
     *              )
     *           )
     *        )
     *     )
     *  ),
     * @OA\Response(
     *    response=401,
     *    description="error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *     )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Article saved successfully"),
     *      ),
     *    )
     * )
     *)
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $article = Article::where("user_id", Auth::id())->find($id);
        if (!$article) {
            return response()->json(['error' => 'No Article Found'], 404);
        }
        $article->title = $request->title;
        $article->description = $request->description;
        $article->save();
        return response()->json(['message' => 'Article updated successfully']);
    }

    /**
     * @OA\Delete(
     * path="/api/articles/{id}",
     * summary="Delete Article",
     * tags={"articles"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *    description="Id of article",
     *    in="path",
     *    name="id",
     *    required=true,
     *    example="1",
     *    @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     *    response=401,
     *    description="error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *     )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="No Article Found")
     *     )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Article deleted successfully"),
     *      ),
     *    )
     * )
     *)
     */
    public function destroy($id)
    {

        $article = Article::where("user_id", Auth::id())->find($id);
        if (!$article) {
            return response()->json(['message' => 'No Article Found'], 404);
        }
        $article->delete();
        return response()->json(['message' => 'Article deleted successfully']);
    }
}
