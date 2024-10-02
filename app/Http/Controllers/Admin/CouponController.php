<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Coupons;
use Carbon\Carbon;

class CouponController extends Controller
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
            $tbl = Coupons::get();
            return datatables()->of($tbl)
            ->addColumn('type', function($data){
                return ucfirst($data->type);
           })
            ->addColumn('code', function($data){
                 return $data->code;
            })
            ->addColumn('title', function($data){
                return $data->name;
            })
            ->addColumn('discount', function($data){
                return $data->discount_amount;
            })
            ->addColumn('description', function($data){
                if($data->description){
                    $div = '<div><span id="content'.$data->id.'">'.substr($data->description,0,80).'</span> <br>
                    <a href="javascript:void(0)" class="showMore" id="'.$data->id.'" data-id="'.$data->id.'" data-type="less" data-content="'.$data->description.'" data-less="'.substr($data->description,0,80).'"> [See more]</a></div>';
                    return $div;
                }
                else{
                    return '';
                }
            })
            ->addColumn('expiry', function($data){
                return Carbon::parse($data->expires_at)->format('M d Y');
            })
            ->addColumn('created_by', function($data){
                return $data->user->name.'<br>'.$data->user->email;
            })
            ->addColumn('action', function($data){
                $select = '<button type="button" data-id="'.$data->id.'" class="btn btn-primary btn-sm edit mr-1" title="Edit details"><i class="far fa-edit"></i></button>';
                // $select .= '<button type="button" data-id="'.$data->id.'" class="btn btn-danger btn-sm deleted" title="Delete"><i class="fas fa-trash"></i></button>';
                return $select;
            })
            ->rawColumns(['type','code','title','expiry','description','created_by','action'])
            ->make(true);
        }
        return view('admin.coupon.index');
    }


    public function create()
    {
        //
    }

    public function store(Request $req)
    {
        $array = [ 'title' => 'required|min:2|max:190','expiry'=>'required','discount'=>'required|not_in:0' ];

        $validator = Validator::make($req->all(),$array);

        if ($validator->fails())
        {
            return response()->json([ 'success' => false, 'errors' => $validator->getMessageBag()->toArray() ], 400);
        }

        $cat = new Coupons();
        $cat->code = $cat->generateUniqueCode();
        $cat->name = $req->title;
        $cat->description = isset($req->description) ? $req->description : '';
        $cat->type = $req->type;
        $cat->discount_amount = $req->discount;
        $cat->expires_at = $req->expiry;
        $cat->created_by = auth()->id();


        if($cat->save()){
            return response()->json(['msg' => 'success', 'res' => 'Coupon Created Successfully']);
        }
        else{
            return response()->json(['msg' => 'error', 'res' => 'Error While Creating Coupon']);
        }



    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $post = Coupons::where('id',$id)->first();
        if(isset($post)){
            return response()->json(['msg' => 'success', 'post' => $post]);
        }
    }

    public function update(Request $req, $id)
    {
        $id = isset($id) ? $id : $req->id;

        if(Coupons::where('name',$req->type)->where('type','press')->where('id','!=',$id)->exists()){
            return response()->json(['msg' => 'error', 'res' => "Category with following name {$req->name} already exists"]);
        }

        // $array = [ 'type' => 'required|min:2|max:190' ];
        $attributes = array();
        if($req->description){ $attributes['description'] = 'required|max:1000'; }

        $validator = Validator::make($req->all(),$attributes);

        if ($validator->fails())
        {
            return response()->json([ 'success' => false, 'errors' => $validator->getMessageBag()->toArray() ], 400);
        }


        $cat = new Coupons();
        $attributes['code'] = $cat->generateUniqueCode();
        $attributes['name'] = $req->title;
        $attributes['description'] = isset($req->description) ? $req->description : '';
        $attributes['type'] = 'incubation';
        $attributes['discount_amount'] = $req->discount;
        $attributes['expires_at'] = $req->expiry;
        $attributes['created_by'] = auth()->id();

        if(Coupons::where('id',$id)->update($attributes)){
            return response()->json(['msg' => 'success', 'res' => 'Coupon Updated Successfully']);
        }
        else{
            return response()->json(['msg' => 'error', 'res' => 'Error While Updating Coupon']);
        }
    }

    public function destroy($id)
    {
        if(Coupons::where('id',$id)->delete()){
            return response()->json(['msg' => 'success', 'res' => "Category Deleted Successfully"]);
        }
        else{
            return response()->json(['msg' => 'error', 'res' => "Error While Deleting Category"]);
        }
    }




}
