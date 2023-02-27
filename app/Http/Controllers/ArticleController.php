<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $article = new Article();
        $article->title = $request->title;
        $article->description = $request->description;
        $article->user_id = Auth::id();
        $article->save();
        return response()->json(['message' => 'Article saved successfully']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $article = Article::where("user_id", Auth::id())->find($id);
        if (!$article) {
            return response()->json(['error' => 'No Article Found'], 404);
        }
        $article->delete();
        return response()->json(['message' => 'Article deleted successfully']);
    }
}
