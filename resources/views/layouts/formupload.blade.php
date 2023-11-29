<head>
    
    <style>
        #topRightButton {
            /* Adjust the right position as needed for spacing from the right */
            position: absolute;
            top: 74px;
            right: 70px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 50%;
            height: 20%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }

        .upload-modal-content {
            background-color: #fff;
            position: absolute;
            top: 20%;
            left: 50%;
            width: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            text-align: center;
            border: 1px solid #ccc;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>


<div class="container">
    <button class="btn btn-success" id="topRightButton">Upload</button>
</div>

<div id="myModal" class="modal">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card upload-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        File Upload
                        <button type="button" class="close" aria-label="Close" onclick="closeCard()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="csvFile" accept=".csv">
                            <button class="btn btn-primary" id="uploadButton" type="submit">Upload</button>
                        </form>

                        <!-- <a href="{{ asset('Samples/Sample.csv') }}" download>Sample Headers File Download</a> -->

                        <div id="message" style="color: red"></div>
                        <div id="inserted_count" style="color: green"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    $('#inserted_count').text('Data Uploading please wait..').show();

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
                console.log(response);
                // $('#message').text(response.message).show();
                document.getElementById('uploadButton').disabled = false;

            },
            error: function(error) {
                // var errorResponse = JSON.parse(error.responseText);
                // var errorMessage = 'Error: ' + errorResponse.message;
                // if (errorResponse.errors) {
                //     errorMessage += '<br>';
                //     Object.keys(errorResponse.errors).forEach(function(key) {
                //         errorMessage += errorResponse.errors[key][0] + '<br>';
                //     });
                // }
                // $('#message').html(errorMessage).show();
            }
        });
    });
</script>




<script>
    const topRightButton = document.getElementById("topRightButton");
    const modal = document.getElementById("myModal");
    const closeModal = document.querySelector(".close");

    topRightButton.addEventListener("click", function() {
        modal.style.display = "block";
    });

    closeModal.addEventListener("click", function() {
        modal.style.display = "none";
    });

    window.addEventListener("click", function(e) {
        if (e.target == modal) {
            modal.style.display = "none";
        }
    });

    const uploadFormElement = document.getElementById("uploadForm");
    uploadFormElement.addEventListener("submit", function(e) {
        e.preventDefault();
        // Handle the form submission here or close the modal
        // modal.style.display = "none";
    });
</script>



