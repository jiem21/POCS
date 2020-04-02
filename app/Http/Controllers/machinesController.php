<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Machines;
use App\Logs;

use DB;
use Carbon;
use Validator;

class machinesController extends Controller
{
    public $output = "";
    function __construct()
    {
        $this->machine = new Machines;
        $this->logs = new Logs;
    }
// 403
    public function forbidden()
    {
        return view('403');
    }
// Homepage
    public function index()
    {
        $ip = $this->getUserIpAddr();
        $check_machine = $this->machine->where('ip_address','=',$ip)->where('active_status','=',1)->where('machine_type','=',2)->count();
        $check_machine2 = $this->machine->where('ip_address','=',$ip)->where('active_status','=',1)->where('machine_type','=',1)->count();
        if ($check_machine == 1 AND $check_machine2 == 0) {
            $mech = $this->machine->where('ip_address','=',$ip)->where('active_status','=',1)->first();
            $data['id'] = $mech->id;
            $data['name'] = $mech->machine_name;
            $data['trigger'] = $mech->trigger_time;
            $data['status'] = $mech->status;
            $data['setup'] = $mech->machine_setup;
            return view('qet',$data);
        }
        elseif(($check_machine == 0 AND $check_machine2 == 1) OR $ip == "::1"){
            $data['ip'] = $ip;
            $data['machines'] = $this->machine->where('machine_type','=',2)->where('third_order','=',NULL)->get();
            return view('welcome',$data);
        }
        else{
            return view('403');
        }
    }
// List of all Machines
    // View
    public function list_machines()
    {
        $ip = $this->getUserIpAddr();
        $check_machine2 = $this->machine->where('ip_address','=',$ip)->where('active_status','=',1)->where('machine_type','=',1)->count();
        if ($check_machine2 == 1 OR $ip =="::1") {
            $data['ip'] = $ip;
            $data["machines"] = $this->machine->orderBy('machine_name','ASC')->get();
            return view('home',$data);
        }
        else{
            return view('403');
        }   
    }
    // Add
    public function save_machine(Request $req)
    {
        $ip = $this->getUserIpAddr();
        $rules = [
            'ip_address'=>'required',
            'machine_name'=>'required',
            'mech_type'=>'required'
        ];
        $validator = Validator::make($req->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
        }
        else{
            $check_ip = $this->machine->where('ip_address','=',$req->get('ip_address'))->count();
            $check_name = $this->machine->where('machine_name','=',$req->get('machine_name'))->count();
            if ($check_ip > 0 AND $check_name > 0) {
                return response()->json(['error' => true,'message'=>['IP Address and Machine name are already used']]);
            }
            elseif($check_ip > 0 AND $check_name == 0){
                return response()->json(['error' => true,'message'=>['IP Address is already used']]);
            }
            elseif($check_ip == 0 AND $check_name > 0){
                return response()->json(['error' => true,'message'=>['Machine name is already used']]);
            }
            else{
                $this->machine->insert([
                    'ip_address' => $req->get('ip_address'),
                    'machine_name' => $req->get('machine_name'),
                    'trigger_time' => Carbon\Carbon::now(),
                    'status' => 4,
                    'machine_type' => $req->get('mech_type'),
                    'active_status' => 1,
                    'date_created' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                    'created_by' => $ip
                ]);
                activity_log('Added New Machine PC IP Address - '.$req->get('ip_address'),$ip,Carbon\Carbon::now());
                return response()->json(['error' => false,'message'=>['Machine is saved']]);
            }
        }
    }
    // update
    public function update_machine(Request $req)
    {
        $ip = $this->getUserIpAddr();
        $rules = [
            'ip_address'=>'required',
            'machine_name'=>'required',
            'mech_type'=>'required',
            'mech_status'=>'required'
        ];
        $validator = Validator::make($req->all(),$rules);
        if ($validator->fails()) {
           return response()->json(['error' => true,'message'=>['Please Fill up all the fields']]);
        }
        else{
            $check_ip = $this->machine->where('ip_address','=',$req->get('ip_address'))->where('id','=',$req->get('mech_id'))->count();
            $check_name = $this->machine->where('machine_name','=',$req->get('machine_name'))->where('id','=',$req->get('mech_id'))->count();
            if ($check_ip == 1 AND $check_name == 1) {
                $this->machine->where('id','=',$req->get('mech_id'))->update([
                    'machine_type' => $req->get('mech_type'),
                    'active_status' => $req->get('mech_status'),
                    'updated_at' => Carbon\Carbon::now(),
                    'created_by' => $ip
                ]);
                activity_log('Update Machine status IP Address('.$req->get('ip_address').')',$ip,Carbon\Carbon::now());
                return response()->json(['error' => false,'message'=>['Successfully Updated']]);
            }
            elseif($check_ip == 1 AND $check_name == 0){
                $check_name2 = $this->machine->where('machine_name','=',$req->get('machine_name'))->count();
                if ($check_name2 == 1) {
                    return response()->json(['error' => true,'message'=>['Machine name is already used']]);
                }
                else{
                    $this->machine->where('id','=',$req->get('mech_id'))->update([
                    'ip_address' => $req->get('ip_address'),
                    'machine_name' => $req->get('machine_name'),
                    'machine_type' => $req->get('mech_type'),
                    'active_status' => $req->get('mech_status'),
                    'updated_at' => Carbon\Carbon::now(),
                    'created_by' => $ip
                ]);
                return response()->json(['error' => false,'message'=>['Successfully Updated']]);
                }
            }
            elseif($check_ip == 0 AND $check_name == 1){
                $check_ip2 = $this->machine->where('ip_address','=',$req->get('ip_address'))->count();
                if ($check_ip2 == 1) {
                    return response()->json(['error' => true,'message'=>['IP Address is already used']]);
                }
                else{
                    $this->machine->where('id','=',$req->get('mech_id'))->update([
                    'ip_address' => $req->get('ip_address'),
                    'machine_name' => $req->get('machine_name'),
                    'machine_type' => $req->get('mech_type'),
                    'active_status' => $req->get('mech_status'),
                    'updated_at' => Carbon\Carbon::now(),
                    'created_by' => $ip
                ]);
                return response()->json(['error' => false,'message'=>['Successfully Updated']]);
                }
            }
            else{
                $this->machine->where('id','=',$req->get('mech_id'))->update([
                    'ip_address' => $req->get('ip_address'),
                    'machine_name' => $req->get('machine_name'),
                    'machine_type' => $req->get('mech_type'),
                    'active_status' => $req->get('mech_status'),
                    'updated_at' => Carbon\Carbon::now(),
                    'created_by' => $ip
                ]);
                return response()->json(['error' => false,'message'=>['Successfully Updated']]);
            }
        }
    }
    // Get Details
    public function get_detials(Request $request)
    {
        if ($request->get('id')) {
             $data = $this->machine->where('id','=',$request->get('id'))->first();
             return response()->json(['valid'=>true,'info'=>[$data]]);
        } 
    }
// Real Time load of Host or the Receiver
    public function Load_data()
    {
        $list = $this->machine->where('active_status','=',1)->where('machine_type','=',2)->orderBy('machine_name','ASC')->get();
        foreach ($list as $lists) {
            $this->output .= '<tr>
            <td>'.$lists->machine_name.'</td>
            <td>'.time_con($lists->trigger_time).'</td>
            <td>'.aoi_status($lists->status).'</td>
            <td>'.$lists->load_needed.'</td>
            <td>'.$lists->second_order.'</td>
            <td>'.$lists->third_order.'</td>
            <td class="word-wrap">'.$lists->machine_setup.'</td>';
            if($lists->status == 3){$this->output .='<td><button class="btn btn-primary go" data-id="'.$lists->id.'">Received</button></td>';}
            elseif($lists->status == 2){$this->output .='<td><button class="btn btn-primary" disabled><i class="fas fa-spinner"></i> Processing Request</button></td>';}
            elseif($lists->status == 4){$this->output .='<td><button class="btn btn-primary" disabled><i class="fas fa-spinner"></i> No Request</button></td>';}
            else{$this->output .='<td><button class="btn btn-primary" disabled><i class="fas fa-spinner"></i> Ordering</button></td>';}
            $this->output .='</tr>';
        }
        return $this->output;
    }

// Machine Updates
    public function qet(Request $req)
    {
        $list = $this->machine->where('id','=',$req->get('id'))->get();
        foreach ($list as $lists) {

            $this->output .= '
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 text-center">
                            <h1>'.qet_status($lists->status).'</h1>
                        </div>
                        <div class="col-md-6 col-md-offset-3 text-center">
                            <h3>Trigger Time: '.time_con($lists->trigger_time).'</h3>
                        </div>
                    </div>';
            if ($lists->status == 4) {
                $this->output .= '<table class="table table-bordered">
                                <thead>
                                <th>First Order</th>
                                <th>Second Order</th>
                                <th>Third Order</th>
                                </thead>
                                <tbody>
                                <td colspan="3" class="text-center">No Request Order</td>
                                </tbody>
                                </table>';
            }
            else{
                $this->output .= '<table class="table table-bordered">
                                <thead>
                                <th>First Order</th>
                                <th>Second Order</th>
                                <th>Third Order</th>
                                </thead>
                                <tbody>
                                <td>'.$lists->load_needed.'</td>
                                <td>'.$lists->second_order.'</td>
                                <td>'.$lists->third_order.'</td>
                                </tbody>
                                </table>';
            }
            $this->output .= '<div class="row">
                        <div class="col-md-4 col-md-offset-4 text-center">';
                        if ($lists->status == 1) {
                            $this->output .= '<button class="btn btn-primary go" data-id="'.$lists->id.'">Process Request</button>';
                        }
                        elseif($lists->status == 2){
                            $this->output .= '<button class="btn btn-success go" data-id="'.$lists->id.'">Ready For Pick Up</button>';
                        }
                        elseif($lists->status == 3){
                            $this->output .= '<button class="btn btn-primary disabled"><i class="fas fa-spinner"></i> Delivery Request</button>';
                        }
                        else{
                            $this->output .= '<button class="btn btn-primary disabled"><i class="fas fa-spinner"></i> No Request</button>';
                        }     
        $this->output .= '</div>
                    </div>';
        }
        return  $this->output;
    }

    public function update(Request $req)
    {
        $ip = $this->getUserIpAddr();
        $machlist = $this->machine->where('id','=',$req->get('id'))->first();
        if ($machlist->status == 1) {
            $this->machine->where('id','=',$req->get('id'))->update(['status' => 2,'trigger_time' => Carbon\Carbon::now(),'updated_at' => Carbon\Carbon::now()]);
            activity_log('Processed  '.$machlist->load_needed,$ip,Carbon\Carbon::now());
            return response()->json(['success' => true, 'message'=>['Processing the request']]);
        }
        elseif ($machlist->status == 2) {
            $this->machine->where('id','=',$req->get('id'))->update(['status' => 3,'trigger_time' => Carbon\Carbon::now(),'updated_at' => Carbon\Carbon::now()]);
            activity_log('Finished Process Request '.$machlist->load_needed,$ip,Carbon\Carbon::now());
            return response()->json(['success' => true, 'message'=>['Processing Complete']]);
        }
        elseif ($machlist->status == 3) {
            if (empty($machlist->second_order)) {
                $this->machine->where('id','=',$req->get('id'))->update(['load_needed' => NULL,'status' => 4,'trigger_time' => Carbon\Carbon::now(),'updated_at' => Carbon\Carbon::now()]);
                activity_log('Received '.$machlist->load_needed.' From: '.$machlist->machine_name,$ip,Carbon\Carbon::now());
                return response()->json(['success' => true, 'message'=>['Order received']]);
            }
            else{
                $this->machine->where('id','=',$req->get('id'))->update([
                    'load_needed' => $machlist->second_order,
                    'second_order' => $machlist->third_order,
                    'third_order' => NULL,
                    'status' => 2,
                    'trigger_time' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now()
                ]);
                activity_log('Received '.$machlist->load_needed.' From: '.$machlist->machine_name,$ip,Carbon\Carbon::now());
                return response()->json(['success' => true, 'message'=>['Order received']]);
            } 
        }
        else {
            
        }
    }
    public function machine_order(Request $req)
    {
        $ip = $this->getUserIpAddr();
        $machlist = $this->machine->where('id','=',$req->get('id'))->first();
        $rules = [
                'request_load'=>'required',
                'id'=>'required'
            ];
            $validator = Validator::make($req->all(),$rules);
            if ($validator->fails()) {
               return response()->json(['success' => false,'message'=>['Please Fill up all the fields']]);
           }else{
             if (empty($machlist->load_needed)) {
                 $this->machine->where('id','=',$req->get('id'))->update(['status' => 1,'load_needed'=> $req->get('request_load'),'trigger_time' => Carbon\Carbon::now(),'updated_at' => Carbon\Carbon::now()]);
                 activity_log('Requested '.$req->get('request_load').' - Machine Name:'.$machlist->machine_name,$ip,Carbon\Carbon::now());
                 return response()->json(['success' => true, 'message'=>['Request Send']]);
             }
             elseif(empty($machlist->second_order)){
                $this->machine->where('id','=',$req->get('id'))->update(['second_order'=> $req->get('request_load'),'trigger_time' => Carbon\Carbon::now(),'updated_at' => Carbon\Carbon::now()]);
                activity_log('Requested '.$req->get('request_load').' - Machine Name:'.$machlist->machine_name,$ip,Carbon\Carbon::now());
                return response()->json(['success' => true, 'message'=>['Request Send']]);
            }
            else{
                $this->machine->where('id','=',$req->get('id'))->update(['third_order'=> $req->get('request_load'),'trigger_time' => Carbon\Carbon::now(),'updated_at' => Carbon\Carbon::now()]);
                activity_log('Requested '.$req->get('request_load').' - Machine Name:'.$machlist->machine_name,$ip,Carbon\Carbon::now());
                return response()->json(['success' => true, 'message'=>['Request Send']]);
            }
        }
    }

    // Get IP ADDRESS
    public function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    // Machine Setup Update
    public function machine_setup(Request $request)
    {
        $ip = $this->getUserIpAddr();
        $rules = [
                'setup'=>'required'
            ];
            $validator = Validator::make($request->all(),$rules);
            if ($validator->fails()) {
               return response()->json(['success' => true,'message'=>['Please Fill up all the fields']]);
           }
           else{
            $this->machine->where('id','=',$request->get('machine_id'))->
            update(['machine_setup'=>$request->get('setup')]);
            activity_log('Changed Machine Setup ',$ip,Carbon\Carbon::now());
            return response()->json(['success' => true, 'message'=>['Machine Setup successfully saved']]);
           }
    }

    // Activity Logs
    public function activity_logs_view()
    {
        $data['ip'] = $this->getUserIpAddr();
        $data['logs'] = $this->logs->select('a.machine_name','tbl_logs.user_ip','tbl_logs.activity_name','tbl_logs.date_created')->leftjoin('tbl_machine_details as a','a.ip_address','=','tbl_logs.user_ip')->orderBy('date_created','Desc')->get();
        return view('logs.activity',$data);
    }

}
