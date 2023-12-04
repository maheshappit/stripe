@extends('layouts.dashboard')

@section('dashboard-content')


<head>

<style>
    .hidden {
      display: none;
    }
  </style>

               <script>
                    $(document).ready(function() {
                        $('.country').select2();
                        $('.conference').select2();
                        $('.article').select2();
                        $('.user_created_at').input();

                        
                       
                        
                    });
                </script>








</head>


<script>
   
</script>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
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


                <label for="article">Articles:</label>
                <select id="article" name="article" class="article" style="width:auto">

                    <option value="All">All Article Names</option>

                </select>


            </div>

            <div>
                <label for="user"> Users:</label>
                <select id="user" name="user" class="user">
                    <option value="All">All</option>
                    @foreach($users as $code => $name)
                    <option value="{{ $name['id'] }}">{{ $name['name'] }}</option>
                    @endforeach
                </select>

            </div>

            <div>
                <label for="country">Created Date:</label>

                <input type="date" name="user_created_at" class="user_created_at" id="user_created_at">

            </div>


            <div>
                <label for="country">Updated Date:</label>
                <input type="date" name="user_updated_at" class="user_updated_at" id="user_updated_at">
            </div>

            <div>
                <div>
                <button id="search-btn" class="btn-sm btn-primary">Search</button>

                
                </div>


</div>




         

        </div>






       

       








        


        <!-- <button class="btn btn-warning" onclick="resetSelect()" type="button">Reset</button> -->



    </form>

</div>

<div class="item">
<input type="checkbox" id="toggleCheckbox" disabled> Select All
<button id="hiddenButton" class="btn btn-success" style="display: none;">Sent Email</button>
    <table id="dtHorizontalExample" class="table">
       
    </table>


</div>



@endsection