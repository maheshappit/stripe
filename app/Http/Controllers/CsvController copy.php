<?php

namespace App\Http\Controllers;
use Validator;

use Illuminate\Http\Request;
use App\Models\BdModel;

use League\Csv\Reader;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;




class CsvController extends Controller
{

    public function upload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt|max:10000', 
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 for Unprocessable Entity

        }else{

            $file = $request->file('csv_file');
        $path = $file->getRealPath();
    
        $csv = Reader::createFromPath($path, 'r');
        $headers = $csv->fetchOne();

        // dd($headers);

        $csv->setHeaderOffset(0);


    
        foreach ($csv as $row) {
            // dd($row);


            $email = $row['Email']; // Assuming the email column in the CSV is named 'email'

        // Try to find a record with the same email in the database
        $existingRecord = BdModel::where('email', $email)->first();

        if ($existingRecord) {
            // If a record with the same email exists, update it
            $existingRecord->update([
                'create_date'=>$row['Create Date'],
                'email_sent_date'=>$row['Email sent Date'],
                'company_source'=>$row['Company Source'],
                'contact_source'=>$row['Contact Source'],
                'database_creator_name'=>$row['Database Creator Name'],
                'technology'=>$row['Technology'],
                // 'client_speciality'=>$row['Client Speciality'],
                'client_name'=>$row['Client Name'],
                'street'=>$row['Street'],
                'city'=>$row['City'],
                'state'=>$row['State'],
                'zip_code'=>$row['Zip Code'],
                'country'=>$row['Country'],
                'website'=>$row['Website'],
                'first_name'=>$row['First Name'],
                'last_name'=>$row['Last Name'],
                'designation'=>$row['Designation'],
                'email'=>$row['Email'],
                'email_response_1'=>$row['Response 1'],
                'email_response_2'=>$row['Response 2'],
                'rating'=>$row['Rating'],
                'followup'=>$row['FollowUp'],
                'linkedin_link'=>$row['LinkedIn Link'],
                'employee_count'=>$row['Employee Count']
            ]);
        } else {

            try{
                BdModel::create([
                    'create_date'=>$row['Create Date'],
                    'email_sent_date'=>$row['Email sent Date'],
                    'company_source'=>$row['Company Source'],
                    'contact_source'=>$row['Contact Source'],
                    'database_creator_name'=>$row['Database Creator Name'],
                    'technology'=>$row['Technology'],
                    'client_speciality'=>$row['Client Speciality'],
                    'client_name'=>$row['Client Name'],
                    'street'=>$row['Street'],
                    'city'=>$row['City'],
                    'state'=>$row['State'],
                    'zip_code'=>$row['Zip Code'],
                    'country'=>$row['Country'],
                    'website'=>$row['Website'],
                    'first_name'=>$row['First Name'],
                    'last_name'=>$row['Last Name'],
                    'designation'=>$row['Designation'],
                    'email'=>$row['Email'],
                    'email_response_1'=>$row['Response 1'],
                    'email_response_2'=>$row['Response 2'],
                    'rating'=>$row['Rating'],
                    'followup'=>$row['FollowUp'],
                    'linkedin_link'=>$row['LinkedIn Link'],
                    'employee_count'=>$row['Employee Count']
    
    
                ]);
            } 
            catch (QueryException $e){

// dd($e->getMessage());
                Log::error($e->getMessage());
                return back()->with('success', 'An error occurred while inserting data.');


            }
            // If no record with the same email exists, insert a new record
           
        }
        }

        return response()->json(['message' => 'Form submitted successfully']);

    

        }
    
        
    }
    
}