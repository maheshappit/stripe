@extends('layouts.dashboard')

@section('dashboard-content')

<head>

    <!-- Add this to your HTML file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
            height: 100vh;
        }

        .button-container {
            display: flex;
            margin-top: 20px;
            margin-right: 20px;
        }

        .btn {
            margin-left: 10px;
            /* Adjust the margin between buttons */
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
            height: 100vh;
        }

        .button-container {
            display: flex;
            margin-top: 20px;
            margin-right: 20px;
        }

        .btn {
            margin-left: 10px;
            /* Adjust the margin between buttons */
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }



        /* Create Modal Styling */
        #createModal {
            display: none;
            position: fixed;
            top: 0;
            right: 250px !important;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .importcard {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: 0.25rem;
            top: 40px;
        }

        #openimportModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;

        }

        .modal-content {
            background-color: #fff;
            /* padding: 20px; */
            border-radius: 5px;
            text-align: center;
        }

        .card {
            top: -24px !important;
        }

        .single-row{
            
    display: flex;
    justify-content: space-around;

        }
    </style>


    <script>
        function openCreateModal() {
            document.getElementById('createModal').style.display = 'flex';
        }

        function openimportModal() {
            document.getElementById('uploadModal').style.display = 'flex';
        }

        function closeModal() {
            var modal = document.getElementById('createModal');
            modal.style.display = 'none';
        }


        function importcloseModal() {
            var modal = document.getElementById('uploadModal');
            modal.style.display = 'none';
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.conference').select2();
            $('.topic').select2();

            $('.import_conference').select2();
            $('.import_topic').select2();



            

        });
    </script>

    <script>
        $(document).ready(function() {

            $('#conference').change(function() {
                // Get the selected country value
                var selectedCountry = $(this).val();

                var url = "{{ route('all-topics', ['id' => 'id']) }}";
                url = url.replace('id', selectedCountry);

                // Make an AJAX request to retrieve client names based on the selected country
                $.ajax({
                    url: url, // Replace with your server-side script
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Update the client list with the retrieved data
                        $('#topic').html(displayClientNames(data.topicNames));
                    },
                    error: function(error) {
                        console.error('Error fetching client names:', error);
                    }
                });
            });

            function displayClientNames(topicNames) {
                var html = '<h2>Client Names:</h2><select>';

                $.each(topicNames, function(index, topic_name) {
                    html += '<option value="' + topic_name['id'] + '">' + topic_name['topic_name'] + '</option>';

                });
                html += '</select>';
                return html;
            }

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
                            closeModal();
                            $('#myForm')[0].reset();


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

            $('#import_conference').change(function() {
                // Get the selected country value
                var selectedCountry = $(this).val();

                var url = "{{ route('all-topics', ['id' => 'id']) }}";
                url = url.replace('id', selectedCountry);

                // Make an AJAX request to retrieve client names based on the selected country
                $.ajax({
                    url: url, // Replace with your server-side script
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Update the client list with the retrieved data
                        $('#import_topic').html(displayClientNames(data.topicNames));
                    },
                    error: function(error) {
                        console.error('Error fetching client names:', error);
                    }
                });
            });



            function displayClientNames(topicNames) {
                var html = '<h2>Client Names:</h2><select>';

                $.each(topicNames, function(index, topic_name) {
                    html += '<option value="' + topic_name['id'] + '">' + topic_name['topic_name'] + '</option>';

                });
                html += '</select>';
                return html;
            }

            $('#uploadForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                // document.getElementById('uploadButton').disabled = true;


                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();

                        // if (xhr.status == 0) {
                        //     $('#message').text('Data Uploading please wait..').show();

                        // } else if (xhr.status === 200) {

                        //     $('#message').text('Data Uploaded Successfully').show();


                        // }
                        return xhr;
                    },

                    url: '{{ route('upload') }}', // Using Laravel's route helper
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#message').text(response.message).show();
                        $('#error-message').remove();

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
                        handleValidationErrors(errorResponse.errors);
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





</head>

<div class="button-container">
    <button class="btn btn-success" onclick="openCreateModal()">Create</button>
    <button class="btn btn-success" onclick="openimportModal()">Import</button>
</div>




<div id="createModal" class="modal">
    <div class="card">
        <div class="card-header">
            Create Conference Details

            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                <span aria-hidden="true">&times;</span>

        </div>

        <div class="card-body">

            <form id="myForm">
                @csrf


                <div class="form-row">
                    <label>Conference </label>
                    <select id="conference" name="conference" class="conference">
                        <option value="">All</option>

                        @foreach($conferences as $code )
                        <option value="{{ $code['id'] }}">{{ $code['name'] }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="form-row">
                    <label>Topic </label>
                    <option value="All">All</option>

                    <select id="topic" class="topic" name="topic" style="width:auto"></select>
                </div>

                <div class="form-row">
                    <label for="exampleFormControlInput1">Name</label>
                    <input type="text" name="name" class="form-control">
                </div>

                <div class="form-row">
                    <label for="exampleFormControlInput1">Email address</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="form-row">
                    <label for="exampleFormControlInput1">Phone Number </label>
                    <input type="number" name="phone_number" class="form-control">
                </div>

                <div class="form-row">
                    <label for="exampleFormControlInput1">Country </label>
                    <input type="text" name="country" class="form-control">
                </div>






                <br>
                <div class="row">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>


            </form>

        </div>
    </div>
</div>

<div class="modal" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="importcloseModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- File Upload Form -->
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf

                    <div class="single-row">
                    <div class="">
                    <label>Conference</label>
                    <select id="import_conference" name="import_conference" class="import_conference">
                        <option value="All">All</option>

                        @foreach($conferences as $code )
                        <option value="{{ $code['id'] }}">{{ $code['name'] }}</option>
                        @endforeach
                    </select>

                    </div>
                   

                    <div class="form-row">
                    <label>Topic </label>
                    <option value="All">All</option>
                    <select id="import_topic" class="import_topic" name="import_topic" style="width:auto"></select>
                    </div>

                    </div>

                    <div class="single-row">
                        <div>
                        <input type="file" name="csvFile" accept=".csv">


                        </div>
                        <div>
                        <button class="btn btn-primary" id="uploadButton" type="submit">Upload</button>


                        </div>

                    </div>
                    

                   
                </form>
                <a href="{{ asset('Samples/Sample.csv') }}" download>Sample Headers File Download</a>


                <div class="single-row">
                <div id="message" style="color: green"></div>
    <div id="error-message" style="color: red"></div>

    <div id="inserted_count" style="color: green"></div>
    <div id="updated_count" style="color: blue"></div>
    <div id="erros_count" style="color: red"></div>    
                </div>
                 


</div>
        </div>
    </div>
</div>
</div>









@endsection