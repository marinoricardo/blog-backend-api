<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->paginate();
        return response()->json(['data' => $users, 'message' => "Users retrieved successfully"]);
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
            'name' => 'required',
            'email' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt('password');
            $user->save();

            DB::commit();
            return response()->json(['message' => 'User saved successfully']);
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
        $user = $this->user->find($id);
        if (empty($user)) {
            DB::rollBack();
            return response()->json(['message' => "User not found"]);
        } else {
            return response()->json(['data' => $user, 'message' => "User retrieved successfully"]);
        }
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
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $user = $this->user->find($id);
            if (empty($user)) {

                DB::rollBack();
                return response()->json(['message' => "User not found"]);
            } else {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->update();

                DB::commit();
                return response()->json(['message' => 'User updated successfully']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([$e]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $user = $this->user->find($id);
            if (empty($user)) {

                DB::rollBack();
                return response()->json(['message' => "User not found"]);
            } else {
                $user->delete();

                DB::commit();
                return response()->json(['message' => 'User removed successfully']);
            }
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([$e]);
        }
    }
}
