<?php

namespace App\Http\Controllers;

use App\Models\BdModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Carbon;


class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


     public function getClients(Request $request){

        $client_names = BdModel::where('country', $request->country_name)->distinct()->pluck('client_name')->toArray();
        $dba_names = BdModel::distinct()->pluck('database_creator_name',)->toArray();
        $countries = BdModel::distinct()->pluck('country',)->toArray();
        return view('home',compact('client_names','countries','dba_names'));

    }



    public function allClients(Request $request, $id)
    {



        if ($request->id === 'All') {
            // If 'All' is selected, fetch all client names
            $clientNames = BdModel::distinct()->pluck('client_name')->toArray();
        } else {
            // Fetch client names based on the selected country ID
            $clientNames = BdModel::where('country', $id)->distinct()->pluck('client_name')->toArray();
        }
        

        $encodedClientNames = array_map('utf8_encode', $clientNames);
        return response()->json(['clientNames' => $encodedClientNames]);
            }


    public function index()
    {

        // $users_data=BdModel::latest()->paginate(100);
        $dba_names = BdModel::distinct()->pluck('database_creator_name',)->toArray();

        $countries = BdModel::distinct()->pluck('country',)->toArray();

        $technology=BdModel::distinct()->pluck('technology',)->toArray();

        $client_speciality=BdModel::distinct()->pluck('client_speciality',)->toArray();

        $designation=BdModel::distinct()->pluck('designation',)->toArray();

        // dd($designation);

        $email_count=BdModel::distinct()->pluck('employee_count',)->toArray();
        // dd($email_count);

        // $client_names = BdModel::distinct()->pluck('client_name',)->toArray();

        return view('home',compact('countries','dba_names','technology','client_speciality'));
        
    }

    public function edit(Request $request){
        $user=BdModel::find($request->id);

        return view('edit',compact('user'));
    }


    public function update(Request $request){

        $now = Carbon::now();

        $currentDateTime = $now->toDateTimeString(); 

        $user= BdModel::find($request->id);


        $validator = Validator::make($request->all(), [
            'create_date' => 'required', 
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }else{

            $user->update([
                'create_date'=>$request->create_date,
                'email_sent_date'=>$request->email_sent_date,
                'company_source'=>$request->company_source,
                'contact_source'=>$request->contact_source,
                'database_creator_name'=>$request->database_creator_name,
                'technology'=>$request->technology,
                'client_speciality'=>$request->client_speciality,
                'client_name'=>$request->client_name,
                'street'=>$request->street,
                'city'=>$request->city,
                'state'=>$request->state,
                'zip_code'=>$request->zip_code,
                'country'=>$request->country,
                'website'=>$request->website,
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'designation'=>$request->designation,
                'email'=>$request->email,
                'email_response_1'=>$request->email_response_1,
                'email_response_2'=>$request->email_response_2,
                'rating'=>$request->rating,
                'followup'=>$request->followup,
                'linkedin_link'=>$request->linkedin_link,
                'employee_count'=>$request->employee_count,
                'updated_at'=>$currentDateTime,
    
            ]);
    

        }


      
        return redirect()->route('home')->with('success', 'User Updated Successfully.');

    }
}
