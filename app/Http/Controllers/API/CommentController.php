<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\CommentJob;
use App\Mail\Commentario;
use App\Mail\OrderShipped;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    private $comment;
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = $this->comment->paginate(20);
        return response()->json(['data' => $comments, 'message' => "Comments retrieved successfully"]);
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
            'comment' => 'required',
            'article_id' => 'required'
        ]);

        DB::beginTransaction();

        try {

            // dd($user_email);
            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->article_id = $request->article_id;
            $comment->save();

            $article_id = Article::Find($request->article_id);
            $user = User::find($article_id)->first();
            $user_email = $user->email;

            DB::commit();
            // CommentJob::dispatch($user_email, $comment->comment, $article_id)->delay(now()->addSeconds(10));
            $data = array('name'=>"Virat Gandhi");
            Mail::to([$user_email])->send(new OrderShipped($request->comment));

            return response()->json(['message' => 'Comment saved successfully']);
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
