<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.users.search');
    }


    public function searchCandidate(Request $request)
    {
       $data = User::whereEmail($request->email)->first();
       return view('admin.users.search',['data'=>$data]);
    }

    public function store(Request $req)
    {
        $array = [ 'title' => 'required|min:2|max:190'];

        $validator = Validator::make($req->all(),$array);

        if ($validator->fails())
        {
            return response()->json([ 'success' => false, 'errors' => $validator->getMessageBag()->toArray() ], 400);
        }

        $cat = new Courses();
        $cat->title = $req->title;
        $cat->slug = str_slug($req->title);
        $cat->batch_month = $req->batch_month;
        $cat->status = $req->status;

        if($cat->save()){
            return response()->json(['msg' => 'success', 'res' => 'Course Created Successfully']);
        }
        else{
            return response()->json(['msg' => 'error', 'res' => 'Error While Creating Course']);
        }



    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $post = Courses::where('id',$id)->first();
        if(isset($post)){
            return response()->json(['msg' => 'success', 'post' => $post]);
        }
    }

    public function update(Request $req, $id)
    {
        $id = isset($id) ? $id : $req->id;

        if(Courses::where('title',$req->type)->where('id','!=',$id)->exists()){
            return response()->json(['msg' => 'error', 'res' => "Course with following name {$req->title} already exists"]);
        }

        $cat = new Courses();
        $attributes['title'] = $req->title;
        $attributes['batch_month'] = $req->batch_month;
        $attributes['status'] = $req->status;

        if(Courses::where('id',$id)->update($attributes)){
            return response()->json(['msg' => 'success', 'res' => 'Course Updated Successfully']);
        }
        else{
            return response()->json(['msg' => 'error', 'res' => 'Error While Updating Course']);
        }
    }

    public function destroy($id)
    {
        if(Courses::where('id',$id)->delete()){
            return response()->json(['msg' => 'success', 'res' => "Course Deleted Successfully"]);
        }
        else{
            return response()->json(['msg' => 'error', 'res' => "Error While Deleting Course"]);
        }
    }




}
