<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\ConferenceDetails;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;




class UserController extends Controller
{
    
   

    public function index(Request $request)
{




    // dd($request->search);

    $query = ConferenceDetails::query();

    if($request->search){
        $query
        ->orwhere('country', 'like', '%' . $request->search . '%')->orderBy('country', 'asc')
        ->orwhere('email', 'like', '%' . $request->search . '%')->orderBy('email', 'asc')
        ->orwhere('create_date', 'like', '%' . $request->search . '%')
        ->orWhere('email_sent_date', 'like', '%' . $request->search . '%')
        ->orWhere('company_source', 'like', '%' . $request->search . '%')
        ->orWhere('contact_source', 'like', '%' . $request->search . '%')
        ->orWhere('database_creator_name', 'like', '%' . $request->search . '%')
        ->orWhere('technology', 'like', '%' . $request->search . '%')
        ->orWhere('client_speciality', 'like', '%' . $request->search . '%')
        ->orWhere('client_name', 'like', '%' . $request->search . '%')
        ->orWhere('street', 'like', '%' . $request->search . '%')
        ->orWhere('city', 'like', '%' . $request->search . '%')
        ->orWhere('state', 'like', '%' . $request->search . '%')
        ->orWhere('zip_code', 'like', '%' . $request->search . '%')
        ->orWhere('website', 'like', '%' . $request->search . '%')
        ->orWhere('first_name', 'like', '%' . $request->search . '%')
        ->orWhere('country', 'like', '%' . $request->search . '%')
        ->orWhere('last_name', 'like', '%' . $request->search . '%')
        ->orWhere('designation', 'like', '%' . $request->search . '%')
        ->orWhere('email_response_1', 'like', '%' . $request->search . '%')
        ->orWhere('email_response_2', 'like', '%' . $request->search . '%')
        ->orWhere('email_response_3', 'like', '%' . $request->search . '%')
        ->orWhere('email_response_4', 'like', '%' . $request->search . '%')
        ->orWhere('email_response_5', 'like', '%' . $request->search . '%')

        ->orWhere('rating', 'like', '%' . $request->search . '%')
        ->orWhere('followup', 'like', '%' . $request->search . '%')
        ->orWhere('linkedin_link', 'like', '%' . $request->search . '%')
        ->orWhere('employee_count', 'like', '%' . $request->search . '%')
      


        ;

    }

        if($request->country == 'All'){

            $query->whereNotNull('country')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');

        }else{
            $query->where('country', 'like', '%' . $request->country . '%')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');

        }

        //for all individual clients
        if($request->client == "All"){
            $query->whereNotNull('client_name')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');

        }else{
            $query->where('client_name', 'like', '%' . $request->client . '%')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');

        }

        //for all dbs
        if($request->db == 'All'){
            $query->whereNotNull('database_creator_name')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');

        }else{
            $query->where('database_creator_name', 'like', '%' . $request->db . '%')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');

        }

        //for all Technology
        if($request->technology == 'All'){
            $query->whereNotNull('technology')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');

        }else{
            $query->where('technology', 'like', '%' . $request->technology . '%')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');

        }

        //for all Speciality


        if($request->speciality == 'All'){
            $query->whereNotNull('client_speciality')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');

        }else{
            $query->where('client_speciality', 'like', '%' . $request->speciality . '%')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');

        }



    
        // get country,client,all dbs 
        if(($request->country == 'All') && ($request->client =='All') && ( $request->db=='All') && ($request->technology == 'All') && ($request->speciality == 'All')){

            $query->whereNotNull('database_creator_name')->whereNotNull('country')->whereNotNull('client_name')->whereNotNull('technology')->whereNotNull('client_speciality')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');
        }

        // get particular country,client,db

        if ($request->country != 'All' && $request->client != 'All' && $request->db != 'All') {
            $query->where('client_name', 'like', '%' . $request->client . '%')->where('database_creator_name', 'like', '%' . $request->db . '%')->where('country', 'like', '%' . $request->country . '%')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');
        }



        //all countries and particular client,db creator name

        if ($request->country == 'All' && $request->client != 'All' && $request->db != 'All') {
            $query->where('client_name', 'like', '%' . $request->client . '%')->where('database_creator_name', 'like', '%' . $request->db . '%')->whereNotNull('country')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');

        }


        //only speciality with all

        if(($request->country == 'All') && ($request->client =='All') && ( $request->db=='All') && ($request->technology == 'All') && ($request->speciality != 'All')){

            $query->whereNotNull('database_creator_name')->whereNotNull('country')->whereNotNull('client_name')->whereNotNull('technology')->where('client_speciality',$request->speciality)->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');
        }


                //only speciality and techonology with all


                if(($request->country == 'All') && ($request->client =='All') && ( $request->db=='All') && ($request->technology != 'All') && ($request->speciality != 'All')){

                    $query->whereNotNull('database_creator_name')->whereNotNull('country')->whereNotNull('client_name')->where('technology',$request->technology)->where('client_speciality',$request->speciality)->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');
                }

                //country,technology,speciality
                if(($request->country !='All') && ($request->client =='All') && ( $request->db=='All') && ($request->technology != 'All') && ($request->speciality != 'All')){

                    $query->whereNotNull('database_creator_name')->where('country',$request->country)->whereNotNull('client_name')->where('technology',$request->technology)->where('client_speciality',$request->speciality)->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');
                }

                //country,client name ,technology

                if(($request->country !='All') && ($request->client !=='All')  && ($request->technology != 'All') && ($request->speciality == 'All') && ( $request->db =='All')){

                    $query->where('country',$request->country)->where('client_name',$request->client)->where('technology',$request->technology)->whereNotNull('client_speciality')->whereNotNull('database_creator_name')->where('designation', 'like', '%' . $request->designation . '%')->where('employee_count', 'like', '%' . $request->emp_count . '%');
                }



                return DataTables::of($query)
                ->make(true);
        
}
public function showReport(){


    $all_users=User::all();
    return view('user.reports',compact('all_users'));
}

public function downloadReport(Request $request){

// dd($request->all())

    $all_users=User::all();


    $f_date = Carbon::parse($request->from_date);
    $startDate = $f_date->format('Y-m-d H:i:s');
    // dd($startDate);
    $t_date = Carbon::parse($request->to_date);
    $endDate = $t_date->format('Y-m-d H:i:s');

    $request->validate([
        'from_date' => 'required',
        'to_date' => 'required',

    ]);


    
    $query = ConferenceDetails::query();

    $query->join('users', 'users.id', '=', 'bd.user_id');
    $query->whereBetween('bd.user_created_at', [$startDate, $endDate]);
    
    if ($request->user_id == 'All') {
        $query->select('users.id', 'users.name', 'users.created_at');
        $query->selectRaw('
            COUNT(DISTINCT CASE WHEN bd.user_created_at IS NOT NULL THEN bd.id END) as inserted_count,
            COUNT(DISTINCT CASE WHEN bd.user_updated_at IS NOT NULL THEN bd.id END) as updated_count,
            SUM(bd.download_count) as download_count'
        );
        $query->whereNotNull('bd.user_created_at'); // Only count inserted records
        $query->groupBy('users.id', 'users.name', 'users.created_at');
    } else {
        $query->where('bd.user_id', $request->user_id);
        $query->select('users.id', 'users.name', 'users.created_at');
    
        $query->selectRaw('
            users.id, users.name,
            SUM(CASE WHEN bd.user_created_at IS NOT NULL THEN 1 ELSE 0 END) as inserted_count,
            SUM(CASE WHEN bd.user_updated_at IS NOT NULL THEN 1 ELSE 0 END) as updated_count,
            SUM(bd.download_count) as download_count'
        );
        $query->groupBy('users.id', 'users.name', 'users.created_at');
    }
    
    
    $result = $query->get();
    
    return DataTables::of($result)
        ->make(true);
    

}


public function downloadEmails(Request $request){
    
    $emails=$request->emails;
    if(isset($emails)){
        foreach($emails as $email){
            ConferenceDetails::where('email', $email)->update(['download_count' => 1]);
        }
    }


}

}
