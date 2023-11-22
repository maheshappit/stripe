@extends('layouts.dashboard')

@section('dashboard-content')

<head>

    <style>
        .form-row {
            display: flex;
            justify-content: space-between;
        }

        .error-message {
            margin-left: 5rem;
            color: red;
        }

        
    </style>

</head>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<script>
    $(document).ready(function() {
        // Initialize DataTable with buttons
        var table = $('#dataTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        $('#clear').click(function(e){
            
            $('#from_date').val('');
            $('#to_date').val('');


        })

        // Intercept the form submission
        $('#form').submit(function(e) {
            // Prevent the default form submission
            e.preventDefault();
            $('.field-error').text('');

            var f_date=$('#from_date').val();

            var t_date=$('#to_date').val();



            function formatDate(inputDate) {
  const options = { day: '2-digit', month: 'short', year: 'numeric' };
  const date = new Date(inputDate);
  return date.toLocaleDateString('en-US', options);
}

// Example usage
const from_date = formatDate(f_date);
const to_date = formatDate(t_date);

console.log(from_date);




            // Get form data
            var formData = $(this).serializeArray();

            // Add additional variable to the payload

            // Make an Ajax request
            $.ajax({
                type: 'POST',
                url: "{{ route('report.download') }}", // Use the route function to generate the URL
                data: formData,
                success: function(response,status) {

                   
                    // Clear existing table data
                    table.clear().draw();

                   



                    // Populate the table with the new data
                    $.each(response.data, function(index, item) {
                        var createdAt = new Date(item.created_at);
                        var formattedCreatedAt = createdAt.toISOString().split('T')[0];

                        table.row.add([ 
                            from_date + ' To ' + to_date,
                            item.name,
                            item.inserted_count,
                            item.updated_count,
                            item.download_count,

                        ]).draw();
                    });
                },
                error: function(xhr, status, error) {

                    // Handle errors, including validation errors
                    var errors = xhr.responseJSON.errors;

                    // Display errors in your form
                    displayErrors(errors);
                },
                complete: function(xhr, status) {

                    if(xhr.status=='422'){

                        var errors = xhr.responseJSON.errors;

                    // Display errors in your form
                    displayErrors(errors);

                    }else if(xhr.status == '200'){
                        $('#error-from_date').text('');
                        $('#error-to_date').text('');



                    }else{
                        alert('somthing went wrong');
                    }
        // xhr.status contains the status code
    }
            });
        });

        function displayErrors(errors) {
            // Clear previous error messages
            // $('.message').remove();

            // Display new error messages
            $.each(errors, function(key, value) {
                // Assuming you have elements with IDs like 'error-from_date' and 'error-to_date'
                $('#' + 'error-' + key).html('<p class="error-message">' + value[0] + '</p>');
            });
        }
    });
</script>




<div class="item">
    <h6>Reports</h6>
    <div></div>

    <form id="form">

        @csrf

        <div class="form-row">


            <div>
                <label for="user_id"> Users:</label>
                <select id="user_id" name="user_id" class="user_id" style="height: 30;">
                    <option value="All">All</option>
                    @foreach($all_users as $name)
                    <option value="{{ $name['id'] }}">{{ $name['name'] }}</option>
                    @endforeach
                </select>

            </div>


            <div>
                <label for="country">From Date:</label>
                <input type="date" id="from_date" name="from_date" >
                <p class="message" id="error-from_date"></p>


            </div>

            <div>
                <label for="country">To Date:</label>
                <input type="date" id="to_date" name="to_date">
                <p class="message" id="error-to_date"></p>

            </div>

            <div>
                <button class="btn btn-primary"   type="submit">Submit</button>
                <button id="clear"  class="btn btn-warning">clear</button>

            </div>


        </div>



    </form>


    <div>
    </div>
</div>

<div class="item">
    <table id="dataTable" class="table table-striped">
        <!-- Table headers go here -->
        <thead>
            <tr>
                <th width="250px;">Date</th>
                <th>UserName</th>
                <th>Inserted Count</th>
                <th>Updated Count</th>
                <th>Downloaded Count</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table body will be populated using Ajax response -->
        </tbody>
    </table>
</div>
@endsection