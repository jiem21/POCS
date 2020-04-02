<?php 

use App\Logs;

if (! function_exists('activity_log')) {
    function activity_log($activity,$user_id,$date_transcated) {
        $logs = new Logs;
        $logs->activity_name = $activity;
        $logs->user_ip = $user_id;
        $logs->date_created = $date_transcated;
        $logs->save();
    }
}

if (! function_exists('date_time_con')) {
  function date_time_con($date) {
   $new_date = date("F d,Y h:i A",strtotime($date));
   return $new_date;
 }
}


if (! function_exists('time_con')) {
  function time_con($date) {
   $new_date = date("m-d-Y h:i A",strtotime($date));
   return $new_date;
 }
}
if (! function_exists('date_con')) {
  function date_con($date) {
   $new_date = date("F d, Y",strtotime($date));
   return $new_date;
 }
}

if (! function_exists('aoi_status')) {
  function aoi_status($id) {
   switch ($id) {
   	case '1':
   		return 'Ordering';
   		break;
   	case '2':
   		return 'Processing Request';
   		break;
   	case '3':
   		return 'Ready For Pick Up';
   		break;
    case '4':
      return 'On Loading';
      break;
   	
   	default:
   		return 'Invalid';
   		break;
   }
 }
}


if (! function_exists('qet_status')) {
  function qet_status($id) {
   switch ($id) {
   	case '1':
   		return 'Requesting';
   		break;
   	case '2':
   		return 'Processing Request';
   		break;
   	case '3':
   		return 'Delivery';
   		break;
    case '4':
      return 'AOI is Working on the Request';
      break;
   	
   	default:
   		return 'Invalid';
   		break;
   }
 }
}

if (! function_exists('machine_type')) {
  function machine_type($id) {
   switch ($id) {
   	case '1':
   		return 'HOST';
   		break;
   	case '2':
   		return 'QET Machine';
   		break;
   	
   	default:
   		return 'Invalid';
   		break;
   }
 }
}

if (! function_exists('stats')) {
  function stats($id) {
   switch ($id) {
   	case '1':
   		return 'Active';
   		break;
   	case '2':
   		return 'Deactivated';
   		break;
   	
   	default:
   		return 'Invalid';
   		break;
   }
 }
}


?>