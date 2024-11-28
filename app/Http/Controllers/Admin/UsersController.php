<?php

namespace App\Http\Controllers\Admin;

use Log;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Courses;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\BitrixCallsService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\DigitalIncubationRegistration;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $bitrixCall;

    public function __construct()
    {
        $this->middleware('auth');
        $this->bitrixCall = new BitrixCallsService();
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

    public function show(Request $request)
    {
        // whereHas('candidate',function($query) use ($request){
        //     return $query->where('id','=',$request->id);
        // })
        $data = DigitalIncubationRegistration::where('id',$request->id)->whereIn('status',['pending','approved'])->first();
        $courses = Courses::where('status','active')->get();
        return view('admin.users.mini_detail',['data'=>$data,'courses'=>$courses]);
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

    public function updateCourse(Request $request)
    {
        $course1 = (($request->course1 != 0)?$request->course1:'');
        $course2 = (($request->course2 != 0)?$request->course2:'');
        $course3 = (($request->course3 != 0)?$request->course3:'');

        $dir = DigitalIncubationRegistration::where('id',$request->id)->first();
        $dir->course1 = $course1;
        $dir->course2 = $course2;
        $dir->course3 = $course3;
        $dir->amount = $request->charges;

        if($dir->save()){
            if ($dir->b24_deal_id) {

                // $data1=[
                //     'ID' => $dealID,
                //     'FIELDS[UF_CRM_6346CD7E3D06C]' => $inoviceLink, // Payment Link
                //     'FIELDS[UF_CRM_1675176530]' => '2st Installment', // Payment Link
                // ];
                //   $queryData1   = http_build_query($data1);
                //   $ret =  $this->bitrixCall->sendCurlRequest($queryData1,"update","crm.deal");
                $data = [
                    'ID' => $dir->b24_deal_id
                ];
                $res = $this->bitrixCall->sendCurlRequest(http_build_query($data),"get","crm.deal.productrows");

            }else{

            }
            $b24_id = (($dir->b24_deal_id)?$dir->b24_deal_id:$dir->b24_lead_id);
            $b24_method = (($dir->b24_deal_id)?'deal':'lead');

            $bitrixObj=[
                    'ID' => $b24_id,
                    'FIELDS[OPPORTUNITY]' => $dir->amount,
                    ];
            $this->bitrixCall->sendCurlRequest(http_build_query($bitrixObj),"update","crm.".$b24_method);

            $res = $this->bitrixCall->sendCurlRequest(['ID' => $b24_id],"get","crm.".$b24_method.".productrows");
            if (isset($res['result'])) {
                foreach ($res['result'] as $key => $value) {
                $this->bitrixCall->sendCurlRequest(['id' => $value['ID']],"delete","crm.item.productrow");
                }
            }
            $productRows = array();
            $course_count = 0;
            if (isset($dir->course1Details)) {
                array_push($productRows, ["PRODUCT_ID" => $dir->course1Details->b24_course_id,"PRICE" => $dir->course1Details->price,'DISCOUNT_RATE'=>0, "QUANTITY" => 1]);
                $course_count++;
            }
            if (isset($dir->course2Details)) {
                array_push($productRows,["PRODUCT_ID" => $dir->course2Details->b24_course_id,"PRICE" => $dir->course2Details->price,'DISCOUNT_RATE'=>0, "QUANTITY" => 1]);
                $course_count++;
            }
            if (isset($dir->course3Details)) {
                array_push($productRows,["PRODUCT_ID" => $dir->course3Details->b24_course_id,"PRICE" => $dir->course3Details->price,'DISCOUNT_RATE'=>0, "QUANTITY" => 1]);
                $course_count++;
            }
            if ($course_count == 2 || $course_count == 3) {
                $collection = collect($productRows);
                $productRows = $collection->map(function ($item) use ($course_count) {
                    $item['DISCOUNT_RATE'] = (($course_count == 2)?Courses::two_courses_discount:Courses::three_courses_discount);
                    return $item;
                })->toArray();
            }
            Log::channel('bitrix')->debug($productRows);
            $product['id']   = $b24_id;
            $product['rows'] = $productRows;
            $productresult = $this->bitrixCall->sendCurlRequest(http_build_query($product),'set','crm.'.$b24_method.'.productrows');
            Log::channel('bitrix')->debug($productresult);
            return response()->json(['msg' => 'success', 'res' => 'User Registration Updated Successfully']);
        }
        else{
            return response()->json(['msg' => 'error', 'res' => 'Error While Updating Details']);
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
