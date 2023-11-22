<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\ConferenceDetails;


class ConferenceDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|unique:conference_details|max:255',
        ]);  

    

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }else{


          

            ConferenceDetails::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'topic_id'=>$request->topic,
                'conference_id'=>$request->conference,
            ]);

            return response()->json([
                'message' => 'conference Details Added Successfully',
                'status_code'=>'200'
                
            ],200);


        }

        // Form data is valid, proceed with your logic
        // For demonstration purposes, we'll just return a success message
    
    
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
