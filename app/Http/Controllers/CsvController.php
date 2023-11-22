<?php

namespace App\Http\Controllers;

use Validator;

use Illuminate\Http\Request;
use App\Models\ConferenceDetails;

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
            'import_conference'=>'required',
            'import_topic'=>'required',
        ],
        [
            'import_topic.required' => 'Please Select the Topic!',
            'import_conference.required' => 'Please select the Conference!'
        ]);


        $file = $request->file('csvFile');

        $path = $file->getRealPath();

        $csv = Reader::createFromPath($path, 'r');
        $headers = $csv->fetchOne();

        // dd($headers);

        $csv->setHeaderOffset(0);



        $update_count = 0;
        $errorCount = 0;
        $insertcount = 0;




        foreach ($csv as $row) {
            $email = $row['Email'];

            // Check if the record exists based on the email
            $model = ConferenceDetails::where('email', $email)->first();

            if ($model) {
                // If the record exists, update it
                $model->update([
                    'name' => $row['Name'],
                    'phone_number' => $row['Phone Number'],
                    'country' => $row['Country'],
                    'user_id' => $userID,
                    'user_updated_at' => $currentDateTime,
                    'conference_id'=>$request->import_conference,
                    'topic_id'=>$request->import_topic,
                    

                ]);
                $update_count++;
            } else {
                // If the record doesn't exist, create a new one
                ConferenceDetails::create([
                    'name' => $row['Name'],
                    'phone_number' => $row['Phone Number'],
                    'email' => $row['Email'],
                    'country' => $row['Country'],
                    'user_id' => $userID,
                    'user_created_at' => $currentDateTime,
                    'conference_id'=>$request->import_conference,
                    'topic_id'=>$request->import_topic,
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
            'inserted_count' => 'Inserted Records Count: '.$insertcount,
            'updated_count'=> 'Updated Records Count: ' . $update_count ,
            'message'=>'Data Uploaded Successfully',
        ]);
    }

    public function show()
    {

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
