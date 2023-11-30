@extends('layouts.superadmindashboard')

@section('dashboard-content')

<div class="item">

    <form id="uploadForm" enctype="multipart/form-data">
        @csrf
       
        <label for="conference">Conference:</label>
                    <select class="custom-select"   name="conference">
                        <option value="">--Choose Conference--</option>
                        @foreach($conferences as $code )
                        <option value="{{ $code->name}}">{{ $code->name}}</option>
                        @endforeach
                    </select>
        <input type="file" name="csvFile" accept=".csv">
        <button class="btn btn-primary" id="uploadButton" type="submit">Upload</button>
    </form>

    
 

    <a href="{{ asset('Samples/Sample.csv') }}" download>Sample Headers File Download</a>

    <div id="message" style="color: green"></div>
    <div id="error-message" style="color: red"></div>

    <div id="inserted_count" style="color: green"></div>
    <div id="updated_count" style="color: blue"></div>
    <div id="erros_count" style="color: red"></div>


</div>

<script>
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

            url: '{{ route('superadmin.upload') }}', // Using Laravel's route helper
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
</script>


@endsection