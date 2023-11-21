<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;

class ProcessCsvRecords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $pathToCsvFile;

    public function __construct($pathToCsvFile)
    {
        $this->pathToCsvFile = $pathToCsvFile;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $csv = Reader::createFromPath($this->pathToCsvFile, 'r');
            $csv->setHeaderOffset(0);

            $csv->chunk(100, function ($records) {
                foreach ($records as $row) {
                    dd($row);
                    // Process each record and save it to the database
                    Record::create([
                        'create_date'=>$row['Create Date'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            // Handle the exception, log the error, or requeue the job if necessary
        }
    }
}
