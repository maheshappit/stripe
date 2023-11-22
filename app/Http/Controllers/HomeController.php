<?php

namespace App\Http\Controllers;

use App\Models\ConferenceDetails;
use App\Models\Conference;
use App\Models\Topic;
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

        $client_names = ConferenceDetails::where('country', $request->country_name)->distinct()->pluck('client_name')->toArray();
        $dba_names = ConferenceDetails::distinct()->pluck('database_creator_name',)->toArray();
        $countries = ConferenceDetails::distinct()->pluck('country',)->toArray();
        return view('home',compact('client_names','countries','dba_names'));

    }



    public function allTopics(Request $request, $id)
    {




        if ($request->id === 'All') {
            // If 'All' is selected, fetch all client names
            $topicNames = Topic::all();
        } else {
            // Fetch client names based on the selected country ID
            $topicNames = Topic::where('conference_id', $id)->get()->toArray();

        }
        

        return response()->json(['topicNames' => $topicNames]);
            }


    public function index()
    {

        $conferences=Conference::all();

        
        return view('home',compact(('conferences')));
        
    }

    public function edit(Request $request){
        $user=ConferenceDetails::find($request->id);

        return view('edit',compact('user'));
    }


    public function update(Request $request){

        $now = Carbon::now();

        $currentDateTime = $now->toDateTimeString(); 

        $user= ConferenceDetails::find($request->id);


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
