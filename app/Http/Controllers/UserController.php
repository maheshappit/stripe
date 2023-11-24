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

        if ($request->article == 'All') {

            $query->whereNotNull('article');
        } else {
            $query->where('article', 'like', '%' . $request->article . '%');
        }


        if ($request->user == 'All') {

            $query->whereNotNull('email');
        } else {
            $query->where('user_id', 'like', '%' . $request->user . '%');
        }






        //for all country,conference,articles,users,created,updated dates
        if ($request->country == 'All' && $request->conference == 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {

            $query->whereNotNull('country')->whereNotNull('conference')->whereNotNull('article')->whereNotNull('user_id');
        }

        //particular country and all-->conferences,articles,users,created,updated dates
        if ($request->country != 'All' && $request->conference == 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            $query->where('country',$request->country)->whereNotNull('conference')->whereNotNull('article')->whereNotNull('user_id');
        }

         //particular country,conference and all-->conferences,articles,users,created,updated dates

        if ($request->country != 'All' && $request->conference != 'All' && $request->article == 'All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country',$request->country)->where('conference',$request->conference)->whereNotNull('article')->whereNotNull('user_id');
        }



        //particular country,conference,article, all users,all dates
        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user == 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country',$request->country)->where('conference',$request->conference)->where('article',$request->article)->whereNotNull('user_id');
        }


        //particular country,conference,article, users,all dates
        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user != 'All' && $request->user_created_at  == '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country',$request->country)->where('conference',$request->conference)->whereNotNull('article')->where('user_id',$request->user_id);
        }


                //particular country,conference,article,user,user created date,all 

        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user != 'All' && $request->user_created_at  != '' && $request->user_updated_at == '') {
            // dd($request);
            $query->where('country',$request->country)->where('conference',$request->conference)->whereNotNull('article')->whereNotNull('user_id')->where('user_created_at',$request->user_created_at);
        }


        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user != 'All' && $request->user_created_at  == '' && $request->user_updated_at != '') {
            // dd($request);
            $query->where('country',$request->country)->where('conference',$request->conference)->whereNotNull('article')->whereNotNull('user_id')->where('user_updated_at',$request->user_created_at);
        }


         //particular country,conference,article,user,user created date,user updated date

        if ($request->country != 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user != 'All' && $request->user_created_at  != '' && $request->user_updated_at != '') {
            // dd($request);
            $query->where('country',$request->country)->where('conference',$request->conference)->whereNotNull('article')->whereNotNull('user_id')->where('user_created_at',$request->user_created_at)->where('user_updated_at',$request->user_updated_at);
        }


        //country,conference,article,user,created,updated
        if ($request->country == 'All' && $request->conference != 'All' && $request->article == '!All' && $request->user != 'All' && $request->user_created_at  != '' && $request->user_updated_at != '' && $request->user_created_at) {
            // dd($request);
            $query->whereNotNull('country')->where('conference',$request->conference)->where('article',$request->article)->where('user_id',$request->user_id)->where('user_created_at',$request->user_created_at)->where('user_updated_at',$request->user_updated_at);
        }






        return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('posted_by', function($row) {
                    return $row->postedby->name;
                })
                ->rawColumns(['posted_by'])
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
