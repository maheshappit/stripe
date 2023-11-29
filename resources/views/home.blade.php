@extends('layouts.dashboard')
@section('dashboard-content')

<head>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


    <script>
        function openModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = 'flex';
        }

        function closeModal(modalId) {
            var modal = document.getElementById(modalId);
            modal.style.display = 'none';
        }
    </script>

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


<script>

$(document).ready(function() {

    $('#uploadForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        document.getElementById('uploadButton').disabled = true;


        $.ajax({
            xhr: function(res) {
                var xhr = new window.XMLHttpRequest();




                if (xhr.status == 0) {

                    $('#error-message').text('');

                    
                    $('#message')
                        .text('Data Uploading, please wait...')
                        .css({
                            'font-size': '16px',
                            'color': 'green',
                            'font-weight': 'bold'
                            // Add any other styles you want to apply
                        })
                        .show();
                } else if (xhr.status === 200) {
                    $('#message').remove();

                    $('#error-message').remove();



                    $('#message')
                        .text('Data uploaded success')
                        .css({
                            'font-size': '16px',
                            'color': 'green',
                            'font-weight': 'bold'
                            // Add any other styles you want to apply
                        })
                        .show();

                }
                return xhr;
            },

            url: '{{ route('upload') }}', // Using Laravel's route helper
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {

                $('#error-message').text('');


                $('#message').text(response.message).show();
                $('#inserted_count').text(response.inserted_count).show();
                $('#updated_count').text(response.updated_count).show();




                document.getElementById('uploadButton').disabled = false;

            },
            error: function(error) {
                var errorResponse = JSON.parse(error.responseText);
                var errorMessage = 'Error: ' + errorResponse.message;
                if (errorResponse.errors) {
                    errorMessage += '<br>';
                    Object.keys(errorResponse.errors).forEach(function(key) {
                        errorMessage += errorResponse.errors[key][0] + '<br>';
                    });
                }
                $('#message').text('');

                $('#error-message').html(errorMessage).show();
                document.getElementById('uploadButton').disabled = false;

            }
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

    <style>

        
.close-button {
    position: absolute;
    top: 10px;
    right: 37px;
    font-size: 20px;
    cursor: pointer;
    color: #333; /* Change the color to your preference */
}

.close-button:hover {
    color: #555; /* Change the hover color to your preference */
}
        .button-container {
            text-align: center;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            /* Blue color, you can change this */
            color: #fff;
            /* Text color, you can change this */
            margin: 5px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }


        .modal-upload {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 600px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-container {
            text-align: left;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-group button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<div class="item">
    <h4>hello ,{{ Auth::user()->name }}</h4>
    <div class="button-container">
        <button class="btn-sm btn-primary" onclick="openModal('modal1')">Create new Conference</button>
        <button class="btn-sm btn-success" onclick="openModal('modal2')">import</button>
    </div>

</div>

<div id="modal1" class="modal">
    <div class="modal-content">
        <div class="modal-title">Create New Conference</div>
        <div class="close-button" onclick="closeModal('modal1')">✕</div>
        <div class="form-container">
            <form id="myForm">
                @csrf
                <div>
                    <label for="conference">Conference:</label>
                    <select class="custom-select"   name="conference">
                        <option value="">--Choose Conference--</option>
                        @foreach($conferences as $code )
                        <option value="{{ $code->name}}">{{ $code->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="article">Article:</label>
                    <input type="text" id="article" name="article">
                </div>

                <div class="form-group">
                    <label for="name"> Client Name:</label>
                    <input type="text" id="name" name="name" >
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" >
                </div>

                <div class="form-group">
                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country" >
                </div>

                <button class="btn btn-success" type="submit">Submit</button>
            </form>
        </div>

        <!-- Close "x" mark -->
    </div>
</div>


<!-- Modal 2 -->
<div id="modal2" class="modal">
    <div class="modal-content">
        <div class="modal-title">upload data</div>
        <div class="close-button" onclick="closeModal('modal2')">✕</div>

        <form id="uploadForm" enctype="multipart/form-data">
        @csrf

       
        <input type="file" name="csvFile" accept=".csv">

        <label for="conference">Conference:</label>
                    <select class="custom-select"   name="conference">
                        <option value="">--Choose Conference--</option>
                        @foreach($conferences as $code )
                        <option value="{{ $code->name}}">{{ $code->name}}</option>
                        @endforeach
                    </select>
        <button class="btn btn-primary" id="uploadButton" type="submit">Upload</button>

        <div id="message" style="color: green"></div>
    <div id="error-message" style="color: red"></div>

    <div id="inserted_count" style="color: green"></div>
    <div id="updated_count" style="color: blue"></div>
    <div id="erros_count" style="color: red"></div>
        

    </form>        
    <a href="{{ asset('Samples/Sample.csv') }}" download>Sample Headers File Download</a>

    </div>
</div>


@endsection