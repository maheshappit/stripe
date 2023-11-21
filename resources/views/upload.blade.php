@extends('layouts.dashboard')

@section('dashboard-content')

<div class="item">
    <form id="uploadForm" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csvFile" accept=".csv">
        <button class="btn btn-primary" id="uploadButton" type="submit">Upload</button>
    </form>

    <a href="{{ asset('Samples/Sample.csv') }}" download>Sample Headers File Download</a>

    <div id="message" style="color: red"></div>
</div>

<script>
    $('#uploadForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        document.getElementById('uploadButton').disabled = true;


        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();

                if(xhr.status==0){
                    $('#message').text('Data Uploading please wait..').show();

                }
                else if(xhr.status === 200){

                    $('#message').text('Data Uploaded Successfully').show();


                }
                return xhr;
            },
            
            url: '{{ route('upload') }}', // Using Laravel's route helper
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#message').text(response.message).show();
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
                $('#message').html(errorMessage).show();
            }
        });
    });
</script>


@endsection