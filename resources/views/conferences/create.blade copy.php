@extends('layouts.dashboard')

@section('dashboard-content')

<head>
<script>
        $(document).ready(function() {

            $('#myForm').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('conferencedetails.save') }}',

                    data: formData,
                    success: function(response) {

                        console.log(formData);

                        if (response.status_code == '200') {
                            toastr.success(response.message);
                            $('#name').val('');
                            $('#email').val('');
                            $('#article').val('');
                            $('#email').val('');
                            $('#country').val('');

                            

                        }
                    },
                    error: function(xhr, status, error) {

                        var errors = xhr.responseJSON.errors;
                        handleValidationErrors(errors);
                    },
                });
            });

            function handleValidationErrors(errors) {
                // Display validation errors as toasts
                for (var field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        toastr.error(errors[field][0]);
                    }
                }
            }
        });
    </script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</head>

<div class="item">
        <div class="card-body">

            <form id="myForm">
                @csrf


                <div class="form-row">
                    <h4> Create Conference </h4>
                   
                </div>

                <div class="form-row">

                <label for="exampleFormControlInput1">Conference</label>

                <select class="custom-select" name="conference">
                    @foreach($conferences as $code )
                    <option   value="{{ $code->name}}">{{ $code->name}}</option>
                    @endforeach
                    
                </select>
                   

                </div>

               
                <div class="form-row">
                    <label for="exampleFormControlInput1">Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>

                <div class="form-row">
                    <label for="exampleFormControlInput1">Email address</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>

                <div class="form-row">
                    <label for="exampleFormControlInput1">Article </label>
                    <input type="text" name="article" id="article" class="form-control">
                </div>

                <div class="form-row">
                    <label for="exampleFormControlInput1">Country </label>
                    <input type="text" name="country" id="country" class="form-control">
                </div>

                

                <br>
                <div class="row">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>


            </form>

        </div>
</div>






@endsection