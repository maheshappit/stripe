<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Conference;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;




class UserController extends Controller
{




    


    public function index(Request $request)
    {





        $query = Conference::query();

        if ($request->search) {
            $query
                ->orwhere('country', 'like', '%' . $request->search . '%')->orderBy('country', 'asc')
                ->orwhere('email', 'like', '%' . $request->search . '%')->orderBy('email', 'asc');
        } else {
            $query->whereNotNull('country');
        }

        if ($request->country == 'All') {

            $query->whereNotNull('country');
        } else {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        if ($request->conference == 'All') {

            $query->whereNotNull('conference');
        } else {
            $query->where('conference', 'like', '%' . $request->conference . '%');
        }

        if ($request->conference == 'All' && $request->country == 'All') {
            $query->whereNotNull('email');
        }

        if ($request->conference != 'All' && $request->country != 'All') {
            $query->where('conference', $request->conference)->where('country', $request->country);
        }

        if ($request->country == 'All' && $request->conference != 'All') {
            // dd($request);
            $query->where('conference', $request->conference);
        }




        return DataTables::of($query)
            ->make(true);
    }
    public function showReport()
    {


        $all_users = User::all();
        return view('user.reports', compact('all_users'));
    }

    public function downloadReport(Request $request)
    {

        // dd($request->all())

        $all_users = User::all();


        $f_date = Carbon::parse($request->from_date);
        $startDate = $f_date->format('Y-m-d');
        // dd($startDate);
        $t_date = Carbon::parse($request->to_date);
        $endDate = $t_date->format('Y-m-d');

        $request->validate([
            'from_date' => 'required',
            'to_date' => 'required',

        ]);



        $query = Conference::query();

        $query->join('users', 'users.id', '=', 'conferences.user_id');
        $query->whereBetween('conferences.user_created_at', [$startDate, $endDate]);

        if ($request->user_id == 'All') {
            $query->select('users.id', 'users.name', 'users.created_at');
            $query->selectRaw(
                '
            COUNT(DISTINCT CASE WHEN conferences.user_created_at IS NOT NULL THEN conferences.id END) as inserted_count,
            COUNT(DISTINCT CASE WHEN conferences.user_updated_at IS NOT NULL THEN conferences.id END) as updated_count,
            SUM(conferences.download_count) as download_count'
            );
            $query->whereNotNull('conferences.user_created_at'); // Only count inserted records
            $query->groupBy('users.id', 'users.name', 'users.created_at');
        } else {
            $query->where('conferences.user_id', $request->user_id);
            $query->select('users.id', 'users.name', 'users.created_at');

            $query->selectRaw(
                '
            users.id, users.name,
            SUM(CASE WHEN conferences.user_created_at IS NOT NULL THEN 1 ELSE 0 END) as inserted_count,
            SUM(CASE WHEN conferences.user_updated_at IS NOT NULL THEN 1 ELSE 0 END) as updated_count,
            SUM(conferences.download_count) as download_count'
            );
            $query->groupBy('users.id', 'users.name', 'users.created_at');
        }


        $result = $query->get();

        return DataTables::of($result)
            ->make(true);
    }


    public function downloadEmails(Request $request)
    {

        $emails = $request->emails;
        if (isset($emails)) {
            foreach ($emails as $email) {
                Conference::where('email', $email)->update(['download_count' => 1]);
            }
        }
    }
}
