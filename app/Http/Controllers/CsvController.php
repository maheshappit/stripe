<?php

namespace App\Http\Controllers;
use Validator;

use Illuminate\Http\Request;
use App\Models\Conference;

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


        $currentDateTime = $now->toDateString();



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
            $conference = $row['Conference'];
            $article=$row['Article'];

        
            // Check if the record exists based on the email
            $model = Conference::where('email', $email)->where('conference',$conference)->where('article',$article)->first();
        
            if ($model) {
                // If the record exists, update it
                $model->update([
                'name'=>$row['Name'],
                // 'email'=>$row['Email'],
                // 'article'=>$row['Article'],
                // 'conference'=>$row['Conference'],
                'country'=>$row['Country'],
                
                'user_id'=>$request->user()->id,

                'user_updated_at'=>$currentDateTime,
                // 'updated_at'=>'',
                ]);
                $update_count++;

            } else {
                // If the record doesn't exist, create a new one

                Conference::create([

                    'name'=>$row['Name'],
                    'email'=>$row['Email'],
                    'article'=>$row['Article'],
                    'conference'=>$row['Conference'],
                    'country'=>$row['Country'],
                    'user_id'=>$request->user()->id,

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
            'inserted_count' => 'Inserted Records Count: '.$insertcount,
            'updated_count'=> 'Updated Records Count: ' . $update_count ,
            'message'=>'Data Uploaded Successfully',
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