@extends('layouts.dashboard')

@section('dashboard-content')

<div class="item">
    <div class="input-group mb-3">
        <input type="text" name="search" id="search" class="form-control" placeholder="Search Data Here..." aria-label="Recipient's username" aria-describedby="basic-addon2">
        <div class="input-group-append">
            <button class="btn btn-success" id="searchButton" type="button">Search</button>
            <button class="btn btn-warning" id="MainClearBtn" type="button">Clear</button>

        </div>
    </div>

</div>





<div class="item">

    <h6>Bd Users Data</h6>
    <form id="form">

        <div class="form-row">

            <div>
                <label for="country"> Country:</label>
                <select id="country" name="country" class="country">
                    <option value="All">All</option>
                    @foreach($countries as $code => $name)
                    <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>

            </div>

            <div>
                <label for="client">Client Name:</label>
                <select id="client" name="client" class="client" style="width:auto">

                    <option value="">All Client Names</option>


                </select>


            </div>

            <div>
                <label for="db">DB Creator Name:</label>
                <select id="db" name="database_creator_name" class="db">

                    <option value="All">All</option>

                    @foreach($dba_names as $code => $name)
                    <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach

                </select>

            </div>

        </div>






        <div class="form-row">

            <div>
                <label for="technology"> Technology:</label>
                <select id="technology" name="technology" class="technology">
                    <option value="All">All</option>
                    @foreach($technology as $code => $name)
                    <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>


            </div>
            <div>
                <label for="technology"> Speciality:</label>
                <select id="speciality" name="speciality" class="speciality">
                    <option value="All">All</option>
                    @foreach($client_speciality as $code => $name)
                    <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>

            </div>

            <div>
                <label for="technology"> Designation:</label>
                <input type="text" name="designation" id="designation">
            </div>

            <div>
                <label for="country"> Emp Count:</label>
                <input type="text" id="emp_count" name="emp_count">
                <button id="search-btn" class="btn-sm btn-primary">Search</button>


            </div>


        </div>









        


        <!-- <button class="btn btn-warning" onclick="resetSelect()" type="button">Reset</button> -->



    </form>

</div>

<div class="item">
    <table id="dtHorizontalExample" class="table">
        <thead>
            <tr>
                <th>Industry</th>
                <th>State</th>
                <th>Country</th>
                <th>Client Name</th>

                <th>Contact Source</th>
                <th>Database Creator Name</th>
                <th>Technology</th>
                <th>Client Speciality</th>
                <th>Street</th>
                <th>City</th>
                <th>Zip Code</th>
                <th>Website</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Designation</th>
                <th>Email</th>
                <th>Email Response 1</th>
                <th>Email Response 2</th>
                <th>Rating</th>
                <th>FollowUp</th>
                <th>LinkedIn Link</th>
                <th>Employee Count</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>


</div>



@endsection