@extends('layouts.adminapp')

@extends('layouts.adminupload')

@extends('layouts.add_users')




@section('content')

<style>
    td {
        white-space: nowrap;
        max-width: 100%;
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



                // dom: 'lBfrtip',
                // 'responsive': true,

                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: '{!! route('users') !!}',

                columns: [{
                        title: 'Serial Number',
                        data: 'id'
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
            <a class="btn btn-primary" href='/bd/admin/edit/?id=${row.id}'>Edit</a>
        `;
                        }
                    },
                    {
                        mData: '',
                        render: (data, type, row) => {
                            return `
            <a class="btn btn-danger" href='/bd/admin/delete/?id=${row.id}'>Delete</a>
        `;
                        }
                    }


                ],

            });



            myTable.buttons().disable();



            // Array of specific headers you want to target
            var specificHeaders = ['Industry', 'State', 'Country', 'Client Name'];

            myTable.columns().every(function() {
                var column = this;
                var columnIndex = column.index();
                var columnHeader = $(column.header()).text().trim(); // Get the header text

                // Check if the current header matches one of the specific headers
                if (specificHeaders.includes(columnHeader)) {
                    var input = $('<input style="width:100px;" type="text" placeholder="Search..."/>')
                        .appendTo($(column.header()))
                        .on('keyup change', function() {
                            column.search(this.value).draw();
                            myTable.buttons().enable();
                        });
                }
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

                <h5 class="card-title">BD Users Data</h5>

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
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>


    </div>



</div>




@endsection