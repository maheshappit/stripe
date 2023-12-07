<?php



namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\ConferencesData;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use app\Models\User;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;



use App\Exports\ExportUsers;
use App\Models\FeebBack;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Response;

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


        // $encodedClientNames = array_map('utf8_encode', $conferenceNames);
        return response()->json(['conferenceNames' => $conferenceNames]);
    }

    private function arrayToCsv($array)
    {
        $output = fopen('php://output', 'w');

        // Add headers
        fputcsv($output, array_keys($array[0]));

        // Add data
        foreach ($array as $row) {
            fputcsv($output, $row);
        }

        fclose($output);

        return ob_get_clean();
    }



    public function sentEmail(Request $request)
    {
        $now = Carbon::now();
        $currentDateTime = $now->toDateString();

        if (!empty($request->selectedData)) {
            foreach ($request->selectedData as $email) {

                if (isset($email['email'])) {

                    $original_email = $email['email'];
                    $conference = Conference::where('email', $original_email)->where('conference', 'LIKE', '%' . $request->conference . '%')->first();
                    if ($conference) {
                        $conference->email_sent_status = 'sent';
                        $conference->email_sent_date = $currentDateTime;
                        $conference->save();
                    }
                }
            }
        }


        $data = $request->selectedData;

        $csvFileName = 'example.csv';
        // Set the headers for the response
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $csvFileName,
        ];

        // Create a StreamedResponse
        $response = new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');



            // Output the CSV header
            fputcsv($handle, ['id', 'name', 'email', 'article', 'country', 'conference', 'user_created_at', 'user_updated_at', 'user_id', 'download_count', 'created_at', 'updated_at', 'email_sent_status', 'email_sent_date', 'client_status', 'posted_by', 'DT_RowIndex']);

            // Output the CSV data
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, $headers);

        // Get the underlying Symfony response instance
        $symfonyResponse = $response->prepare(request());

        // Include a status message in the response headers
        $symfonyResponse->headers->set('X-Status-Message', 'Emails Status Changed Successfully');

        return $symfonyResponse;
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


        $now = Carbon::now();
        $currentDateTime = $now->toDateString();
        $user = Conference::find($request->id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {

            // dd($request->client_status);

            $user->update([
                'name' => $request->name,
                'conference' => $request->conference,
                'article' => $request->article,
                'email' => $request->email,
                'country' => $request->country,
                'updated_at' => $currentDateTime,
                'client_status' => $request->client_status,
            ]);





         


                    $feedback = FeebBack::create([
                        'comment' => $request->comment,
                        'email' => $request->email,
                        'conference' => $request->conference,
                        'article' => $request->article,
                        'client_status' => $request->client_status,
                        'comment_created_date'=>$currentDateTime
                    ]);
                   


                    return response()->json([
                        'status_code' => '200',
                        'message' => 'Client Updated Successfully',
                    ]);
                }
            

           

        }
    }

