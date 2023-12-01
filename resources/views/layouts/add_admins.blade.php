<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">


    <style>
        #topRightButtonAddUsers {
            /* Adjust the right position as needed for spacing from the right */
            position: absolute;
            top: 74px;
            right: 150px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
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

        .my {
            top: 80px;
            width: fit-content;
        }
        .toast-message{
            color:black
        }
        
    </style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        $('#submitButton').click(function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = $('#admin-create').serialize();

            // Include CSRF token in the headers
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route("superadmin.create") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    toastr.success(response.message);
                    $('#name').val('');
                    $('#email').val('');
                    $('#role').val('');

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


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<div class="container">
    <button class="btn btn-primary" id="topRightButtonAddUsers">Add User/Admin</button>
</div>


<div id="AddUsersModal" class="modal">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card my">
                    <button type="button" class="close" aria-label="Close" onclick="closeCard()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="card-header">{{ __('Register') }}</div>




                    <div class="card-body">
                        <form  id="admin-create" method="POST" >
                            @csrf

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('Role') }}</label>

                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" id="userRadio" name="role" value="user" required>
                                        <label class="form-check-label" for="userRadio">User</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input type="radio" class="form-check-input" id="adminRadio" name="role" value="admin" required>
                                        <label class="form-check-label" for="adminRadio">Admin</label>
                                    </div>

                                    @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>




                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                           
                        
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn btn-primary" id="submitButton">Submit</button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    const topRightButtonadduser = document.getElementById("topRightButtonAddUsers");
    const modal = document.getElementById("AddUsersModal");
    const closeModal = document.querySelector(".close");

    topRightButtonadduser.addEventListener("click", function() {
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

    const AddusersuploadFormElement = document.getElementById("uploadForm");
    AddusersuploadFormElement.addEventListener("submit", function(e) {
        e.preventDefault();
        // Handle the form submission here or close the modal
        // modal.style.display = "none";
    });
</script>