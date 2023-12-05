@extends('layouts.app')

@extends('layouts.formupload')

@section('content')


@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<style>
    td {
        white-space: nowrap;
        max-width: 100%;
    }

    .custom-message {
    color: green;
    font-weight: bold;
    /* Add any other styles you want */
}


    select {
        word-wrap: normal;
        width: 150px;
    }


    .dataTables tbody tr {
        min-height: 3px;
        /* or whatever height you need to make them all consistent */
    }

    .card {
        width:auto !important;
        top: 80px;

    }

    .alert {
        width: fit-content;
    }

    /* Apply text wrapping to the first column */
    #datatable td:first-child {
        white-space: normal;
        /* Enable text wrapping */
    }

    .text-wrap {
        white-space: normal;
    }

    .width-200 {
        width: 200px;
    }
</style>

<head>


    <!-- //these links for filters in home page -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



 


    <script>
        $(document).ready(function() {
            var myTable; // Declare a variable to store the DataTable object

    
            myTable = $('#datatable').DataTable({

                "columnDefs": [{
                    "targets": [1], // Assuming "Street" is the second column (index 1)
                    "render": function(data, type, row) {
                        if (type === 'display' && data != null && data.length > 20) {
                            return `<span class="ellipsis">${data.substr(0, 20)}...</span>
                            <span class="more-text" style="display: none;">${data}</span>
                            <button class="show-more">More</button>`;
                        }
                        return data;
                    }
                }],



                dom: 'lBfrtip',
                // 'responsive': true,

                processing: true,
                serverSide: true,
                autoWidth: false,
                recordsTotal: 50,
                ajax: {
                    url: "{{ route('users') }}",
                    data: function(d) {

                        d.db = $('#db').val();
                        d.search=$('#search').val();
                        d.client = $('#client').val();
                        d.country = $('#country').val();
                        d.technology = $('#technology').val();
                        d.speciality = $('#speciality').val();
                        d.designation = $('#designation').val();
                        d.emp_count = $('#emp_count').val();



                        var client_id = $('#client').val();

                        var county_id = $('#country').val();

                        var dba = $('#db').val();

                       var search= d.search=$('#search').val();


                        console.log(search);


                    }
                },

                columns: [{
                        title: 'Serial Number',
                        data: null,
                        "render": function (data, type, row, meta) {
                    // 'meta.row' is the row index, 'meta.settings._iDisplayStart' is the page start index
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
                    },
                    {
                        data: 'create_date',
                        title: 'Create Date'
                    },
                    {
                        data: 'country',
                        title: 'Country'
                    },
                    {
                        data: 'client_name',
                        title: 'Client Name'
                    },
                    {
                        data: 'contact_source',
                        title: 'Contact Source'
                    },
                    {
                        data: 'database_creator_name',
                        title: 'DataBase Creator Name'
                    },
                    {
                        data: 'technology',
                        title: 'Technology'
                    },
                    {
                        data: 'client_speciality',
                        title: 'Client Speciality',
                        "width": "2px",
                        render: function(data, type, row) {
                            if (type === 'display' && data != null && data.length > 10) {
                                return `<span class="ellipsis">${data.substr(0, 10)}...</span>
                            <span class="more-text" style="display: none;">${data}</span>
                            <a class="show-more">More</a>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'street',
                        title: 'Street',
                        "width": "2px",
                        render: function(data, type, row) {
                            if (type === 'display' && data != null && data.length > 10) {
                                return `<span class="ellipsis">${data.substr(0, 10)}...</span>
                            <span class="more-text" style="display: none;">${data}</span>
                            <a class="show-more">More</a>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'city',
                        title: 'City'
                    },
                    {
                        data: 'zip_code',
                        title: 'Zip Code'
                    },
                    {
                        data: 'first_name',
                        title: 'First Name'
                    },
                    {
                        data: 'website',
                        title: 'Website'
                    },
                    {
                        data: 'last_name',
                        title: 'Last Name'
                    },
                    {
                        data: 'designation',
                        title: 'Designation'
                    },
                    {
                        data: 'email',
                        title: 'Email'
                    },

                    {
                        data: 'email_response_1',
                        title: 'Email Response 1',
                        "width": "2px",
                        render: function(data, type, row) {
                            if (type === 'display' && data != null && data.length > 10) {
                                return `<span class="ellipsis">${data.substr(0, 10)}...</span>
                            <span class="more-text" style="display: none;">${data}</span>
                            <a class="show-more">More</a>`;
                            }
                            return data;
                        }
                    },

                    {
                        data: 'email_response_2',
                        title: 'Email Response 2',
                        "width": "2px",
                        render: function(data, type, row) {
                            if (type === 'display' && data != null && data.length > 10) {
                                return `<span class="ellipsis">${data.substr(0, 10)}...</span>
                            <span class="more-text" style="display: none;">${data}</span>
                            <a class="show-more">More</a>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'rating',
                        title: 'Rating'
                    },

                    {
                        data: 'followup',
                        title: 'Followup'
                    },
                    {
                        data: 'linkedin_link',
                        title: 'Linked In Link',
                        "width": "2px",
                        render: function(data, type, row) {
                            if (type === 'display' && data != null && data.length > 10) {
                                return `<span class="ellipsis">${data.substr(0, 10)}...</span>
                            <span class="more-text" style="display: none;">${data}</span>
                            <a class="show-more">More</a>`;
                            }
                            return data;
                        }
                    },

                    {
                        data: 'employee_count',
                        title: 'Employee Count'
                    },

                    {
                        mData: '',
                        render: (data, type, row) => {
                            return `
            <a class="btn btn-primary" href='user/edit/?id=${row.id}'>Edit</a>
        `;
                        }
                    }


                ],

            });



            // myTable.buttons().disable();



            // Array of specific headers you want to target
            var specificHeaders = ['Industry', 'State', 'Country', 'Client Name'];

            myTable.columns().every(function() {
                var column = this;
                var columnIndex = column.index();
                var columnHeader = $(column.header()).text().trim(); // Get the header text

                // Check if the current header matches one of the specific headers
                // if (specificHeaders.includes(columnHeader)) {
                //     var input = $('<input style="width:100px;" type="text" placeholder="Search..."/>')
                //         .appendTo($(column.header()))
                //         .on('keyup change', function() {
                //             column.search(this.value).draw();
                //             myTable.buttons().enable();
                //         });
                // }
            });


            $('#datatable').on('click', '.show-more', function() {
                var $row = $(this).closest('tr');
                var $moreText = $row.find('.more-text');
                var $ellipsis = $row.find('.ellipsis');

                $ellipsis.hide();
                $moreText.show();
                $(this).text('Less').removeClass('show-more').addClass('show-less');
            });

            $('#datatable').on('click', '.show-less', function() {
                var $row = $(this).closest('tr');
                var $moreText = $row.find('.more-text');
                var $ellipsis = $row.find('.ellipsis');

                $moreText.hide();
                $ellipsis.show();
                $(this).text('More').removeClass('show-less').addClass('show-more');
            });

            $('#search-btn').on('click', function(e) {
                e.preventDefault(); // Prevent the default form submission behavior
                myTable.ajax.reload();
            });


            $('#searchButton').on('click', function(e) {
                e.preventDefault(); // Prevent the default form submission behavior

                myTable.ajax.reload();

            });





        });
    </script>


<script>
    // Wait for the document to be ready
    $(document).ready(function() {
      // Attach a click event handler to the search button
      $("#MainClearBtn").click(function(e) {
        e.preventDefault();
        var inputData = $('#search').val();
        $('#search').val('');

      });
    });
  </script>


<script>
    // Wait for the document to be ready
    $(document).ready(function() {
      // Attach a click event handler to the search button
      $("#Reset").click(function(e) {
        alert();
        e.preventDefault();
        $('#form')[0].reset();
      });
    });
  </script>



<script>
        function resetSelect() {
            alert();
            // Get the select element by its id
            var selectElement = document.getElementById('country');

            // Set the selectedIndex to 0 to reset to the first option
            selectElement.val().reset();
        }
    </script>



    <script>
        $(document).ready(function() {
            // Set default selected value
            //   var defaultCountry = 'all';

            // Set the default value in the dropdown
            var my = $('#country').val();



            if (typeof my !== 'undefined') {

                var my = $('#country').val();

                $('#country').change();


                var url = "{{ route('all-clients', ['id' => 'id']) }}";
                url = url.replace('id', my);

                // Make an AJAX request to retrieve client names based on the selected country
                $.ajax({
                    url: url, // Replace with your server-side script
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Update the client list with the retrieved data
                        $('#client').html(displayClientNames(data.clientNames));
                    },
                    error: function(error) {
                        console.error('Error fetching client names:', error);
                    }
                });

                $('#country').change(function() {
                    // Get the selected country value
                    var selectedCountry = $(this).val();

                    var url = "{{ route('all-clients', ['id' => 'id']) }}";
                    url = url.replace('id', selectedCountry);

                    // Make an AJAX request to retrieve client names based on the selected country
                    $.ajax({
                        url: url, // Replace with your server-side script
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            // Update the client list with the retrieved data
                            $('#client').html(displayClientNames(data.clientNames));
                        },
                        error: function(error) {
                            console.error('Error fetching client names:', error);
                        }
                    });
                });




                function displayClientNames(clientNames) {
                    var html = '<select id="client" class="client"> <option>All</option>';

                    $.each(clientNames, function(index, clientName) {
                        html += '<option>' + clientName + '</option>';
                    });
                    html += '</select>';
                    return html;
                }

            } else {

                // Listen for changes in the country dropdown
                $('#country').change(function() {
                    // Get the selected country value
                    var selectedCountry = $(this).val();

                    var url = "{{ route('all-clients', ['id' => 'id']) }}";
                    url = url.replace('id', selectedCountry);

                    // Make an AJAX request to retrieve client names based on the selected country
                    $.ajax({
                        url: url, // Replace with your server-side script
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            // Update the client list with the retrieved data
                            $('#client').html(displayClientNames(data.clientNames));
                        },
                        error: function(error) {
                            console.error('Error fetching client names:', error);
                        }
                    });
                });

                function displayClientNames(clientNames) {
                    var html = '<select id="client" class="client">';

                    $.each(clientNames, function(index, clientName) {
                        html += '<option>' + clientName + '</option>';
                    });
                    html += '</select>';
                    return html;
                }

            }

            // Trigger the change event to make the AJAX request


        });
    </script>




</head>

<div class="container">




    @if(session('success'))



    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ session('success') }}
    </div>
    @endif




    <div class="container">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Search</h5>
                <div class="input-group mb-3">
                    <input type="text"  name="search" id="search" class="form-control" placeholder="Search Data Here..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-success" id="searchButton" type="button">Search</button>
                        <button class="btn btn-warning" id="MainClearBtn" type="button">Clear</button>

                    </div>
                </div>


            </div>

        </div>




        <div class="card">


            <div class="card-body">

                <h5 class="card-title">BD Users Data</h5>

                <form id="form">
                    <label for="country"> Country:</label>
                    <select id="country" name="country" class="country">
                        <option value="All">All</option>
                        @foreach($countries as $code => $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>



                    <label for="client">Client Name:</label>
                    <select id="client" name="client" class="client" style="width:auto">

                        <option value="">All Client Names</option>


                    </select>


                    <label for="db">DB Creator Name:</label>
                    <select id="db" name="database_creator_name" class="db">

                        <option value="All">All</option>

                        @foreach($dba_names as $code => $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach

                    </select>


                
                            <label for="technology"> Technology:</label>
                            <select id="technology" name="technology" class="technology">
                                <option value="All">All</option>
                                @foreach($technology as $code => $name)
                                <option value="{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </select>
            




                    <div style="display: flex; align-items: center;">
                        <div style="margin-right: 10px;">
                            <label for="technology"> Speciality:</label>
                            <select id="speciality" name="speciality" class="speciality">
                                <option value="All">All</option>
                                @foreach($client_speciality as $code => $name)
                                <option value="{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </select>
                           
                        </div>

    
                        <div>
                        <label for="technology"> Designation:</label>
                            <input type="text" name="designation" id="designation" >
                        </div>


                        <div>
                            <label for="country"> Emp Count:</label>
                            <input type="text" id="emp_count" name ="emp_count">
                            
                        </div>


                    </div>
                    <button id="search-btn" class="btn btn-primary">Search</button>


                    <!-- <button class="btn btn-warning" onclick="resetSelect()" type="button">Reset</button> -->








                </form>




                <script>
                    $(document).ready(function() {
                        $('.country').select2();
                        $('.client').select2();
                        $('.db').select2();
                        $('.technology').select2();
                        $('.speciality').select2();

                    });
                </script>




                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th>Industry</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Client Name</th>

                            <th>Contact Source</th>
                            <th>Database Creator Name</th>
                            <th>Technology</th>
                            <th>Client Speciality</th>
                            <th>Street</th>
                            <th>City</th>
                            <th>Zip Code</th>
                            <th>Website</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Designation</th>
                            <th>Email</th>
                            <th>Email Response 1</th>
                            <th>Email Response 2</th>
                            <th>Rating</th>
                            <th>FollowUp</th>
                            <th>LinkedIn Link</th>
                            <th>Employee Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>


    </div>



</div>




@endsection