<?php

namespace App\Http\Controllers\Admin;

use App\Models\Trip;
use App\Models\User;
use App\Models\Agent;
use App\Models\EmailLog;
use App\Models\FleetType;
use App\Models\UserLogin;
use App\Models\Transaction;
use App\Models\BookedTicket;
use Illuminate\Http\Request;
use App\Models\AgentCommission;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function transaction()
    {
        $pageTitle = 'Transaction Logs';
        $transactions = Transaction::with('user')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No transactions.';
        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'emptyMessage'));
    }

    public function indivisual_reports(Request $request)
    {
        $pageTitle = 'Booking Reports';

        if(!empty($request->start_date)){
            $start_date = date("Y-m-d",strtotime($request->start_date));
        }else{
            $start_date = date("Y-m-d",strtotime( "- 7 days"));
        }
        if(!empty($request->end_date)){
            $end_date = date("Y-m-d",strtotime($request->end_date));
        }else{
            $end_date = date("Y-m-d",strtotime ( "+ 7 days" ));
        }
        $booked_tickets = BookedTicket::whereBetween('date_of_journey', [$start_date, $end_date])->get();
        $arraydates = self::date_range($start_date, $end_date, $step = '+1 day', $output_format = 'Y-m-d' ) ;
        $trips = Trip::where('status',1)->get();
        $data = array();
        foreach($arraydates as $key => $value){
            $data[$key]['trip_date'] = $value;
            foreach($trips as $trip){
                    $data[$key]['trip_id'] =  $trip->id;
                    $data[$key]['trip_title'] = $trip->title;
                    $data[$key]['fleet_id'] = $trip->fleet_type_id;
                    $data[$key]['trip_day_off'] = $trip->day_off;
                    $data[$key]['total_seats'] = self::total_seats($trip->fleet_type_id);
                    $data[$key]['bookings'] = self::get_tickets($value,$trip->id);
                    $bookedby = self::get_tickets_bookedby($value,$trip->id);
                    if(!empty($bookedby)){
                        $booked_by_user = self::get_name($bookedby);
                    }else{
                        $booked_by_user = "";
                    }
                    $data[$key]['booked_by'] = $booked_by_user;
                    //
            }
        }
        
        $emptyMessage = 'Booking Reports';
        return view('admin.reports.indivisual', compact('pageTitle', 'start_date', 'end_date', 'data', 'emptyMessage'));
    }

    public function booking_reports(Request $request)
    {
        $pageTitle = 'Booking Reports';

        if(!empty($request->start_date)){
            $start_date = date("Y-m-d",strtotime($request->start_date));
        }else{
            $start_date = date("Y-m-d",strtotime( "- 7 days"));
        }
        if(!empty($request->end_date)){
            $end_date = date("Y-m-d",strtotime($request->end_date));
        }else{
            $end_date = date("Y-m-d",strtotime ( "+ 7 days" ));
        }
        $booked_tickets = BookedTicket::whereBetween('date_of_journey', [$start_date, $end_date])->get();
        $arraydates = self::date_range($start_date, $end_date, $step = '+1 day', $output_format = 'Y-m-d' ) ;
        $trips = Trip::where('status',1)->get();
        $data = array();
        foreach($arraydates as $key => $value){
            foreach($trips as $trip){
                $tripDetails = [];
                $tripDetails['bookings'] = self::get_tickets($value,$trip->id);
                
                if($tripDetails['bookings'])
                {
                    $tripDetails['trip_date'] = $value;
                    $tripDetails['trip_id'] =  $trip->id;
                    $tripDetails['trip_title'] = $trip->title;
                    $tripDetails['fleet_id'] = $trip->fleet_type_id;
                    $tripDetails['trip_day_off'] = $trip->day_off;
                    $tripDetails['total_seats'] = self::total_seats($trip->fleet_type_id);
                    $tripDetails['agentCommission'] = null;
                    $bookedby = self::get_tickets_bookedby($value,$trip->id);
                    if(!empty($bookedby)){
                        $booked_by_user = self::get_name($bookedby);
                        if($this->getAgentCommission($bookedby, $trip->id))
                        {
                            $tripDetails['agentCommission'] = $this->getAgentCommission($bookedby, $trip->id)->commission_amount * $tripDetails['bookings'];
                        }
                    }else{
                        $booked_by_user = "";
                    }
                    $tripDetails['booked_by'] = $booked_by_user;
                    $data[] = $tripDetails;
                }
            }
        }
        
        $emptyMessage = 'Booking Reports';
        return view('admin.reports.bookings', compact('pageTitle', 'start_date', 'end_date', 'data', 'emptyMessage'));
    }
    public function getAgentCommission($userId, $tripId)
    {
        if($agent = Agent::where('user_id', $userId)->first())
        {
            return AgentCommission::where('agent_id', $agent->id)->where('trip_id', $tripId)->first();
        }
        else
        {
            return null;
        }
    }
    public function get_name($id){
        $user = User::where('id',$id)->get()->first();
        return $user->firstname . " ".$user->lastname;
    }
    public function get_tickets($date,$trip_id){
       $bookings =  BookedTicket::where('date_of_journey',$date)
                                 ->where('trip_id',$trip_id)
                                 ->sum('ticket_count');
        return $bookings;
    }
    public function get_tickets_bookedby($date,$trip_id){
        $bookings =  BookedTicket::where('date_of_journey',$date)
                                  ->where('trip_id',$trip_id)
                                  ->get(['user_id'])->first();
        if(!empty($bookings)){
           
            return $bookings->user_id;
        }else{
            return false;
        }
                                  
     }

    public function total_seats($fleet_type_id){
        $capacity = FleetType::where('id',$fleet_type_id)->get('deck_seats')->first();
        return $capacity->deck_seats;
    }
    public function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
    
        while( $current <= $last ) {
    
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
    
        return $dates;
    }

    public function transactionSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = 'Transactions Search - ' . $search;
        $emptyMessage = 'No transactions.';

        $transactions = Transaction::with('user')->whereHas('user', function ($user) use ($search) {
            $user->where('username', 'like',"%$search%");
        })->orWhere('trx', $search)->orderBy('id','desc')->paginate(getPaginate());

        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'emptyMessage','search'));
    }

    public function loginHistory(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'User Login History Search - ' . $search;
            $emptyMessage = 'No search result found.';
            $login_logs = UserLogin::whereHas('user', function ($query) use ($search) {
                $query->where('username', $search);
            })->orderBy('id','desc')->with('user')->paginate(getPaginate());
            return view('admin.reports.logins', compact('pageTitle', 'emptyMessage', 'search', 'login_logs'));
        }
        $pageTitle = 'User Login History';
        $emptyMessage = 'No users login found.';
        $login_logs = UserLogin::orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login By - ' . $ip;
        $login_logs = UserLogin::where('user_ip',$ip)->orderBy('id','desc')->with('user')->paginate(getPaginate());
        $emptyMessage = 'No users login found.';
        return view('admin.reports.logins', compact('pageTitle', 'emptyMessage', 'login_logs','ip'));

    }

    public function emailHistory(){
        $pageTitle = 'Email history';
        $logs = EmailLog::with('user')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No data found';
        return view('admin.reports.email_history', compact('pageTitle', 'emptyMessage','logs'));
    }
}
