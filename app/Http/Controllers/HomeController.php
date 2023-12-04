<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\ConferencesData;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use app\Models\User;


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


    public function getClients(Request $request)
    {

        $client_names = Conference::where('country', $request->country_name)->distinct()->pluck('client_name')->toArray();
        $dba_names = Conference::distinct()->pluck('database_creator_name',)->toArray();
        $countries = Conference::distinct()->pluck('country',)->toArray();
        return view('home', compact('client_names', 'countries', 'dba_names'));
    }



    public function allClients(Request $request, $id)
    {

        if ($request->id === 'All') {
            // If 'All' is selected, fetch all client names
            $conferenceNames = Conference::distinct()->pluck('conference')->toArray();
        } else {
            // Fetch client names based on the selected country ID
            $conferenceNames = Conference::where('country', $id)->distinct()->pluck('conference')->toArray();
        }


        $encodedClientNames = array_map('utf8_encode', $conferenceNames);
        return response()->json(['conferenceNames' => $encodedClientNames]);
    }

    public function sentEmail(Request $request){
        

       $whole_data= $request->selectedData;
        if(!empty($request->selectedData)){
            foreach($whole_data as $email){

                if(isset($email['email'])){

                    $original_email=$email['email'];
                    $conference=Conference::where('email',$original_email)->where('conference', 'LIKE', '%' . $request->conference . '%')->first();
                    if($conference){
                        $conference->email_sent_status='sent';
                        $conference->save();
                    }

        

                }
            }

            return response()->json([
                'message' => 'Email  Status Changed Successfully',
                'status_code'=>'200'
                
            ],200);
        }
    }


    public function allTopics(Request $request, $id)
    {

        if ($request->id === 'All') {
            // If 'All' is selected, fetch all client names
            $topicNames = Conference::distinct()->pluck('article')->toArray();
        } else {
            // Fetch client names based on the selected country ID
            $topicNames = Conference::where('conference', $id)->distinct()->pluck('article')->toArray();
        }

        $encodedClientNames = array_map('utf8_encode', $topicNames);
        return response()->json(['topicNames' => $encodedClientNames]);
    }





    public function index()
    {

        $conferences = ConferencesData::all();

        $countries = Conference::distinct()->pluck('country',)->toArray();
        $users = User::all();
        return view('home', compact('countries', 'users', 'conferences'));
    }

    public function edit(Request $request)
    {
        $user = Conference::find($request->id);

        return view('edit', compact('user'));
    }


    public function update(Request $request)
    {

        // dd($request->id);

        $now = Carbon::now();


        $currentDateTime = $now->toDateString();

        $user = Conference::find($request->id);


        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {

            $user->update([
                'name' => $request->name,
                'conference' => $request->conference,
                'article' => $request->article,
                'email' => $request->email,
                'country' => $request->country,
                'updated_at' => $currentDateTime,

            ]);
        }

        return redirect()->route('home')->with('success', 'User Updated Successfully.');
    }
}
