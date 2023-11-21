<?php

namespace App\Http\Controllers;
use Validator;

use Illuminate\Http\Request;
use App\Models\BdModel;

use League\Csv\Reader;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;





class CsvController extends Controller
{

    public function upload(Request $request)
    {

        $now = Carbon::now();


        $currentDateTime = $now->toDateTimeString(); // Retrieves the date and time in 'Y-m-d H:i:s' format



        
        $userID = Auth::id();
        // dd($userID);
        $request->validate([
            'csvFile' => 'required|mimes:csv,txt|max:10000000',
        ]);
    
       
       
        $file = $request->file('csvFile');
        $path = $file->getRealPath();
    
        $csv = Reader::createFromPath($path, 'r');
        $headers = $csv->fetchOne();

        // dd($headers);

        $csv->setHeaderOffset(0);

        //if upload from file upload and move to public uploads

        // $file = $request->file('csvFile');

        // $filePath = $file->move(public_path('uploads'), $file->getClientOriginalName()); // Move the file to 'public/uploads' directory

        // $csv = Reader::createFromPath($filePath, 'r');
        // $csv->setHeaderOffset(0); // Set the CSV header row

    
        $update_count = 0;
        $errorCount = 0;
        $insertcount=0;

    
       

        foreach ($csv as $row) {
            $email = $row['Email'];
        
            // Check if the record exists based on the email
            $model = BdModel::where('email', $email)->first();
        
            if ($model) {
                // If the record exists, update it
                $model->update([
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
                'email_response_1'=>$row['Response 1'],
                'email_response_2'=>$row['Response 2'],
                'email_response_3'=>$row['Response 3'],
                'email_response_4'=>$row['Response 4'],
                'email_response_5'=>$row['Response 5'],

                'rating'=>$row['Rating'],
                'followup'=>$row['FollowUp'],
                'linkedin_link'=>$row['LinkedIn Link'],
                'employee_count'=>$row['Employee Count'],
                'user_id'=>$userID,
                'user_updated_at'=>$currentDateTime,
                // 'updated_at'=>'',
                ]);
                $update_count++;

            } else {
                // If the record doesn't exist, create a new one
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
                 'email_response_3'=>$row['Response 3'],
                'email_response_4'=>$row['Response 4'],
                'email_response_5'=>$row['Response 5'],
                'rating'=>$row['Rating'],
                'followup'=>$row['FollowUp'],
                'linkedin_link'=>$row['LinkedIn Link'],
                'employee_count'=>$row['Employee Count'],
                'user_id'=>$userID,
                'user_created_at'=>$currentDateTime,
                // 'updated_at'=>'',

                ]);
                $insertcount++;
            }
        }


        //if upload from uploads
        // if (file_exists($filePath)) {
        //     unlink($filePath);
        // } 
        
    
        return response()->json([
            'message' => 'Inserted Records Count: '.$insertcount.' Updated Records Count: ' . $update_count . ' Errors: ' . $errorCount,
        ]);
    }

   public function show(){

    return view('upload');
   }


    public function progress()
{
    $progress = session('upload_progress', 0);
    $finished = $progress == 100;

    return response()->json([
        'progress' => $progress,
        'finished' => $finished,
    ]);
}
    
}