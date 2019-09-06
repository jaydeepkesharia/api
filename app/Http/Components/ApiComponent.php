<?php
namespace App\Http\Components;

use DB;
use Carbon\Carbon;


class ApiComponent {
	
	/*private function generate_code() {
		
		//$pin .= mt_rand(0, 9);
		$random_no= mt_rand(100000, 999999);
		$query = DB::table('devices')->where('unique_id',$random_no)->get();
		$count = $query->count();

		if($count > 0){
			return $this->generate_code();
		}else{
			return $random_no;
		}

	}*/

	/*public function insert_device_id($device_id) {

		$array = [
			'unique_id' => '',
			'new_user' => '',
		];

		$query = DB::table('devices')->select('unique_id')->where('device_id', '=', $device_id)->get();
		$count = $query->count();

		if ($count > 0) {

			$array['unique_id'] = $query->first()->unique_id;
			$array['new_user'] = 0;
		} else {
			$unique_id = $this->generate_code();
			
			DB::table('devices')->insert(
			    ['device_id' => $device_id, 'unique_id' => $unique_id,'created_at' =>Carbon::now() ]
			);

			$array['unique_id'] = $unique_id;
			$array['new_user'] = 1;
		}

		return $array;
	}*/
	public function check_unique_id($count_unique_id){
		$query = DB::table('devices')->where('unique_id',$count_unique_id)->get();
		$count = $query->count(); 
		return $count;
	}
	public function random_number($random_no){
		$query = DB::table('devices')->where('unique_id',$random_no)->get();
		$count = $query->count();
		return $count;
	}
	public function insert_device_id($device_id,$unique_id) {

		return	DB::table('devices')->insert(['device_id' => $device_id, 'unique_id' => $unique_id,'created_at' =>Carbon::now() ]);
	}
	public function select_device_id($device_id){

		$query = DB::table('devices')->select('unique_id')->where('device_id', '=', $device_id)->get();
		//$count = $query->count();

		return $query;
	}
	public function get_unique_id($device_id){

			return	$query = DB::table('contacts')->select('unique_id')->where('device_id', '=', $device_id)->groupBy('unique_id')->get();
				//$query;
	}
	public function count_unique_id($device_id){
		$query = DB::table('devices')->where('device_id', '=', $device_id)->get();
		$count = $query->count();
		return $count;
	}
	public function insert_call_history($device_id,$name,$mobile_no,$call_type,$date_time,$duration){

		$array = [
			'device_id' =>'',
			'name' => '',
			'mobile_no' => '',
			'call_type' => '',
			'date_time' => '',
			'duration' => ''
		];

		$history = DB::table('calls')->insert(['device_id' => $device_id, 'name' => $name, 'mobile_no' => $mobile_no, 'call_type' => $call_type, 'date_time' => $date_time, 'duration' => $duration,'created_at' =>Carbon::now() ]);

		$array['device_id'] = $device_id;
		$array['name'] 		= $name;
		$array['mobile_no'] = $mobile_no;
		$array['call_type'] = $call_type;
		$array['date_time'] = $date_time;
		$array['duration']  = $duration;

		return $array;
	}

	public function insert_device_name($device_id,$unique_id,$device_name){

		$array = [
			'device_id' =>'',
			'unique_id' => '',
			'device_name' => '',
			
		];

		if (DB::table('contacts')->where('device_id',$device_id)->where('unique_id',$unique_id)->get()->count() > 0) {
			DB::table('contacts')->where('unique_id',$unique_id)->where('device_id',$device_id)->update(['device_name' => $device_name]);
		} else {
			DB::table('contacts')->insert(['device_id' => $device_id,'unique_id' => $unique_id, 'device_name' => $device_name,'created_at' =>Carbon::now()]);
		}

		$array['device_id'] = $device_id;
		$array['unique_id'] = $unique_id;
		$array['device_name']= $device_name;
		

		return $array;
	}
	public function get_call_history($device_id,$unique_id){
			/*$unique = implode(',',$unique_id);
				dd($unique);*/
				
	if (! empty($unique_id)) {
			$data = DB::select("SELECT `calls`.`device_id`, `devices`.`unique_id`, `name`, `mobile_no`, `call_type`, `date_time`, `duration`, `calls`.`created_at`, (SELECT `contacts`.`device_name` FROM `contacts` WHERE `contacts`.`device_id` = `devices`.`device_id` AND  `contacts`.`unique_id` = `devices`.`unique_id` ORDER BY `contacts`.`created_at` DESC LIMIT 1) AS `device_name` FROM `calls` LEFT JOIN `devices` ON `calls`.`device_id` = `devices`.`device_id` WHERE FIND_IN_SET(`devices`.`unique_id`, (SELECT GROUP_CONCAT(`unique_id`) FROM `contacts` WHERE `device_id` = '$device_id')) AND `devices`.`unique_id` IN ($unique_id) ORDER BY `devices`.`unique_id`");
		} else {
			$data = DB::select("SELECT `calls`.`device_id`, `devices`.`unique_id`, `name`, `mobile_no`, `call_type`, `date_time`, `duration`, `calls`.`created_at`, (SELECT `contacts`.`device_name` FROM `contacts` WHERE `contacts`.`device_id` = `devices`.`device_id` AND  `contacts`.`unique_id` = `devices`.`unique_id` ORDER BY `contacts`.`created_at` DESC LIMIT 1) AS `device_name` FROM `calls` LEFT JOIN `devices` ON `calls`.`device_id` = `devices`.`device_id` WHERE FIND_IN_SET(`devices`.`unique_id`, (SELECT GROUP_CONCAT(`unique_id`) FROM `contacts` WHERE `device_id` = '$device_id')) ORDER BY `devices`.`unique_id`");
		}	
			//$data =  DB::table('calls')->where('device_id',$device_id)->get();
		

		return $data;
		//return DB::table('calls')->where('device_id',$device_id)->get();

		//dd($gethistory);
	}
}