<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    private $articles;
    public function __construct(Article $articles)
    {
        $this->articles = $articles;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = $this->articles->paginate(10);
        return response()->json(['data' => $articles, 'message' => "Articles retrieved successfully"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'category_id' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $articles = new Article();
            $articles->title = $request->title;
            $articles->body = $request->body;
            $articles->category_id = $request->category_id;
            $articles->author_id = Auth::user()->id;
            if($request->file('image')==NULL){
                $articles->image='placeholder.png';
            }else{ 
                $filename=Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
                $articles->image=$filename;
                $request->image->move(public_path('images'),$filename);
            }
            $articles->save();

            DB::commit();
            return response()->json(['message' => 'Articles saved successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([$e]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
