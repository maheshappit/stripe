<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Models\Conference;
class ConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        

        $conferences = Conference::distinct()->pluck('conference',)->toArray();

        return view('conferences.create',compact('conferences'));
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
            'email' => 'required|string|max:255',
            'article'=>'required',
            'conference'=>'required',
            'country'=>'required'
        ]);  


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }else{


            Conference::create([
                'name' => $request->name,
                'email' => $request->email,
                'article'=>$request->article,
                'conference'=>$request->conference,
                
            ]);

            return response()->json([
                'message' => 'Conference Details Added Successfully',
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
