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

    <h6>Conferences Data</h6>
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
                <label for="conference">Conferences:</label>
                <select id="conference" name="conference" class="conference" style="width:auto">

                    <option value="All">All conference Names</option>


                </select>


            </div>

            <div>

            <button id="search-btn" class="btn-sm btn-primary">Search</button>

</div>




         

        </div>






       

       








        


        <!-- <button class="btn btn-warning" onclick="resetSelect()" type="button">Reset</button> -->



    </form>

</div>

<div class="item">
    <table id="dtHorizontalExample" class="table">
       
    </table>


</div>



@endsection