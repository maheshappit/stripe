<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Config;



class BDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Request $request): void
    {


        // $handle = fopen($filePath, 'r');
        //  DB::disableQueryLog();
    DB::table('bd')->truncate();

    LazyCollection::make(function () {
        $filePath = Config::get('custom.csv_file_path');

      $handle = fopen(public_path('Database.csv'), 'r');
      
      while (($line = fgetcsv($handle, 4096)) !== false) {
        $dataString = implode(', ', $line);
        $row = explode(',', $dataString);
        yield $row;
      }

      fclose($handle);
    })
    ->skip(1)
    ->chunk(500)
    ->each(function (LazyCollection $chunk) {
        $records = $chunk->map(function ($row) {

            // dd($row[1]);
            return [
                "create_date" => $row[0] ?? null,  // Assign the value from $row[0] to the 'create_date' column
                "email_sent_date" => $row[1] ?? null, // Map other columns to values from $row as needed
                "company_source" => $row[2] ?? null,
                "contact_source" => $row[3] ?? null,
                "database_creator_name" => $row[4] ?? null,
                "technology" => $row[5] ?? null,
                "client_speciality" => $row[6] ?? null,
                "client_name" => $row[7] ?? null,
                "street" => $row[8] ?? null,
                "city" => $row[9] ?? null,
                "state" => $row[10] ?? null,
                "zip_code" => $row[11] ?? null,
                "country" => $row[12] ?? null,
                "website" => $row[13] ?? null,
                "first_name" => $row[14] ?? null,
                "last_name" => $row[15] ?? null,
                "designation" => $row[16] ?? null,
                "email" => $row[17] ?? null,
                "email_response_1" => $row[18] ?? null,
                "email_response_2" => $row[19] ?? null,
                "rating" => $row[20] ?? null,        
                "followup" => $row[21] ?? null,
                "linkedin_link" => $row[22] ?? null,
                "employee_count" => $row[23]  ?? null,// Assigning 'employee_count' from $row[0]
            ];
        })->toArray();

        // dd($records);
        
        DB::table('bd')->insert($records);
        
    
    });
    }
}
