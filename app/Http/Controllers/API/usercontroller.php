<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class usercontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function profile()
    {
        return auth('api')->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::orderBy('id', 'desc')->paginate(5);


        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required',
            'password'=>'sometimes'
        ]);
        $user=Auth::user();
        if($request->photo){

            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
           Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);

        }
        $request['password']=Hash::make($request['password']);
       $photo= public_path('img/profile/').$user->photo;;
       if(file_exists($photo)){
@unlink($photo);
       }


        $user->update($request->all());

        return ['message' => 'Success'];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function  search(Request  $request){
      if($search=  $request->get('q')){
         $users= User::where(function ($query) use ($search){
              $query->where('name','like',"%$search%");
          })->paginate(5);

      }else{
          $users = User::orderBy('id', 'desc')->paginate(5);
      }
return  $users;


    }
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
        $user=User::findOrFail($id);
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$id,
            'password'=>'sometimes'
        ]);
        $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $user=  User::findOrFail($id);
      $user->delete();;
      return ['message'=>'deleted'];
    }
}
