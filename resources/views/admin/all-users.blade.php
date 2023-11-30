@extends('layouts.admindashboard')

@section('dashboard-content')

<head>


    <style>
        .close-button {
            position: absolute;
            top: 10px;
            right: 37px;
            font-size: 20px;
            cursor: pointer;
            color: #333;
            /* Change the color to your preference */
        }

        .close-button:hover {
            color: #555;
            /* Change the hover color to your preference */
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
        // public/js/your_script.js

        $(document).ready(function() {
            $(".editBtn").click(function() {
                var id = $(this).data("id");
                $.ajax({
                    url: "{{ route('admin.conference.show', ['id' => ':id']) }}".replace(':id', id),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Populate the form with data
                        $("#id").val(data.id);

                        $("#name").val(data.name);
                        $("#email").val(data.email);
                        $("#role").val(data.role);
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            });





            // Add a click event for the save button if needed
            $('#myForm').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.user.update') }}',

                    data: formData,
                    success: function(response) {

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                        if (response.status_code == '200') {
                            toastr.success(response.message);
                            $('#name').val('');
                            $('#email').val('');
                            $('#id').val('');
                            $('#role').val('');
                            closeModal('modal1');





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
            $('.delete-btn').click(function() {
                var userId = $(this).data('id');
                openModal('modal2');
                $('#confirmDeleteBtn').data('id', userId);
            });

            // Confirm delete button in the modal
            $('#confirmDeleteBtn').click(function() {
                const user_id = $(this).data('id');

                // Make an AJAX request to delete the user
                $.ajax({
                    url: "{{ route('admin.user.delete')}}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: user_id
                    },
                    success: function(response) {

                        toastr.error(response.message);

                        setTimeout(function() {
                            location.reload();
                        }, 2000);

                    },
                    error: function(error) {
                        console.log('Error:', error);
                        alert('Failed to delete user');
                    }
                });

                // Hide the modal
            });
        });
    </script>







</head>


<div class="item">
    <h5>All Users Data</h5>
    <table class="table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
                <!-- Add more columns as needed -->
            </tr>
        </thead>
        <tbody>

            @foreach ($all_users as $item)
            <tr>
                <td>{{ ($all_users->currentPage() - 1) * $all_users->perPage() + $loop->index + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{$item->role}}</td>
                <td>


                    <button class="btn-sm btn-primary editBtn" data-id="{{$item->id}}" onclick="openModal('modal1')">Edit</button>
                    <button class="btn-sm btn-danger delete-btn" data-id="{{$item->id}}">Delete</button>

                    <!-- <a href="{{ route('admin.conference.show',$item->id) }}" data-toggle="modal" data-target="#conferenceModal">Show Details</a> -->

                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $all_users->links('pagination::bootstrap-4') }}
    </div>
</div>

<div id="modal1" class="modal">
    <div class="modal-content">
        <div class="modal-title">Edit User</div>
        <div class="close-button" onclick="closeModal('modal1')">✕</div>
        <div class="form-container">
            <form id="myForm">
                @csrf

                <input type="text" id="id" name="id" hidden>

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" value="{{$conference->name ?? ''}}" name="name">
                </div>


                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                </div>

                <div class="form-group">
                    <label for="role">Role:</label>
                    <select class="custom-select" id="role" name="role">
                        <option>--select--</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>

                    </select>
                </div>

                <button class="btn btn-success" type="submit">Update</button>
            </form>
        </div>

        <!-- Close "x" mark -->
    </div>
</div>

<div id="modal2" class="modal">
    <div class="modal-content">
        <div class="modal-title">Delete User</div>
        <div class="close-button" onclick="closeModal('modal2')">✕</div>
        <div class="modal-body">
            Are you sure you want to delete this User ?
        </div>

        <div class="modal-footer">

            <button type="button" id="confirmDeleteBtn" class="btn btn-danger confirmDeleteBtn" onclick="closeModal('modal2')">Confirm</button>

            <button type="button" class="btn btn-secondary" onclick="closeModal('modal2')">Cancel</button>

        </div>
        <!-- Close "x" mark -->
    </div>
</div>






@endsection