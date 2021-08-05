<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DB;
use App\User;
Use App\Models\EventView;
Use App\Models\FavouriteEvent;
use Auth;
use Carbon\Carbon;
Use App\Models\EventList;
class ResponseController extends Controller{
	
		public function is_require($data,$field){
		   if(empty($data)){
			   $message = $field." field is required";
			   http_response_code(400);
			   echo json_encode(['result'=>'Failure','message'=>$message]);exit;
			   // echo json_encode(['return'=>0,'result'=>'Failure','message'=>$message]);exit;
		   }else{
			   return $data;
		   } 
	   }
   
	   public function responseOk($message,$data){
			if(!empty($data)){
			   http_response_code(200);
			   echo json_encode(['result'=>'Success','message'=>$message,'data'=>$data]);exit;
				// echo json_encode(['return'=>1,'result'=>'Success','message'=>$message,'data'=>$data]);exit;
			}else{
				http_response_code(200);
			   echo json_encode(['result'=>'Success','message'=>$message]);exit;
				// echo json_encode(['return'=>1,'result'=>'Success','message'=>$message]);exit;
			}
	   }
   
	   public function responseWithError($message){
		   http_response_code(400);
		   echo json_encode(['result'=>'Failure','message'=>$message]);exit;
			// echo json_encode(['return'=>0,'result'=>'Failure','message'=>$message]);
			// exit;
	   }
	   public function responseWithblock($message){
		   http_response_code(403);
		   echo json_encode(['result'=>'Failure','message'=>$message]);exit;
			// echo json_encode(['return'=>0,'result'=>'Failure','message'=>$message]);
			// exit;
	   }
   
	   public function checkUserExist($user_id){
		   $userId = $user_id;
		 //  print $userId;die;
		   if(!empty($userId)){
			   $get_data = User::where('id',$userId)->first();
			   if(!empty($get_data)){
				   return $get_data;
			   }else{
				   http_response_code(401);
				   echo json_encode(['result'=>'Failure','message'=>'user does not exist']);exit;
			   }
			}else{
				http_response_code(402);
			   echo json_encode(['result'=>'Failure','message'=>'user does not exist']);exit;
			}
	   }
	   
		
		public function getUserBlockList($userId){
			$get_your_block_list = DB::table('userblocklist')->where(['userId' => $userId , 'blockstatus' => 1])->pluck('otherUserId');
			$get_you_block_by_other = DB::table('userblocklist')->where(['otherUserId' => $userId , 'blockstatus' => 1])->pluck('userId');
			$collection = collect($get_your_block_list);
			$merged = $collection->merge($get_you_block_by_other);
			$data = $merged->all();
			return $data;
		}
   
		public static function send_iphone_notification($recivertok_id,$message,$notmessage='',$msgsender_id=''){  
			$PATH = dirname(dirname(dirname(dirname(__FILE__))))."/pem/DevPush.pem";
			//$PATH = dirname(dirname(dirname(dirname(__FILE__))))."/pemfile/DevPush.pem";
			$deviceToken = $recivertok_id;
			$passphrase = "";
			//$passphrase = 123456789;
			$message = $message;
			$ctx = stream_context_create();
				   stream_context_set_option($ctx, 'ssl', 'local_cert', $PATH);
			       stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			
			 //   $fp = stream_socket_client(
				// 						'ssl://gateway.push.apple.com:2195', $err,
				// $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); 
			   
			    $fp = stream_socket_client(
										'ssl://gateway.sandbox.push.apple.com:2195', $err,
				$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); 
			  
		
			if (!$fp)
				 exit("Failed to connect: $err $errstr" . PHP_EOL);

				$body['aps'] = array(
					'alert' => $message,
					'sound' => 'default',
					'Notifykey' => $notmessage, 
					'msgsender_id'=>$msgsender_id
				);

			$payload = json_encode($body);
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			$result = fwrite($fp, $msg, strlen($msg));

			//echo "<pre>";
			// print_r($body);
			
			// if (!$result)
				// echo 'Message not delivered' . PHP_EOL;
			// else
				// echo 'Message successfully delivered' . PHP_EOL;
			// exit;
			fclose($fp);
			return  $result;
		}
	
	public static function send_iphone_notification2($recivertok_id,$message,$notmessage='',$msgsender_id=''){  
		$PATH = dirname(dirname(dirname(dirname(__FILE__))))."/pem/DevPush.pem";
		//print $PATH;die;
		$deviceToken = $recivertok_id;
		$passphrase = "";
		//$passphrase = 123456789;
		$message = $message;
		$ctx = stream_context_create();
			   stream_context_set_option($ctx, 'ssl', 'local_cert', $PATH);
			   stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		
				$fp = stream_socket_client(  
									'ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); 
		
		if (!$fp)
			 exit("Failed to connect: $err $errstr" . PHP_EOL);

			$body['aps'] = array(
				'alert' => $message,
				'sound' => 'default',
				'Notifykey' => $notmessage, 
				'msgsender_id'=>$msgsender_id
			);

		$payload = json_encode($body);
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		//sleep(5);
		$result = fwrite($fp, $msg, strlen($msg));
         
		// echo "<pre>";
		// print_r($result);die;
	     // if (!$result)
			 // echo 'Message not delivered' . PHP_EOL;
		 // else
			 // echo 'Message successfully delivered' . PHP_EOL;
		 // exit;
		fclose($fp);
		return  $result;
	}
		
		
		function send_android_notification($device_token,$message,$notmessage='',$msgsender_id=''){
			
			if (!defined('API_ACCESS_KEY'))
			{
				define('API_ACCESS_KEY','AIzaSyCpGtCxr9NjjohLSRhT1AIps3XrHgZNrF4');
			}
			$registrationIds = array($device_token);
				// echo "<pre>";
			// print_r($msgsender_id);exit;
			// $fields = array(
				// 'registration_ids' => $registrationIds,
				// 'alert' => $message,
				// 'sound' => 'default',
				// 'Notifykey' => $notmessage, 
				// 'data'=>$msgsender_id
			// );
			
			$fields = array(
				'registration_ids' => $registrationIds,
				'alert' => $message,
				'sound' => 'default',
				'Notifykey' => $notmessage, 
				'data' => $msgsender_id
					
			);

			$headers = array(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			// echo "<br>";
			// print_r($headers);

			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields) );
			$result = curl_exec($ch);

			if($result == FALSE) {
				die('Curl failed: ' . curl_error($ch));
			}

			// echo curl_error($ch);
			curl_close( $ch );
			// echo "<pre>";print_r($result);exit;
			// echo "<br>";
			return  $result;

		}
	
		public static function randomNumber($length) {
			$result = '';
			for($i = 0; $i < $length; $i++) {
				$result .= mt_rand(0, 9);
			}
			return $result;
		}
		
		
		public function getUserBlockList22($userId){
			$get_your_block_list = DB::table('userblocklist')->where(['userId' => $userId , 'blockstatus' => 1])->pluck('otherUserId');
			$get_you_block_by_other = DB::table('userblocklist')->where(['otherUserId' => $userId , 'blockstatus' => 1])->pluck('userId');
			$collection = collect($get_your_block_list);
			$merged = $collection->merge($get_you_block_by_other);
			$data = $merged->all();
			return $data;
		}
		
		public function get_user_block_list($user_id){
			$get_your_block_list = DB::table('block_users')->where(['user_id'=>$user_id])->pluck('friend_id');
			$get_you_block_by_other = DB::table('block_users')->where(['friend_id'=>$user_id])->pluck('user_id');
			$collection = collect($get_your_block_list);
			$merged = $collection->merge($get_you_block_by_other);
			$data = $merged->all();
			return $data;
		}
		
		public function get_user_block_by_admin($user_id){
			$get_you_block_by_other = DB::table('users')->where(['users.is_deleted'=>2,'users.blockStatus'=>1])->where('users.status','!=',1)->pluck('id');
			return $get_you_block_by_other;
		}
		
		public function get_event_block_list($user_id){
			$get_your_block_list = DB::table('event_reports')->where(['user_id'=>$user_id,'report_status' => 1])->pluck('event_id');
			return $get_your_block_list;
		}
		
		
		public function createNotification($userId,$message,$notificationType,$otherId,$friendId = 0){
			// $notificationType - 1 => newsfeed,  2 => new event created by admin, 3 => check_in, 4 => follow notification, 5 => comment on post, 6 => reply on comment, 7 reply on post, 8 => new message, 9 => new job by connected user, 10 => new job by connected admin, 11 => add_member_in_group , 12 =>  like_post
			if($notificationType == 1 || $notificationType == 3 || $notificationType == 9){
				$get_user_follow = DB::table('followunfollowlist')->where('friendId',$userId)->pluck('userId');
				if(count($get_user_follow) > 0){
					for($i =0;$i<count($get_user_follow);$i++){
						if($get_user_follow[$i] != $userId){
							$get_user_data = DB::table('users')->where('userId',$get_user_follow[$i])->first();
							if(isset($get_user_data) && $get_user_data->newPostbyconnectedUser == 1){
								$notify_data = array('userId' => $get_user_follow[$i],'otherUserId' => $userId,'notificationType' => $notificationType,'message' => $message,'milliseconds' => round(microtime(true) * 1000),'updated_at' => date('Y-m-d H:i:s'),'createdAt' => date('Y-m-d H:i:s'),'status' => 2,'otherId' => $otherId);
								DB::table('notificationlist')->insert($notify_data);
							}
						}
					}
				}
			}else if($notificationType == 2 || $notificationType == 10){
				if($notificationType == 2){
					$get_school_user_list = DB::table('users')
					->where(['schoolId' => $userId,'role' => 1, 'status' => 1, 'emailStatus' => 1, 'blockStatus' => 2])
					->pluck('userId');
				}else{
					$get_school_user_list = array();
				}
				
				if(count($get_school_user_list) > 0){
					for($i=0;$i<count($get_school_user_list);$i++){
						$get_user_data = DB::table('users')->where('userId',$get_school_user_list[$i])->first();
						if(isset($get_user_data) && $get_user_data->newPostbyschool == 1){
							$notify_data = array('userId' => $get_school_user_list[$i],'otherUserId' => '','notificationType' => $notificationType,'message' => $message,'milliseconds' => round(microtime(true) * 1000),'updated_at' => date('Y-m-d H:i:s'),'createdAt' => date('Y-m-d H:i:s'),'status' => 2,'otherId' => $otherId);
							DB::table('notificationlist')->insert($notify_data);
						}
					}
				}	
			}else if($notificationType == 4 || $notificationType == 5 || $notificationType == 6 || $notificationType == 7 || $notificationType == 8 || $notificationType == 11 || $notificationType == 12){
				if($friendId != $userId){	
					$notify_data = array('userId' => $friendId,'otherUserId' => $userId,'notificationType' => $notificationType,'message' => $message,'milliseconds' => round(microtime(true) * 1000),'updated_at' => date('Y-m-d H:i:s'),'createdAt' => date('Y-m-d H:i:s'),'status' => 2,'otherId' => $otherId);
					DB::table('notificationlist')->insert($notify_data);
				}
			}else{
				
			}
		}
		
		public function createIphoneNotification($userId,$message,$notificationType,$otherId,$friendId = 0){
			// $notificationType - 1 => newsfeed,  2 => new event created by admin, 3 => check_in, 4 => follow notification, 5 => comment on post, 6 => reply on comment, 7 reply on post, 8 => new message, 9 => new job by connected user, 10 => new job by connected admin, 11 => add_member_in_group , 12 =>  like_post
			$action_user_data = DB::table('users')->where(['userId' => $userId])->first();
			if($notificationType == 1 || $notificationType == 3 || $notificationType == 9){
				$get_user_follow = DB::table('followunfollowlist')->where('friendId',$userId)->pluck('userId');
				if($notificationType == 1){
					$message_push = ucfirst($action_user_data->name)." ".$message;
					$notifykey = 'add_newsfeed';
					$data = array('otherUserId' => $userId,'otherId' => $otherId,'notificationType' => $notificationType,'notifykey' => 'add_newsfeed','message' => $message);
				}else if($notificationType == 3){
					$message_push = ucfirst($action_user_data->name)." ".$message;
					$notifykey = 'check_in';
					$data = array('otherUserId' => $userId,'otherId' => $otherId,'notificationType' => $notificationType,'notifykey' => 'check_in','message' => $message);
				}else{
					$message_push = ucfirst($action_user_data->name)." ".$message;
					$notifykey = 'new_job';
					$data = array('otherUserId' => $userId,'otherId' => $otherId,'notificationType' => $notificationType,'notifykey' => 'new_job','message' => $message);
				}
				
				if(count($get_user_follow) > 0){
					for($i=0;$i<count($get_user_follow);$i++){
						if($get_user_follow[$i] != $userId){
							$get_user_data = DB::table('users')->where('userId',$get_user_follow[$i])->first();
							if(isset($get_user_data) && $get_user_data->newPostbyconnectedUser == 1){
								$this->send_iphone_notification($get_user_data->deviceToken,$message_push,$notifykey,$data);
							}
						}	
					}
				}
			}else if($notificationType == 2 || $notificationType == 10){
				if($notificationType == 10){
					$message_push = ucfirst($action_user_data->name)." ".$message;
					$notifykey = 'new_job_by_admin';
					$data = array('otherUserId' => $userId,'otherId' => $otherId,'notificationType' => $notificationType,'notifykey' => 'new_job_by_admin','message' => $message);
					$get_school_user_list = DB::table('users')->where(['schoolId' => $userId,'role' => 1, 'status' => 1, 'emailStatus' => 1, 'blockStatus' => 2])->pluck('userId');
				}else{
					$message_push = ucfirst($action_user_data->name)." ".$message;
					$notifykey = 'new_event_by_admin';
					$data = array('otherUserId' => $userId,'otherId' => $otherId,'notificationType' => $notificationType,'notifykey' => 'new_event_by_admin','message' => $message);
					$get_school_user_list = array();
				}
				if(count($get_school_user_list) > 0){
					for($i=0;$i<count($get_school_user_list);$i++){
						$get_user_data = DB::table('users')->where('userId',$get_school_user_list[$i])->first();
						if(isset($get_user_data) && $get_user_data->newPostbyschool == 1){
							$this->send_iphone_notification($get_user_data->deviceToken,$message_push,$notifykey,$data);
						}
					}
				}
				
				
			}else if($notificationType == 4 || $notificationType == 5 || $notificationType == 6 || $notificationType == 7 || $notificationType == 8 || $notificationType == 11 || $notificationType == 12){
				if($friendId != $userId){
					$get_user_data = DB::table('users')->where('userId',$friendId)->first();
					if($notificationType == 4 && !empty($get_user_data) && $get_user_data->newconnectionrequest == 1){
						$message_push = ucfirst($action_user_data->name)." ".$message;
						$notifykey = 'follow';
						$data = array('otherUserId' => $userId,'otherId' => $otherId,'notificationType' => $notificationType,'notifykey' => 'follow','message' => $message);
						$this->send_iphone_notification($get_user_data->deviceToken,$message_push,$notifykey,$data);
					}else if($notificationType == 5 && !empty($get_user_data) && $get_user_data->userreplytomypost == 1){
						$message_push = ucfirst($action_user_data->name)." ".$message;
						$notifykey = 'comment_on_post';
						$data = array('otherUserId' => $userId,'otherId' => $otherId,'notificationType' => $notificationType,'notifykey' => 'comment_on_post','message' => $message);
						$this->send_iphone_notification($get_user_data->deviceToken,$message_push,$notifykey,$data);
					}else if($notificationType == 6 && !empty($get_user_data) && $get_user_data->userreplytomypost == 1){
						$message_push = ucfirst($action_user_data->name)." ".$message;
						$notifykey = 'reply_on_comment';
						$data = array('otherUserId' => $userId,'otherId' => $otherId,'notificationType' => $notificationType,'notifykey' => 'reply_on_comment','message' => $message);
						$this->send_iphone_notification($get_user_data->deviceToken,$message_push,$notifykey,$data);
					}else if($notificationType == 7 && !empty($get_user_data) && $get_user_data->userreplytomypost == 1){
						$message_push = ucfirst($action_user_data->name)." ".$message;
						$notifykey = 'reply_on_post';
						$data = array('otherUserId' => $userId,'otherId' => $otherId,'notificationType' => $notificationType,'notifykey' => 'reply_on_post','message' => $message);
						$this->send_iphone_notification($get_user_data->deviceToken,$message_push,$notifykey,$data);
					}else if($notificationType == 8 && !empty($get_user_data) && $get_user_data->newmessage == 1){
						$notifykey = 'new_message';
						$message_push = ucfirst($action_user_data->name)." ".$message;
						$data = array('otherUserId' => (int)$userId,'otherId' => (int)$otherId,'notificationType' => $notificationType,'notifykey' => 'new_message','message' => $message_push,'name' => $action_user_data->name, 'profileImageUrl' => $action_user_data->profileImageUrl );
						$this->send_iphone_notification($get_user_data->deviceToken,$message_push,$notifykey,$data);
					}else if($notificationType == 11 && !empty($get_user_data)){
						$get_group_data = DB::table('groups')->where('groupId',$otherId)->first();
						$get_group_member = DB::table('groupuserlist')->where(['groupId' => $otherId, 'userId' => $friendId, 'userType' => 'Admin'])->first();
						if(!empty($get_group_member)){
							$isAdmin = 'Yes';
						}else{
							$isAdmin = 'No';
						}
						if(!empty($get_group_data)){
							$notifykey = 'add_member_in_group';
							$message_push = ucfirst($action_user_data->name)." ".$message;
							$data = array('otherUserId' => (int)$userId,'otherId' => (int)$otherId,'notificationType' => $notificationType,'notifykey' => 'add_member_in_group','message' => $message_push,'groupName' => $get_group_data->groupName, 'groupImageUrl' => $get_group_data->groupImageUrl,'isAdmin' => $isAdmin);
							$this->send_iphone_notification($get_user_data->deviceToken,$message_push,$notifykey,$data);
						}
						
					}else if($notificationType == 12 && !empty($get_user_data)){
						$notifykey = 'like_post';
						$message_push = ucfirst($action_user_data->name)." ".$message;
						$data = array('otherUserId' => (int)$userId,'otherId' => (int)$otherId,'notificationType' => $notificationType,'notifykey' => 'like_post','message' => $message_push,'name' => $action_user_data->name, 'profileImageUrl' => $action_user_data->profileImageUrl );
						$this->send_iphone_notification($get_user_data->deviceToken,$message_push,$notifykey,$data);
					}
				}
			}else{
				
			}
		}

		
		function refferal_num($insert_user){
			$size=3;
			$alpha_key = $insert_user;
			$keys = range('A', 'Z');
			for ($i = 0; $i < 2; $i++) {
				$alpha_key .= $keys[array_rand($keys)];
			}
			$length = $size - 2;
			$key = '';
			$keys = range(0, 9);
			for ($i = 0; $i < $length; $i++) {
				$key .= $keys[array_rand($keys)];
			}
			return $alpha_key . $key;
		}	


	function add_tickets($tickets,$event_id){
		$add_tickets=json_decode($tickets);
		//echo $add_tickets;die();
		foreach($add_tickets as $key => $add_ticket){
			if(empty($add_ticket->ticket_title)){
				return 2;
			}
			if(empty($add_ticket->ticket_description)){
				return 2;
			}
			if(empty($add_ticket->ticket_amount)){
				return 2;
			}
			if(empty($add_ticket->ticket_quantity)){
				return 2;
			}
		}
		foreach($add_tickets as $add_ticket){
			$save_data['ticket_title']=$add_ticket->ticket_title;
			$save_data['ticket_description']=$add_ticket->ticket_description;
			$save_data['ticket_amount']=$add_ticket->ticket_amount;
			$save_data['ticket_quantity']=$add_ticket->ticket_quantity;
			$save_data['event_id']=$event_id;
			$save_data['created_at']=Date('y-m-d H:i:s');
			$save_data['updated_at']=Date('y-m-d H:i:s');
			$insert_id = DB::table('tickets')->insertGetId($save_data);
	    }
    }
    
    function get_tickets($event_id,$val){
    	// return $event_id;
    	$get_tickets=DB::table('tickets')->where(['event_id'=>$event_id])->first();
    	// print_r($get_tickets);
        $get_ticketss=DB::table('tickets')->where(['event_id'=>$event_id])
        ->groupBy('ticket_quantity')
        ->sum('ticket_quantity');

    	$get_tickets1=DB::table('bought_tickets')->select(DB::raw("sum(quantity) as sum"))->where(['event_id'=>$event_id])->first()->sum;
		$total_ticket = ($get_ticketss  - $get_tickets1);
		if(!empty($get_tickets)){
			if($val=='ticket_count'){
				return $total_ticket ;
			}elseif($val=='price'){
				return $get_tickets->ticket_amount;
			}
		}else{
			return 0;
		}
    }

    public function get_tickets_status($event_id,$sub_event_id){
    	// return $sub_event_id;
		$get_event=DB::table('event_schedule')->where(['id'=>$sub_event_id])->first();
	    $ticket_statusss = DB::table('tickets')->where(['event_id'=>$get_event->event_id])->first();
	    $check_ticket = DB::table('close_tickets')->where(['event_id'=>$sub_event_id])->first();
		// print_r($ticket_statusss);die;
		return $status = isset($check_ticket) ? 1  : 0 ;
	}


     function get_ticketss($event_id,$val,$sub_event_id){
     	    //echo $id;die; //1497
     		//echo $event_id; //105

    	// return $sub_event_id;
  		// $event_id = $event_id;
    	$get_tickets = DB::table('tickets')->where(['event_id'=>$event_id])->first();
    	// print_r($get_tickets); die;

        /*800 Available tickets */
        $get_ticketss = DB::table('tickets')->where(['event_id'=>$event_id])
					        ->groupBy('ticket_quantity')
					        ->sum('ticket_quantity');

       // DB::enableQueryLog();
		$get_tickets1 = DB::table('bought_tickets')->where('sub_event_id',$sub_event_id)
							->where('whom_purchase','normal')
        					//->groupBy('quantity')
			            	->groupBy('event_id')
			    	  		->sum('quantity'); 
    	  //$a = DB::getQueryLog($get_tickets1);
    	  //print_r($a); die;
    	  	//echo $event_id;die;



    	  	/*$a =DB::table('bought_tickets')->where('sub_event_id',$event_id)->select('quantity')->get();

    	  	$sum = 0;
    	  	foreach ($a as $value) {
    	  	   $sum+= $value->quantity;
    	  	}*/
    	  	//$get_tickets1 = DB::table('bought_tickets')->where('sub_event_id',$sub_event_id)->groupBy('quantity')->sum('quantity');
    	  	//print_r($total);die;
		$total_ticket = ($get_ticketss  - $get_tickets1);
		if(!empty($get_tickets)){
			if($val=='ticket_count'){
				return $total_ticket ;
			}elseif($val=='price'){
				return $get_tickets->ticket_amount;
			}
		} else {
			return 0;
		}
    }

    /*function get_tickets1($event_id,$val){
		 $get_tickets=DB::table('tickets')->where(['event_id'=>$event_id])->>sum('quantity');
		  echo "<pre>";print_r($get_tickets);die;
	 $get_bought_tickets = DB::table('bought_tickets')->select('quantity')->where(['event_id'=>$get_tickets->event_id,'ticket_id'=>$get_tickets->id])->get();
	 foreach ($get_bought_tickets as $value) { 
	 	$get_bought_tickets_new = $value->quantity;
	 }
	 
		 if (!empty($get_tickets)  && $get_tickets != null )
		 {
			if($get_bought_tickets > 0){

				if($val=='ticket_count'){
			 	return $get_tickets->ticket_quantity - $get_bought_tickets_new->quantity;
			}
				elseif($val=='price'){
					return $get_tickets->ticket_amount;
			}
			}
			else if ($get_bought_tickets==0){
				if($val=='ticket_count'){
					return $get_tickets->ticket_quantity;
				}elseif($val=='price'){
					return $get_tickets->ticket_amount;
				}
		}
			else {
				return 0;
				}

		 }
		
		else{
			return 0;
		}
    }*/
    public function event_views($user_id,$sub_event_id){
    	// return $sub_event_id;
			$get_event_id=DB::table('event_schedule')->where(['id'=>$sub_event_id])->first();
			$block_user = DB::table('block_users')->where('user_id',$user_id)
				->distinct('friend_id')
	            ->pluck('friend_id');
			$bought_tickets=DB::table('bought_tickets')->where('event_id',$get_event_id->event_id)
				->whereNotIn('user_id',$block_user)
				->distinct('user_id')
				->pluck('user_id');
			return $bought_tickets->count();
			// $block_user = DB::table('block_users')->where('user_id',$user_id)
			// ->distinct('friend_id')
   //          ->pluck('friend_id');
			// // return $get_event_id->event_id;
	  //       $save_data['user_id']=$user_id;
	  //       $save_data['event_id']=$get_event_id->event_id;
	  //       $save_data['sub_event_id']=$sub_event_id;
	  //       $save_data['created_at']=Date('Y-m-d H:i:s');
   //          EventView::updateOrCreate(['user_id'=>$user_id,'sub_event_id'=>$sub_event_id],$save_data);		
			// $event_views=EventView::where(['sub_event_id'=>$sub_event_id])->count();		
			// return $bought_tickets;
		}


	public function is_join($user_id,$sub_event_id) {
		$get_event_id=DB::table('event_schedule')->where(['id'=>$sub_event_id])->first();
		$bought_tickets=DB::table('bought_tickets')->where(['event_id'=>$get_event_id->event_id,'user_id' => $user_id])->count();
		if($bought_tickets > 0) {
			return 1;
		} else {
			return 0;
		}

	}

	

	public function favourite_status($user_id,$sub_event_id){
		// return $sub_event_id;
    	$check_favourite=FavouriteEvent::where(['user_id'=>$user_id,'sub_event_id'=>$sub_event_id])->first();
	    if($check_favourite){
	        return 1;
	    }else{
	    	return 2;
	    }
	}	


	public function eventListFun($user_id){
		$id=$user_id;   
		#status=>1 not aprove 2 approved
		$now = Carbon::now();
		$date=$now->toDateString();
		$match_date=date('Y-m-d H:i:s');
		//$subDay=$now->subDay();
		//$event_time = $this->is_require($request->input('event_time'),'event_time');
		$end_dt=date('Y-m-d');
		$time=date('H:i:s');
		//print $time;
		$date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		$match_date_range=date('Y-m-d', strtotime("+2 week", strtotime($end_dt)));	
		
			// $invitations=DB::table('invitations')
			// 			->where(function ($query) use ($id){
			// 				$query->where(['sender_id'=>$id]);
			// 				$query->where(['request_status'=>1]);
			// 			})->orWhere(function($query) use ($id){
			// 				$query->where(['receiver_id'=>$id]);
			// 				$query->where(['request_status'=>1]);
			// 			})->select('sub_event_id')
			// 			 ->distinct('sub_event_id')
			// 			 ->pluck('sub_event_id');
			// $get_event= EventList::
			// 	leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			// 	->where(['status' =>2])
			// 	->whereNotIn('event_list.id',$this->get_event_block_list($id))
			// 	->where(function ($query) use ($id,$match_date,$time,$date){
			// 		$query->where(['event_list.user_id'=>$id]);
			// 		$query->where(['submit_by'=>2]);
			// 	})->orWhere(function($query) use ($id,$date,$time,$match_date){
			// 		$query->where(['submit_by'=>3,'status'=>2]);
			// 		$query->whereDate('event_schedule.event_date',$date);
			// 		$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			// 		$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			// 	})->orWhere(function($query) use ($invitations,$date,$time,$id,$match_date){
			// 		$query->whereIn('event_schedule.id',$invitations);
			// 		$query->where(['submit_by'=>2]);  
			// 		$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			// 		$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			// 	})->orderBy('event_schedule.event_start_date_time','ASC')
			//       ->paginate(10);   
			$invitations=DB::table('invitations')
						->where(function ($query) use ($id){
							$query->where(['sender_id'=>$id]);
							$query->where(['request_status'=>1]);
						})->orWhere(function($query) use ($id){
							$query->where(['receiver_id'=>$id]);
							$query->where(['request_status'=>1]);
						})->select('sub_event_id')
						 ->distinct('sub_event_id')
						 ->pluck('sub_event_id');
			$get_event= EventList::
				leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				->where(['status' =>2])
				->where(['event_list.user_id'=>$id])
				->whereDate('event_schedule.event_date','>=',$date)
				->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
				->whereNotIn('event_list.id',$this->get_event_block_list($id))
				->orderBy('event_schedule.event_start_date_time','ASC')
			      ->paginate(10);   
		foreach($get_event as $eachEvent){
			$eachEvent->postList->take(10);
			$eachEvent->post_list_count = $eachEvent->postList->count();
			
			$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
			
			$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->pluck('music_interest_id');
			$event_public_interest_list=DB::table('event_public_interest_list')->where(['event_id'=>$eachEvent->event_id])->pluck('public_interest_id');

			if(!empty($event_music_interest_list)){
				$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',$event_music_interest_list)->get();
			}else{
				$eachEvent->music_interest_id=array();	
			}
			
			if(!empty($event_public_interest_list)){
				$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',$event_public_interest_list)->get();
			}else{
				$eachEvent->public_interest_id=array();	
			}
			// if(!empty($eachEvent->music_int_id)){
			// 	$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
			// }else{
			// 	$eachEvent->music_interest_id=array();	
			// }

			// if(!empty($eachEvent->public_int_id)){
			// 	$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
			// }else{
			// 	$eachEvent->public_interest_id=array();	
			// }
			
			$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,/* 'request_status'=>1 */])->first();
			if(!empty($check_event)){
					$eachEvent->attend_status=1;
			}else{      
				if($id == $eachEvent->user_id){
					$eachEvent->attend_status=1;
				}else{
					$eachEvent->attend_status=2;
				}
			}
			$eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
			$eachEvent->ticket_price=$this->get_tickets($eachEvent->event_id,'price');
			$eachEvent->event_views=$this->event_views($id,$eachEvent->id);	
			$eachEvent->ticket_status =$this->get_tickets_status($eachEvent->event_id,$eachEvent->id);
			$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);
			$eachEvent->ticket_detail=DB::table('bought_tickets')
			    ->leftJoin('tickets','tickets.id','=','bought_tickets.ticket_id')
			    ->where(['tickets.event_id'=>$eachEvent->event_id,'bought_tickets.user_id'=>$id])
			    ->first();
		} 

		return $get_event;
		
	}

	public function get_sold_tickets($sub_get_event){
		// return $sub_get_event;
		$get_event_id=DB::table('event_schedule')->where(['id'=>$sub_get_event])->first();
		$bought_tickets=DB::table('bought_tickets')->where('event_id',$get_event_id->event_id)->sum('quantity');
		return $bought_tickets;
		//return $bought_tickets->count();
    	  // return  DB::table('bought_tickets')->where(['sub_event_id'=>$sub_get_event])->sum('quantity');
    }


    public function current_events($id,$match_date,$invitations,$time,$date){
    	// return $date;
    return $get_event= EventList::
				leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				->where(['status' =>2])
				->whereNotIn('event_list.id',$this->get_event_block_list($id))
				->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
				->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id))
				
				->where(function ($query) use ($id,$match_date,$time,$date){
					$query->where(['event_list.user_id'=>$id]);
					$query->where(['submit_by'=>2]);
					$query->where('event_schedule.event_start_date_time','<',$match_date);
					$query->where('event_schedule.event_end_date_time','>=',$match_date);
				})->orWhere(function($query) use ($id,$date,$time,$match_date){
					$query->where(['submit_by'=>3,'status'=>2]);
					$query->where('event_schedule.event_start_date_time','<',$match_date);
					$query->where('event_schedule.event_end_date_time','>=',$match_date);
				})->orWhere(function($query) use ($invitations,$date,$time,$id,$match_date){
					$query->whereIn('event_schedule.id',$invitations);
					$query->where('event_schedule.event_start_date_time','<',$match_date);
					$query->where('event_schedule.event_end_date_time','>=',$match_date);
				})->orderBy('event_schedule.event_start_date_time','ASC')
			      ->pluck('event_schedule.id');   
	// return	$get_event= EventList::
	// 	leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
	// 	->where(['status' =>2])
	// 	->whereNotIn('event_list.id',$this->get_event_block_list($id))
	// 	->where(function ($query) use ($id,$match_date,$time,$date){
	// 		$query->where(['event_list.user_id'=>$id]);
	// 		$query->where(['submit_by'=>2]);
	// 		$query->where('event_schedule.event_start_date_time','<',$match_date);
	// 		$query->where('event_schedule.event_end_date_time','>=',$match_date);
	// 	})->orWhere(function($query) use ($id,$date,$time,$match_date){
	// 		$query->where(['submit_by'=>3,'status'=>2]);
	// 		$query->whereDate('event_schedule.event_date',$date);
	// 		$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
	// 		$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
	// 		$query->where('event_schedule.event_start_date_time','<',$match_date);
	// 		$query->where('event_schedule.event_end_date_time','>=',$match_date);
	// 	})->orWhere(function($query) use ($invitations,$date,$time,$id,$match_date){
	// 		$query->whereIn('event_schedule.id',$invitations);
	// 		$query->where(['submit_by'=>2]);  
	// 		$query->where('event_schedule.event_start_date_time','<',$match_date);
	// 		$query->where('event_schedule.event_end_date_time','>=',$match_date);
	// 		$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
	// 		$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
	// 	})->orderBy('event_schedule.event_start_date_time','ASC')
	// 	  ->pluck('event_schedule.id');  
 	}

    public function current_events_2($match_date,$invitations,$time,$date){
	return	$get_event= EventList::
		leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
		->where(['status' =>2])
		// ->whereNotIn('event_list.id',$this->get_event_block_list($id))
		->where(function ($query) use ($match_date,$time,$date){
			// $query->where(['event_list.user_id'=>$id]);
			$query->where(['submit_by'=>2]);
			$query->where('event_schedule.event_start_date_time','<',$match_date);
			$query->where('event_schedule.event_end_date_time','>=',$match_date);
		})->orWhere(function($query) use ($date,$time,$match_date){
			$query->where(['submit_by'=>3,'status'=>2]);
			$query->whereDate('event_schedule.event_date',$date);
			// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			$query->where('event_schedule.event_start_date_time','<',$match_date);
			$query->where('event_schedule.event_end_date_time','>=',$match_date);
		})->orWhere(function($query) use ($invitations,$date,$time,$match_date){
			$query->whereIn('event_schedule.id',$invitations);
			$query->where(['submit_by'=>2]);  
			$query->where('event_schedule.event_start_date_time','<',$match_date);
			$query->where('event_schedule.event_end_date_time','>=',$match_date);
			// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
		})->orderBy('event_schedule.event_start_date_time','ASC')
		  ->pluck('event_schedule.id');  
    }

 	public function past_events($id,$match_date,$time,$date,$invitations){

		return $get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
			->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id))
			->where(function ($query) use ($id,$match_date,$date){
				$query->where(['event_list.user_id'=>$id]);                  //event submited by user
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orWhere(function($query)use ($id,$match_date,$date){
				$query->where(['submit_by'=>3,'status'=>2]);               //event submited by organizer
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orWhere(function($query) use ($invitations,$match_date,$id,$date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);                         //event submited by other but invited 
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->pluck('event_schedule.id');
   //  return	$get_event= EventList::where(['status' =>2])
			// ->whereNotIn('event_list.id',$this->get_event_block_list($id))
			// ->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			// ->where(function ($query) use ($id,$match_date,$date){
			// 	$query->where(['event_list.user_id'=>$id]);                  //event submited by user
			// 	$query->where(['submit_by'=>2]);
			// 	$query->where('event_schedule.event_end_date_time','<',$match_date);
			// })->orwhere(function ($query) use ($id,$match_date,$time,$date){
			// 	$query->where(['event_list.user_id'=>$id]);                  //event submited by user same date but end time passed
			// 	$query->where(['submit_by'=>2]);
			// 	$query->where('event_schedule.event_end_date_time','<',$match_date);
			// })->orWhere(function($query)use ($id,$match_date,$date){
			// 	$query->where(['submit_by'=>3,'status'=>2]);               //event submited by organizer
			// 	$query->where('event_schedule.event_end_date_time','<',$match_date);
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			// })->orWhere(function($query)use ($id,$match_date,$time,$date){
			// 	$query->where(['submit_by'=>3,'status'=>2]);
			// 	$query->where('event_schedule.event_end_date_time','<',$match_date);     //event submited by organizer same date but end time passed
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			// })->orWhere(function($query) use ($invitations,$match_date,$id,$date){
			// 	$query->whereIn('event_schedule.id',$invitations);
			// 	$query->where(['submit_by'=>2]);                         //event submited by other but invited 
			// 	$query->where('event_schedule.event_end_date_time','<',$match_date);
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			// })->orWhere(function($query) use ($invitations,$match_date,$id,$time,$date){
			// 	$query->whereIn('event_schedule.id',$invitations);                   
			// 	$query->where(['submit_by'=>2]);                             //event submited by other but invited same date but end time passed
			// 	$query->where('event_schedule.event_end_date_time','<',$match_date);
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			// })->orderBy('event_schedule.event_start_date_time','ASC')
		 //      ->pluck('event_schedule.id');  
	}


	public function past_events_2($match_date,$time,$date,$invitations){
    return	$get_event= EventList::where(['status' =>2])
			// ->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(function ($query) use ($match_date,$date){
				// $query->where(['event_list.user_id'=>$id]);                  //event submited by user
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orwhere(function ($query) use ($match_date,$time,$date){
				// $query->where(['event_list.user_id'=>$id]);                  //event submited by user same date but end time passed
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_end_date_time','<',$match_date);
			})->orWhere(function($query)use ($match_date,$date){
				$query->where(['submit_by'=>3,'status'=>2]);               //event submited by organizer
				$query->where('event_schedule.event_end_date_time','<',$match_date);
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orWhere(function($query)use ($match_date,$time,$date){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->where('event_schedule.event_end_date_time','<',$match_date);     //event submited by organizer same date but end time passed
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orWhere(function($query) use ($invitations,$match_date,$date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);                         //event submited by other but invited 
				$query->where('event_schedule.event_end_date_time','<',$match_date);
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orWhere(function($query) use ($invitations,$match_date,$time,$date){
				$query->whereIn('event_schedule.id',$invitations);                   
				$query->where(['submit_by'=>2]);                             //event submited by other but invited same date but end time passed
				$query->where('event_schedule.event_end_date_time','<',$match_date);
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->pluck('event_schedule.id');  
	}

    public function upcoming_events($id,$invitations,$date,$date_range,$match_date_range,$match_date){
    	return $get_event= EventList::where(['status' =>2])
				->whereNotIn('event_list.id',$this->get_event_block_list($id))
				->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
				->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id))
				->where(function ($query) use ($id,$date,$date_range,$match_date_range,$match_date){
					$query->where(['event_list.user_id'=>$id]);
					$query->where(['submit_by'=>2]);
					$query->where('event_schedule.event_start_date_time','>',$match_date);
					$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
				})->orWhere(function($query)use ($id,$date,$date_range,$match_date_range,$match_date){
					$query->where(['submit_by'=>3,'status'=>2]);
					$query->where('event_schedule.event_start_date_time','>',$match_date);
					$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
				})->orWhere(function($query) use ($invitations,$date,$date_range,$id,$match_date_range,$match_date){
					$query->whereIn('event_schedule.id',$invitations);
					$query->where(['submit_by'=>2]);
					$query->where('event_schedule.event_start_date_time','>',$match_date);
					$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);				
				})->orderBy('event_schedule.event_start_date_time','ASC')
				->pluck('event_schedule.id');
   //  return	$get_event= EventList::where(['status' =>2])
			// ->whereNotIn('event_list.id',$this->get_event_block_list($id))
			// ->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			// ->where(function ($query) use ($id,$date,$date_range,$match_date_range,$match_date){
			// 	$query->where(['event_list.user_id'=>$id]);
			// 	$query->where(['submit_by'=>2]);
			// 	$query->where('event_schedule.event_start_date_time','>',$match_date);
			// 	$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
			// })->orWhere(function($query)use ($id,$date,$date_range,$match_date_range,$match_date){
			// 	$query->where(['submit_by'=>3,'status'=>2]);
			// 	$query->where('event_schedule.event_start_date_time','>',$match_date);
			// 	$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			// })->orWhere(function($query) use ($invitations,$date,$date_range,$id,$match_date_range,$match_date){
			// 	$query->whereIn('event_schedule.id',$invitations);
			// 	$query->where(['submit_by'=>2]);
			// 	$query->where('event_schedule.event_start_date_time','>',$match_date);
			// 	$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
			// 	$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			// })->orderBy('event_schedule.event_start_date_time','ASC')
		 //      ->pluck('event_schedule.id');
    }

    public function upcoming_events_2($invitations,$date,$date_range,$match_date_range,$match_date){
    return	$get_event= EventList::where(['status' =>2])
			// ->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(function ($query) use ($date,$date_range,$match_date_range,$match_date){
				// $query->where(['event_list.user_id'=>$id]);
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
			})->orWhere(function($query)use ($date,$date_range,$match_date_range,$match_date){
				$query->where(['submit_by'=>3,'status'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orWhere(function($query) use ($invitations,$date,$date_range,$match_date_range,$match_date){
				$query->whereIn('event_schedule.id',$invitations);
				$query->where(['submit_by'=>2]);
				$query->where('event_schedule.event_start_date_time','>',$match_date);
				$query->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				// $query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->pluck('event_schedule.id');
    }

    public function search_by_name_past($search_by_name,$id,$match_date,$time,$date,$invitations) {
    	$date = strtotime($date);
    	$match_date = strtotime($match_date);
    	//$get_event= EventList::where(['status' =>2])
    			// ->where('event_name', 'LIKE', "%{$search_by_name}%") 
				// ->where('event_name', 'LIKE', '%'.$search_by_name.'%')
				
		$get_event = EventList::leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				 ->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
				 ->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id)) 
				  ->whereNotIn('event_list.id',$this->get_event_block_list($id))
				/*->when($search_by_name, function ($query) use ($search_by_name) {
                        return $query->where('event_name', 'like', '%' . $search_by_name . '%');
                    })*/
				->where(function($qu1) use ($search_by_name){
					$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
						->where(['status' =>2]);
				})
				->where(function($query) use ($id,$match_date,$invitations){
						$query->where(function($query1) use ($id,$match_date){
							$query1->where(['event_list.user_id'=>$id]);                  //event submited by user
							$query1->where(['submit_by'=>2]);
							$query1->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
						})
						->orWhere(function($query2) use ($id,$match_date) {
							$query2->where(['submit_by'=>3]);               //event submited by organizer
							$query2->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
						})
						->orWhere(function($query3) use ($id,$match_date,$invitations) {
							if($invitations && !empty($invitations) && count($invitations) > 0 && $invitations[0] != 0){
								$query3->whereIn('event_schedule.id',$invitations);
						}
					
							$query3->where(['submit_by'=>2]);                        //event submited by other but invited 
							$query3->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
								});
					})
				/*>where(function ($query) use ($id,$match_date,$date){
					$query->where(['event_list.user_id'=>$id]);                  //event submited by user
					$query->where(['submit_by'=>2]);
					$query->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
				})->orWhere(function($query)use ($id,$match_date,$date){
					$query->where(['submit_by'=>3]);               //event submited by organizer
					$query->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
				})->orWhere(function($query) use ($invitations,$match_date,$id,$date){
					if($invitations && !empty($invitations) && count($invitations) > 0 && $invitations[0] != 0){
						$query->whereIn('event_schedule.id',$invitations);
					}
					
					$query->where(['submit_by'=>2]);                         //event submited by other but invited 
					$query->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
				})*/
				->orderBy('event_schedule.event_start_date_time','ASC')
			      ->paginate(5);

			    foreach($get_event as $eachEvent){
				$eachEvent->postList->take(10);
				$eachEvent->post_list_count = $eachEvent->postList->count();
				
				$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
				$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
				  
				if(!empty($eachEvent->music_int_id)){
					$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
				}else{
					$eachEvent->music_interest_id=array();	
				}

				if(!empty($eachEvent->public_int_id)){
					$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
				}else{
					$eachEvent->public_interest_id=array();	
				}
				
				$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,/* 'request_status'=>1 */])->first();
				if(!empty($check_event)){
						$eachEvent->attend_status=1;
				}else{      
					if($id == $eachEvent->user_id){
						$eachEvent->attend_status=1;
					}else{
						$eachEvent->attend_status=2;
					}
				}
				
				$eachEvent->ticket_available_count = $this->get_ticketss($eachEvent->event_id,'ticket_count',$eachEvent->id);
				
				$eachEvent->ticket_price=$this->get_ticketss($eachEvent->event_id,'price',$eachEvent->id);
				$eachEvent->event_views=$this->event_views($id,$eachEvent->id);
				$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);

			}       
			if(count($get_event) > 0){
				$this->responseOk('Event List',$get_event);
			}else{
				$this->responseWithError('No more event found');
			}
    }

    public function search_by_name_upcoming($match_date_range,$date_range,$search_by_name,$id,$match_date,$time,$date,$invitations) {
    	// return $search_by_name;
    	$date = strtotime($date);
    	$match_date = strtotime($match_date);
    	//$get_event= EventList::where(['status' =>2])
    			// ->where('event_name', 'LIKE', "%{$search_by_name}%") 
				// ->where('event_name', 'LIKE', '%'.$search_by_name.'%')
				
		$get_event = EventList::leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				 ->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
				 ->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id)) 
				  ->whereNotIn('event_list.id',$this->get_event_block_list($id))
				/*->when($search_by_name, function ($query) use ($search_by_name) {
                        return $query->where('event_name', 'like', '%' . $search_by_name . '%');
                    })*/
				->where(function($qu1) use ($search_by_name){
					$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
						->where(['status' =>2]);
				})
				->where(function($query) use ($id,$match_date,$invitations,$match_date_range){
						$query->where(function($query1) use ($id,$match_date,$match_date_range){
							$query1->where(['event_list.user_id'=>$id]);                  //event submited by user
							$query1->where(['submit_by'=>2]);
							$query1->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) > unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) between  unix_timestamp("'.$match_date.'") and unix_timestamp("'.$match_date_range.'"))');
						})
						->orWhere(function($query2) use ($id,$match_date,$match_date_range) {
							$query2->where(['submit_by'=>3]);               //event submited by organizer
							$query2->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) > unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) between  unix_timestamp("'.$match_date.'") and unix_timestamp("'.$match_date_range.'"))');
						})
						->orWhere(function($query3) use ($id,$match_date,$invitations,$match_date_range) {
							if($invitations && !empty($invitations) && count($invitations) > 0 && $invitations[0] != 0){
								$query3->whereIn('event_schedule.id',$invitations);
							}
							$query3->where(['submit_by'=>2]);                        //event submited by other but invited 
							$query3->whereRaw('(unix_timestamp(event_schedule.event_start_date_time) > unix_timestamp("'.$match_date.'") and unix_timestamp(event_schedule.event_start_date_time) between  unix_timestamp("'.$match_date.'") and unix_timestamp("'.$match_date_range.'"))');
						});
					})->orderBy('event_schedule.event_start_date_time','ASC')
			      ->paginate(2);

			    foreach($get_event as $eachEvent){
				$eachEvent->postList->take(10);
				$eachEvent->post_list_count = $eachEvent->postList->count();
				
				$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
				$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
				  
				if(!empty($eachEvent->music_int_id)){
					$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
				}else{
					$eachEvent->music_interest_id=array();	
				}

				if(!empty($eachEvent->public_int_id)){
					$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
				}else{
					$eachEvent->public_interest_id=array();	
				}
				
				$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,/* 'request_status'=>1 */])->first();
				if(!empty($check_event)){
						$eachEvent->attend_status=1;
				}else{      
					if($id == $eachEvent->user_id){
						$eachEvent->attend_status=1;
					}else{
						$eachEvent->attend_status=2;
					}
				}
				
				$eachEvent->ticket_available_count = $this->get_ticketss($eachEvent->event_id,'ticket_count',$eachEvent->id);
				
				$eachEvent->ticket_price=$this->get_ticketss($eachEvent->event_id,'price',$eachEvent->id);
				$eachEvent->event_views=$this->event_views($id,$eachEvent->id);
				$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);

			}       
			if(count($get_event) > 0){
				$this->responseOk('Event List',$get_event);
			}else{
				$this->responseWithError('No more event found');
			}
    }


    public function search_by_name_current($search_by_name,$id,$match_date,$time,$date,$invitations) {
    	$date = strtotime($date);
    	
		$get_event = EventList::leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				 ->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
				 ->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id)) 
				  ->whereNotIn('event_list.id',$this->get_event_block_list($id))
					->where(function($qu1) use ($search_by_name){
						$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
							->where(['status' =>2]);
					})
					->where(function($query) use ($id,$match_date,$invitations){
						$query->where(function($query1) use ($id,$match_date){
							// $query1->where(['event_list.user_id'=>$id]);                  //event submited by user
							$query1->where(['submit_by'=>2]);
							$query1->where('event_schedule.event_start_date_time','<',$match_date);
							$query1->where('event_schedule.event_end_date_time','>=',$match_date);
						})
						->orWhere(function($query2) use ($id,$match_date) {
							$query2->where(['submit_by'=>3,'status'=>2]);               //event submited by organizer
							$query2->where('event_schedule.event_start_date_time','<',$match_date);
							$query2->where('event_schedule.event_end_date_time','>=',$match_date);
						})
						->orWhere(function($query3) use ($id,$match_date,$invitations) {
							if($invitations && !empty($invitations) && count($invitations) > 0 && $invitations[0] != 0){
								$query3->whereIn('event_schedule.id',$invitations);
							}
							$query3->where(['submit_by'=>2]);                        //event submited by other but invited 
							$query3->where('event_schedule.event_start_date_time','<',$match_date);
							$query3->where('event_schedule.event_end_date_time','>=',$match_date);
								});
					})->orderBy('event_schedule.event_start_date_time','ASC')
			      	  ->paginate(1); 

			    foreach($get_event as $eachEvent){
				$eachEvent->postList->take(10);
				$eachEvent->post_list_count = $eachEvent->postList->count();
				
				$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
				$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
				  
				if(!empty($eachEvent->music_int_id)){
					$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
				}else{
					$eachEvent->music_interest_id=array();	
				}

				if(!empty($eachEvent->public_int_id)){
					$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
				}else{
					$eachEvent->public_interest_id=array();	
				}
				
				$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,/* 'request_status'=>1 */])->first();
				if(!empty($check_event)){
						$eachEvent->attend_status=1;
				}else{      
					if($id == $eachEvent->user_id){
						$eachEvent->attend_status=1;
					}else{
						$eachEvent->attend_status=2;
					}
				}
				
				$eachEvent->ticket_available_count = $this->get_ticketss($eachEvent->event_id,'ticket_count',$eachEvent->id);
				
				$eachEvent->ticket_price=$this->get_ticketss($eachEvent->event_id,'price',$eachEvent->id);
				$eachEvent->event_views=$this->event_views($id,$eachEvent->id);
				$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);

			}       
			if(count($get_event) > 0){
				$this->responseOk('Event List',$get_event);
			}else{
				$this->responseWithError('No more event found');
			}
    }


    public function search_by_name_1($search_by_name,$match_date,$time,$date,$invitations) {
    	$date = strtotime($date);
    	// $match_date = strtotime($match_date);
    	//$get_event= EventList::where(['status' =>2])
    			// ->where('event_name', 'LIKE', "%{$search_by_name}%") 
				// ->where('event_name', 'LIKE', '%'.$search_by_name.'%')
				
		$get_event = EventList::leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				 // ->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
				 // ->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id)) 
				 //  ->whereNotIn('event_list.id',$this->get_event_block_list($id))
				/*->when($search_by_name, function ($query) use ($search_by_name) {
                        return $query->where('event_name', 'like', '%' . $search_by_name . '%');
                    })*/
				->where(function($qu1) use ($search_by_name){
					$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
						->where(['status' =>2]);
				})
				->where(function($query) use ($match_date,$invitations){
						$query->where(function($query1) use ($match_date){
							// $query1->where(['event_list.user_id'=>$id]);                  //event submited by user
							$query1->where(['submit_by'=>2]);
							$query1->where('event_schedule.event_start_date_time','<',$match_date);
							$query1->where('event_schedule.event_end_date_time','>=',$match_date);
						})
						->orWhere(function($query2) use ($match_date) {
							$query2->where(['submit_by'=>3]);               //event submited by organizer
							$query2->where('event_schedule.event_start_date_time','<',$match_date);
							$query2->where('event_schedule.event_end_date_time','>=',$match_date);
						})
						->orWhere(function($query3) use ($match_date,$invitations) {
							if($invitations && !empty($invitations) && count($invitations) > 0 && $invitations[0] != 0){
								$query3->whereIn('event_schedule.id',$invitations);
							}
					
							$query3->where(['submit_by'=>2]);                        //event submited by other but invited 
							$query3->where('event_schedule.event_start_date_time','<',$match_date);
							$query3->where('event_schedule.event_end_date_time','>=',$match_date);
								});
					})->orderBy('event_schedule.event_start_date_time','ASC')
			      	  ->paginate(1); 

			    foreach($get_event as $eachEvent){
				$eachEvent->postList->take(10);
				$eachEvent->post_list_count = $eachEvent->postList->count();
				
				$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
				$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
				  
				if(!empty($eachEvent->music_int_id)){
					$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
				}else{
					$eachEvent->music_interest_id=array();	
				}

				if(!empty($eachEvent->public_int_id)){
					$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
				}else{
					$eachEvent->public_interest_id=array();	
				}
				
				// $check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'receiver_id'=>$id, 'request_status'=>1 ])->first();
				// if(!empty($check_event)){
				// 		$eachEvent->attend_status=1;
				// }else{      
				// 		$eachEvent->attend_status=2;
				// }
				
				$eachEvent->ticket_available_count = $this->get_ticketss($eachEvent->event_id,'ticket_count',$eachEvent->id);
				
				$eachEvent->ticket_price=$this->get_ticketss($eachEvent->event_id,'price',$eachEvent->id);
				// $eachEvent->event_views=$this->event_views($eachEvent->id);
				// $eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);

			}       
			if(count($get_event) > 0){
				$this->responseOk('Event List',$get_event);
			}else{
				$this->responseWithError('No more event found');
			}
    }

    public function search_by_name_3($match_date_range,$date_range,$search_by_name,$match_date,$time,$date,$invitations) {
    	// return $search_by_name;
    	$date = strtotime($date);
    	$match_date = strtotime($match_date);
    	//$get_event= EventList::where(['status' =>2])
    			// ->where('event_name', 'LIKE', "%{$search_by_name}%") 
				// ->where('event_name', 'LIKE', '%'.$search_by_name.'%')
				
		$get_event = EventList::leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				 // ->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
				 // ->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id)) 
				 //  ->whereNotIn('event_list.id',$this->get_event_block_list($id))
				/*->when($search_by_name, function ($query) use ($search_by_name) {
                        return $query->where('event_name', 'like', '%' . $search_by_name . '%');
                    })*/
				->where(function($qu1) use ($search_by_name){
					$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
						->where(['status' =>2]);
				})
				->where(function($query) use ($match_date,$invitations,$match_date_range){
						$query->where(function($query1) use ($match_date,$match_date_range){
							// $query1->where(['event_list.user_id'=>$id]);                  //event submited by user
							$query1->where(['submit_by'=>2]);
							$query1->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'>',$match_date);
							$query1->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
						})
						->orWhere(function($query2) use ($match_date,$match_date_range) {
							$query2->where(['submit_by'=>3]);               //event submited by organizer
							$query2->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'>',$match_date);
							$query2->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
						})
						->orWhere(function($query3) use ($match_date,$invitations,$match_date_range) {
							if($invitations && !empty($invitations) && count($invitations) > 0 && $invitations[0] != 0){
								$query3->whereIn('event_schedule.id',$invitations);
							}
					
							$query3->where(['submit_by'=>2]);                        //event submited by other but invited 
							$query3->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'>',$match_date);
							$query3->whereBetween('event_schedule.event_start_date_time',[$match_date,$match_date_range]);
								});
					})->orderBy('event_schedule.event_start_date_time','ASC')
			      ->paginate(5);

			    foreach($get_event as $eachEvent){
				$eachEvent->postList->take(10);
				$eachEvent->post_list_count = $eachEvent->postList->count();
				
				$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
				$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
				  
				if(!empty($eachEvent->music_int_id)){
					$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
				}else{
					$eachEvent->music_interest_id=array();	
				}

				if(!empty($eachEvent->public_int_id)){
					$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
				}else{
					$eachEvent->public_interest_id=array();	
				}
				
				$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id/* 'request_status'=>1 */])->first();
				if(!empty($check_event)){
						$eachEvent->attend_status=1;
				}else{      
						$eachEvent->attend_status=2;
				}
				
				$eachEvent->ticket_available_count = $this->get_ticketss($eachEvent->event_id,'ticket_count',$eachEvent->id);
				
				$eachEvent->ticket_price=$this->get_ticketss($eachEvent->event_id,'price',$eachEvent->id);
				// $eachEvent->event_views=$this->event_views($id,$eachEvent->id);
				// $eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);

			}       
			if(count($get_event) > 0){
				$this->responseOk('Event List',$get_event);
			}else{
				$this->responseWithError('No more event found');
			}
    }

    public function search_by_name_2($search_by_name,$match_date,$time,$date,$invitations) {
    	$date = strtotime($date);
    	$match_date = strtotime($match_date);
    	//$get_event= EventList::where(['status' =>2])
    			// ->where('event_name', 'LIKE', "%{$search_by_name}%") 
				// ->where('event_name', 'LIKE', '%'.$search_by_name.'%')
				
		$get_event = EventList::leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				 // ->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id))
				 // ->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id)) 
				 //  ->whereNotIn('event_list.id',$this->get_event_block_list($id))
				/*->when($search_by_name, function ($query) use ($search_by_name) {
                        return $query->where('event_name', 'like', '%' . $search_by_name . '%');
                    })*/
				->where(function($qu1) use ($search_by_name){
					$qu1->where('event_name', 'like', '%' . $search_by_name . '%')
						->where(['status' =>2]);
				})
				->where(function($query) use ($match_date,$invitations){
						$query->where(function($query1) use ($match_date){
							// $query1->where(['event_list.user_id'=>$id]);                  //event submited by user
							$query1->where(['submit_by'=>2]);
							$query1->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
						})
						->orWhere(function($query2) use ($match_date) {
							$query2->where(['submit_by'=>3]);               //event submited by organizer
							$query2->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
						})
						->orWhere(function($query3) use ($match_date,$invitations) {
							if($invitations && !empty($invitations) && count($invitations) > 0 && $invitations[0] != 0){
								$query3->whereIn('event_schedule.id',$invitations);
						}
					
							$query3->where(['submit_by'=>2]);                        //event submited by other but invited 
							$query3->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
								});
					})
				/*>where(function ($query) use ($id,$match_date,$date){
					$query->where(['event_list.user_id'=>$id]);                  //event submited by user
					$query->where(['submit_by'=>2]);
					$query->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
				})->orWhere(function($query)use ($id,$match_date,$date){
					$query->where(['submit_by'=>3]);               //event submited by organizer
					$query->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
				})->orWhere(function($query) use ($invitations,$match_date,$id,$date){
					if($invitations && !empty($invitations) && count($invitations) > 0 && $invitations[0] != 0){
						$query->whereIn('event_schedule.id',$invitations);
					}
					
					$query->where(['submit_by'=>2]);                         //event submited by other but invited 
					$query->where(DB::raw('UNIX_TIMESTAMP(event_schedule.event_end_date_time)'),'<',$match_date);
				})*/
				->orderBy('event_schedule.event_start_date_time','ASC')
			      ->paginate(5);

			    foreach($get_event as $eachEvent){
				$eachEvent->postList->take(10);
				$eachEvent->post_list_count = $eachEvent->postList->count();
				
				$eachEvent->guest_count=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'request_status'=>1])->count();
				$event_music_interest_list=DB::table('event_music_interest_list')->where(['event_id'=>$eachEvent->event_id])->first();
				  
				if(!empty($eachEvent->music_int_id)){
					$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
				}else{
					$eachEvent->music_interest_id=array();	
				}

				if(!empty($eachEvent->public_int_id)){
					$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
				}else{
					$eachEvent->public_interest_id=array();	
				}
				
				$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,/* 'request_status'=>1 */])->first();
				if(!empty($check_event)){
						$eachEvent->attend_status=1;
				}else{      
						$eachEvent->attend_status=2;
				}
				
				$eachEvent->ticket_available_count = $this->get_ticketss($eachEvent->event_id,'ticket_count',$eachEvent->id);
				
				$eachEvent->ticket_price=$this->get_ticketss($eachEvent->event_id,'price',$eachEvent->id);
				// $eachEvent->event_views=$this->event_views($id,$eachEvent->id);
				// $eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);

			}       
			if(count($get_event) > 0){
				$this->responseOk('Event List',$get_event);
			}else{
				$this->responseWithError('No more event found');
			}
    }

    public function favourite_events($id,$favourites){
    return $favourites= FavouriteEvent::where(['user_id'=>$id])->select('sub_event_id')
						 ->distinct('sub_event_id')
						 ->pluck('sub_event_id');	
			$get_event= EventList::where(['status' =>2])
			->whereNotIn('event_list.id',$this->get_event_block_list($id))
			->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
			->where(function($query) use ($favourites,$id){
				$query->whereIn('event_schedule.id',$favourites);
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_list($id));
				$query->whereNotIn('event_schedule.user_id',$this->get_user_block_by_admin($id));
			})->orderBy('event_schedule.event_start_date_time','ASC')
		      ->pluck('event_schedule.id');
    }


    public function event_filter($id,$public_interest,$music_interest,$lat,$long,$miles,$event_date,$event_ids){
		
		$now = Carbon::now();
		$subDay=$now->subDay('1');
	    $date=$now->toDateTimeString();
		$end_dt=date('Y-m-d H:i:s');
	    $date_range=date('Y-m-d H:i:s', strtotime("+30 day", strtotime($end_dt)));
		$query=DB::table('event_list')
				->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				->where(['status'=>2])
				// ->whereBetween('event_schedule.event_start_date_time',[$date,$date_range])
				->whereIn('event_schedule.id',$event_ids)
				->whereNotIn('event_list.id',$this->get_event_block_list($id));
		if(!empty($lat) && !empty($long)){
			$query_distance = '(3959 * acos( cos( radians("'.$lat.'") ) * cos( radians( event_lat ) ) * cos( radians( event_long ) - radians("'.$long.'") ) + sin( radians("'.$lat.'") ) * sin( radians( event_lat ) ) ) )';
			$query->select(['event_list.*','event_schedule.*',DB::raw(($query_distance) .' AS distance')]);
			$query->where(DB::raw($query_distance),'<=',$miles)->orderBy('distance','ASC');
		}
		if(!empty($public_interest)){
			$public_interest_array= explode(',',$public_interest);
			$public_interest_event_ids=DB::table('event_public_interest_list')->whereIn('public_interest_id',$public_interest_array)->pluck('event_id');
    	   $query->whereIn('event_list.id',$public_interest_event_ids);
		}
		if(!empty($music_interest)){
			$music_interest_array= explode(',',$music_interest);
			$music_interest_ids=DB::table('event_music_interest_list')->whereIn('music_interest_id',$music_interest_array)->pluck('event_id');
		    $query->whereIn('event_list.id',$music_interest_ids);
		}
		if(!empty($event_date)){
		   $query->where('event_schedule.event_date','=',date('Y-m-d',strtotime($event_date)));
		}
		
		$events= $query->paginate(10);
		if(count($events)>0){
			foreach($events as $eachEvent){ 
			    if(!empty($eachEvent->music_int_id)){
					$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
				}else{
					$eachEvent->music_interest_id=array();	
				}

				if(!empty($eachEvent->public_int_id)){
					$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
				}else{
					$eachEvent->public_interest_id=array();	
				}

				$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,/* 'request_status'=>1 */])->first();
				if(!empty($check_event)){
						$eachEvent->attend_status=1;
				}else{      
					if($id == $eachEvent->user_id){
						$eachEvent->attend_status=1;
					}else{
						$eachEvent->attend_status=2;
					}
				}
				$eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
				$eachEvent->ticket_price=$this->get_tickets($eachEvent->event_id,'price');
				$eachEvent->event_views=$this->event_views($id,$eachEvent->id);
				$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);
				//$data[]=$eachEvent;
			}
			return $this->responseOk('Event List',$events);
		}else{
		  return $this->responseWithError('No More event found');
	
		}  		
		
	}

	public function event_filter_2($public_interest,$music_interest,$lat,$long,$miles,$event_date,$event_ids){
		
		$now = Carbon::now();
		$subDay=$now->subDay('1');
	    $date=$now->toDateTimeString();
		$end_dt=date('Y-m-d H:i:s');
	    $date_range=date('Y-m-d H:i:s', strtotime("+30 day", strtotime($end_dt)));
		$query=DB::table('event_list')
				->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				->where(['status'=>2])
				// ->whereBetween('event_schedule.event_start_date_time',[$date,$date_range])
				->whereIn('event_schedule.id',$event_ids);
				// ->whereNotIn('event_list.id',$this->get_event_block_list($id));
		if(!empty($lat) && !empty($long)){
			$query_distance = '(3959 * acos( cos( radians("'.$lat.'") ) * cos( radians( event_lat ) ) * cos( radians( event_long ) - radians("'.$long.'") ) + sin( radians("'.$lat.'") ) * sin( radians( event_lat ) ) ) )';
			$query->select(['event_list.*','event_schedule.*',DB::raw(($query_distance) .' AS distance')]);
			$query->where(DB::raw($query_distance),'<=',$miles)->orderBy('distance','ASC');
		}
		if(!empty($public_interest)){
			$public_interest_array= explode(',',$public_interest);
			$public_interest_event_ids=DB::table('event_public_interest_list')->whereIn('public_interest_id',$public_interest_array)->pluck('event_id');
    	   $query->whereIn('event_list.id',$public_interest_event_ids);
		}
		if(!empty($music_interest)){
			$music_interest_array= explode(',',$music_interest);
			$music_interest_ids=DB::table('event_music_interest_list')->whereIn('music_interest_id',$music_interest_array)->pluck('event_id');
		    $query->whereIn('event_list.id',$music_interest_ids);
		}
		if(!empty($event_date)){
		   $query->where('event_schedule.event_date','=',date('Y-m-d',strtotime($event_date)));
		}
		
		$events= $query->paginate(10);
		if(count($events)>0){
			foreach($events as $eachEvent){ 
			    if(!empty($eachEvent->music_int_id)){
					$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
				}else{
					$eachEvent->music_interest_id=array();	
				}

				if(!empty($eachEvent->public_int_id)){
					$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
				}else{
					$eachEvent->public_interest_id=array();	
				}

				// $check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,'receiver_id'=>$id,/* 'request_status'=>1 */])->first();
				// if(!empty($check_event)){
				// 		$eachEvent->attend_status=1;
				// }else{      
				// 	if($id == $eachEvent->user_id){
				// 		$eachEvent->attend_status=1;
				// 	}else{
				// 		$eachEvent->attend_status=2;
				// 	}
				// }
				$eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
				$eachEvent->ticket_price=$this->get_tickets($eachEvent->event_id,'price');
				// $eachEvent->event_views=$this->event_views($id,$eachEvent->id);
				// $eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);
				//$data[]=$eachEvent;
			}
			return	$this->responseOk('Event List',$events);
		}else{
		  return	$this->responseWithError('No More event found');
	
		}  		
		
	}


	public function search_by_name($id,$event_ids,$search_by_name){
		// return $id;
		$now = Carbon::now();
		$subDay=$now->subDay('1');
	    $date=$now->toDateTimeString();
		$end_dt=date('Y-m-d H:i:s');
	    $date_range=date('Y-m-d H:i:s', strtotime("+30 day", strtotime($end_dt)));
		$query=DB::table('event_list')
				->leftJoin('event_schedule', 'event_schedule.event_id', '=', 'event_list.id')
				->where(['submit_by'=>3,'status'=>2])
				->where('event_name','like', "%$search_by_name%")
				->whereBetween('event_schedule.event_start_date_time',[$date,$date_range])
				->whereIn('event_schedule.id',$event_ids)
				->whereNotIn('event_list.id',$this->get_event_block_list($id));
		
		
		
		$events= $query->paginate(10);
		if(count($events)>0){
			foreach($events as $eachEvent){ 
			    if(!empty($eachEvent->music_int_id)){
					$eachEvent->music_interest_id=DB::table('music_interest')->whereIn('id',explode(',', $eachEvent->music_int_id))->get();
				}else{
					$eachEvent->music_interest_id=array();	
				}

				if(!empty($eachEvent->public_int_id)){
					$eachEvent->public_interest_id=DB::table('public_interest')->whereIn('id',explode(',', $eachEvent->public_int_id))->get();
				}else{
					$eachEvent->public_interest_id=array();	
				}

				$check_event=DB::table('invitations')->where(['event_id'=>$eachEvent->event_id,'sub_event_id'=>$eachEvent->id,/* 'request_status'=>1 */])->first();
				if(!empty($check_event)){
						$eachEvent->attend_status=1;
				}else{      
						$eachEvent->attend_status=2;
				}
				$eachEvent->ticket_available_count=$this->get_tickets($eachEvent->event_id,'ticket_count');
				$eachEvent->ticket_price=$this->get_tickets($eachEvent->event_id,'price');
				$eachEvent->event_views=$this->event_views($id,$eachEvent->id);
				$eachEvent->favourite_status=$this->favourite_status($id,$eachEvent->id);
				//$data[]=$eachEvent;
			}
			return	$this->responseOk('Event List',$events);
		}else{
		  return	$this->responseWithError('No More event found');
	
		}  		
		
	}


	public function create_event_schedule($event_start_date_time,$event_end_date_time,$repeat_interval,$insert_id,$id){
		// return $event_end_date_time;
		$event_date2=date('D j M',strtotime($event_start_date_time));
		$get_start_time = date('H:i:s',(strtotime($event_start_date_time)));
		$get_end_time = date('H:i:s',(strtotime($event_end_date_time)));
		$event_time=date('H:i:s',(strtotime($event_start_date_time)));	
		$end_time=date('H:i:s',(strtotime($event_end_date_time)));
			

		$event_date = date('Y-m-d',(strtotime($event_start_date_time)));
		$event_end_date = date('Y-m-d',(strtotime($event_end_date_time)));
		// $days = date('Y-m-d',(strtotime($event_start_date_time))) - date('Y-m-d',(strtotime($event_end_date_time)));
		$diff = abs(strtotime($event_start_date_time) - strtotime($event_end_date_time)); 
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24));
		// $days = $day + 1;


		if($repeat_interval=='one_day'){
			$event_start_date_time = date('Y-m-d H:i:s', strtotime($event_start_date_time));
			$event_end_date_time = date('Y-m-d H:i:s', strtotime($event_end_date_time));
			if(strtotime($event_start_date_time) >= strtotime($event_end_date_time)){
				$event_end_date_time = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($event_end_date_time)));
			}
			$temp_data = ['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$event_time,'end_time'=>$end_time,'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time];
			$last_id=DB::table('event_schedule')->insertGetId($temp_data);
		}elseif($repeat_interval=='monthly'){
			
			for($i=1;$i<=12;$i++){
				$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
				$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
				if(strtotime($event_start_date_time) >= strtotime($event_end_date_time)){
						$event_end_date_time = date('Y-m-d H:i:s', strtotime("$days days", strtotime($event_end_date_time)));
				}
				$temp_data =  ['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$event_time,'end_time'=>$end_time,'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time];
				$last_id=DB::table('event_schedule')->insertGetId($temp_data);
				$get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
				$event_date=date('Y-m-d', strtotime("+1 months", strtotime($get_date->event_date)));
				$event_date2=date('D j M',strtotime("+1 months", strtotime($get_date->event_date))); 
			}  
		}elseif($repeat_interval=='weekly'){
			// return "2";
			for($i=1;$i<=48;$i++){ 
				$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
				$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
				if(strtotime($event_start_date_time) >= strtotime($event_end_date_time)){
					$event_end_date_time = date('Y-m-d H:i:s', strtotime("$days days", strtotime($event_end_date_time)));
				}
				// return "2";
				  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$event_time,'end_time'=>$end_time,'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time]);
				  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
				  $event_date=date('Y-m-d', strtotime("+1 week", strtotime($get_date->event_date)));
				  $event_date2=date('D j M',strtotime("+1 week", strtotime($get_date->event_date))); 
			} 
		}elseif($repeat_interval=='2_weekly'){
			for($i=1;$i<=24;$i++){
				$event_start_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_start_time"));
				$event_end_date_time = date('Y-m-d H:i:s', strtotime("$event_date $get_end_time"));
				if(strtotime($event_start_date_time) >= strtotime($event_end_date_time)){
					$event_end_date_time = date('Y-m-d H:i:s', strtotime("$days days", strtotime($event_end_date_time)));
				}					
			  $last_id=DB::table('event_schedule')->insertGetId(['event_id'=>$insert_id,'user_id'=>$id,'event_date'=>$event_date,'event_date2'=>$event_date2,'event_time'=>$event_time,'end_time'=>$end_time,'created_at'=>Date('Y-m-d H:i:s'),'event_start_date_time' => $event_start_date_time,'event_end_date_time' => $event_end_date_time]);
			  $get_date=DB::table('event_schedule')->where(['id'=>$last_id])->first();
			  $event_date=date('Y-m-d', strtotime("+2 week", strtotime($get_date->event_date)));
			  $event_date2=date('D j M',strtotime("+2 week", strtotime($get_date->event_date)));
			} 
		}
		return 1;
	}


	function encrypt_fucntion($string,$action){
		//you may change these values to your own
		$secret_key = 'sdsd_sd_sds';
		$secret_iv = 'asas_gg_dfd';
	 	$output = false;
		$encrypt_method = "AES-256-CBC";
		$key = hash('sha256',$secret_key);
		$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
	 	if( $action == 'e' ) {
			$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
		}
		else if( $action == 'd' ){
			$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
		}
 		return $output;
	} 

	public function notification_count($user_id){
		$notification_count=DB::table('notification_list')->where(['user_id'=>$user_id,'status'=>2])->count();
		return $notification_count;
	}

   
}

