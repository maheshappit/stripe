<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;


use App\Models\Conference;
use App\Models\User;

use App\Models\ConferencesData;
use App\Models\FeebBack;
use Illuminate\Support\Carbon;


class ConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $conferences = ConferencesData::all();
        $countries = Conference::distinct()->pluck('country',)->toArray();
        $users = User::all();

        return view('conferences.index',compact('conferences','countries','users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $now = Carbon::now();
        $currentDateTime = $now->toDateString();
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'article'=>'required|string|max:255',
            'conference'=>'required',
            'country'=>'required|string|max:255'
        ],
    [
        'name.required'=>'The Client Name  is required.',
    ]);  


            $existingRecord = Conference::where('conference', $request->conference)
            ->where('article', $request->article)
            ->where('email', $request->email)
            ->first();

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }else{

            if ($existingRecord) {
                return response()->json(['errors' => [['Record Already Exists']]], 422);
            }else{

                Conference::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'article'=>$request->article,
                    'conference'=>$request->conference,
                    'country'=>$request->country,
                    'user_id'=>$request->user()->id,
                    'email_sent_status'=>'pending',
                    'user_created_at'=>$currentDateTime,                
                ]);


                return response()->json([
                    'message' => 'Conference Details Added Successfully',
                    'status_code'=>'200'
                    
                ],200);
                
            }


        }

     

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
