<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Components\ApiComponent;
use Carbon\Carbon;
use Validator;

class ApiController extends Controller
{
	public $apiComponent;

	public function __construct(){
        $this->apiComponent = new ApiComponent();
	}

    private function generate_code(){
        
        //$pin .= mt_rand(0, 9);
        $random_no= mt_rand(100000, 999999);
        /*$query = DB::table('devices')->where('unique_id',$random_no)->get();*/
        $count =$this->apiComponent->random_number($random_no);
        

        if($count > 0){
            return $this->generate_code();
        }else{
            return $random_no;
        }
    }

    public function getUniqueId(Request $request)
    {

        $status_code = 200;
        $message = "success";
        //$data = [];
        $data = [];

        /*$this->validate($request,[
    		'device_id' => 'required',
    	]);*/

        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
        ]);

        if ($validator->fails()) {
            $status_code = 400;
            $message = "device_id is Required";
        } else {
            $device_id = $request->input('device_id');

            $count = $this->apiComponent->count_unique_id($device_id);

            if ($count > 0) {
                $query = $this->apiComponent->select_device_id($device_id);
                $data['unique_id'] = $query->first()->unique_id;
                $data['new_user'] = 0;

            } else {
                $unique_id = $this->generate_code();

                $this->apiComponent->insert_device_id($device_id, $unique_id);
               // dd($data);
                        
                       /* DB::table('devices')->insert(
                            ['device_id' => $device_id, 'unique_id' => $unique_id,'created_at' =>Carbon::now() ]
                        );*/

                $data['unique_id'] = $unique_id;
                $data['new_user'] = 1;
            }

            /*$data = $this->apiComponent->insert_device_id($device_id);*/
            $receiver_unique_id = $this->apiComponent->get_unique_id($device_id);
           //dd($receiver_unique_id);
            foreach ($receiver_unique_id as $item) {
                $data['receiver_unique_id'][] = $item->unique_id;
            }
            //dd($data);
        }

        return response()->json(['status_code' => $status_code, 'message' => $message, 'data' => $data]);
    }

    public function addDevice(Request $request)
    {
        $status_code = 200;
        $message = "success";
        $data = [];

        $validator1 = Validator::make($request->all(), [
            'device_id' => 'required',
        ]);

        $validator3 = Validator::make($request->all(), [
            'data' => 'required',
        ]);

        if ($validator1->fails()) {
            $status_code = 400;
            $message = "device_id is Required";  
        }elseif($validator3->fails()) {
            $status_code = 400;
            $message = "data is Required";
        }else{
            $device_id = $request->input('device_id');
           // dd($count2);
            $d = $request->input('data');  


            $count = $this->apiComponent->count_unique_id($device_id);
            if($count > 0){

            
                    foreach (json_decode($d) as $data1)
                    {
                        $name = $data1->name;
                        $mobile_no = $data1->mobile_no;
                        $call_type = $data1->call_type;
                        //$date_time = date('Y-m-d H:i:s',strtotime($data1->date_time));
                        $date_time = Carbon::parse($data1->date_time)->format('Y-m-d H:i:s');
                        $duration = $data1->duration;

                        $history = $this->apiComponent->insert_call_history($device_id,$name,$mobile_no,$call_type,$date_time,$duration);
                    }
                
            }else{
                $status_code = 400;
                $message = "Invalid device_id";
            }
        }
        return response()->json(['status_code' => $status_code, 'message' => $message, 'data' => $data]);
    }

    public function getCallHistory(Request $request){

        $status_code = 200;
        $message = "success";
        $data = [];
       

        $validator1 = Validator::make($request->all(), [
            'device_id' => 'required',
        ]);



        if ($validator1->fails()) {
            $status_code = 400;
            $message = "device_id is Required";
        }else{
            $device_id = $request->input('device_id');
            $count = $this->apiComponent->count_unique_id($device_id);
            if($count > 0){
                $device_id = $request->input('device_id');
                $unique_id = $request->input('unique_id');
                $data = $this->apiComponent->get_call_history($device_id,$unique_id);
            }else{
                $status_code = 400;
                $message = "Invalid device_id";
            }
        }

        return response()->json(['status_code' => $status_code, 'message' => $message, 'data' => $data]);
    }

    public function addDeviceName(Request $request)
    {
        $status_code = 200;
        $message = "success";
        $data = [];

        $validator1 = Validator::make($request->all(), [
            'device_id' => 'required',
        ]);

        $validator2 = Validator::make($request->all(), [
            'unique_id' => 'required',
        ]);

        $validator3 = Validator::make($request->all(), [
            'device_name' => 'required',
        ]);

        if ($validator1->fails()) {
            $status_code = 400;
            $message = "device_id is Required";  
        }elseif($validator2->fails()) {
            $status_code = 400;
            $message = "unique_id is Required";  
        }elseif($validator3->fails()) {
            $status_code = 400;
            $message = "device_name is Required";
        }else{
            $device_id = $request->input('device_id');
            $unique_id = $request->input('unique_id');
            $device_name = $request->input('device_name'); 
            $count_device_id = $this->apiComponent->count_unique_id($device_id);
            $count_unique_id = $this->apiComponent->check_unique_id($unique_id);
             

            if($count_device_id > 0){

                if($count_unique_id == 0){
                    $status_code = 400;
                    $message = "Invalid unique_id";
                }else{
                    $history = $this->apiComponent->insert_device_name($device_id,$unique_id,$device_name);
                }
            }else{
                $status_code = 400;
                $message = "Invalid device_id";
            }
        }
        return response()->json(['status_code' => $status_code, 'message' => $message, 'data' => $data]);
    }
}

