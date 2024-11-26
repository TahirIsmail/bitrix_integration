<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Courses;
use Carbon\Carbon;

class CoursesController extends Controller
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
    {   //abort_if(!auth()->user()->isCouponModerator(),403);
        if(request()->ajax()){
            $tbl = Courses::orderby('id','ASC')->get();
            return datatables()->of($tbl)
            ->addColumn('id', function($data){
                return $data->id;
           })
            ->addColumn('title', function($data){
                 return $data->title;
            })
            ->addColumn('price', function($data){
                return  $data->price.' PKR';
            })
            ->addColumn('duration', function($data){
                return (($data->duration)? $data->duration.' hours':'-');
            })
            ->addColumn('status', function($data){
                return ucfirst($data->status);
            })
            ->addColumn('action', function($data){
                $select = '<button type="button" data-id="'.$data->id.'" class="btn btn-primary btn-sm edit mr-1" title="Edit details"><i class="far fa-edit"></i></button>';
                // $select .= '<button type="button" data-id="'.$data->id.'" class="btn btn-danger btn-sm deleted" title="Delete"><i class="fas fa-trash"></i></button>';
                return $select;
            })
            ->rawColumns(['id','title','duration','status','action'])
            ->make(true);
        }
        return view('admin.courses.index');
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
        $cat->slug = Str::slug($req->title,'-');
        $cat->price = $req->price;
        $cat->type = $req->type;
        $cat->duration = $req->duration;
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
        $attributes['price'] = $req->price;
        $attributes['duration'] = $req->duration;
        $attributes['type'] = $req->type;
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
