<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <!-- SideBar-Menu CSS -->


    <link href="{{ asset('dashboard/css/styles.css') }}" rel="stylesheet">

    <!-- //bootstap css cdn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Demo CSS -->
    <link href="{{ asset('dashboard/css/demo.css') }}" rel="stylesheet">


    <link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/fh-3.4.0/r-2.5.0/sc-2.2.0/sb-1.6.0/datatables.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/fh-3.4.0/r-2.5.0/sc-2.2.0/sb-1.6.0/datatables.js"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".hamburger .hamburger__inner").click(function() {
                $(".wrapper").toggleClass("active")
            })

            $(".top_navbar .fas").click(function() {
                $(".profile_dd").toggleClass("active");
            });
        })
    </script>

    <script>
        $(document).ready(function() {
            var myTable; // Declare a variable to store the DataTable object


            myTable = $('#dtHorizontalExample').DataTable({
                "scrollX": true,


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
                        d.search = $('#search').val();
                        d.client = $('#client').val();
                        d.country = $('#country').val();
                        d.technology = $('#technology').val();
                        d.speciality = $('#speciality').val();
                        d.designation = $('#designation').val();
                        d.emp_count = $('#emp_count').val();



                        var client_id = $('#client').val();

                        var county_id = $('#country').val();

                        var dba = $('#db').val();

                        var search = d.search = $('#search').val();


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

    <style>
        .dtHorizontalExample td {
            white-space: nowrap;
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


        .dtHorizontalExample tbody tr {
            min-height: 3px;
            /* or whatever height you need to make them all consistent */
        }

        .card {
            width: auto !important;
            top: 80px;

        }

        .alert {
            width: fit-content;
        }

        /* Apply text wrapping to the first column */
        #dtHorizontalExample td:first-child {
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
</head>

<div class="wrapper">
    <div class="top_navbar">
        <div class="hamburger">
            <div class="hamburger__inner">
                <div class="one"></div>
                <div class="two"></div>
                <div class="three"></div>
            </div>
        </div>
        <div class="menu">
            <div class="logo">
                Coding Market
            </div>
            <div class="right_menu">
                <ul>
                    <li><i class="fas fa-user"></i>
                        <div class="profile_dd">
                            <div class="dd_item">Profile</div>
                            <div class="dd_item">Change Password</div>
                            <div class="dd_item" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main_container">
        <div class="sidebar">
            <div class="sidebar__inner">
                <div class="profile">
                    <div class="img">
                        <img src="{{URL::asset('dashboard/img/pic.png')}}" alt="profile_pic">

                    </div>
                    <div class="profile_info">
                        <p>Welcome</p>
                        <p class="profile_name">Alex John</p>
                    </div>
                </div>
                <ul>
                    <li>
                        <a href="#" class="active">
                            <span class="icon"><i class="fas fa-dice-d6"></i></span>
                            <span class="title">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon"><i class="fab fa-delicious"></i></span>
                            <span class="title">Forms</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon"><i class="fab fa-elementor"></i></span>
                            <span class="title">UI Elements</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon"><i class="fas fa-chart-pie"></i></span>
                            <span class="title">Charts</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon"><i class="fas fa-border-all"></i></span>
                            <span class="title">Tables</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container">


            <div class="item">
                <div class="input-group mb-3">
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search Data Here..." aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-success" id="searchButton" type="button">Search</button>
                        <button class="btn btn-warning" id="MainClearBtn" type="button">Clear</button>

                    </div>
                </div>

            </div>





            <div class="item">

                <h6>Bd Users Data</h6>
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

            </div>

            <div class="item">

            <table id="dtHorizontalExample" class="table table-striped table-bordered table-sm" cellspacing="0"
  width="100%">
  <thead>
    <tr>
      <th>First name</th>
      <th>Last name</th>
      <th>Position</th>
      <th>Office</th>
      <th>Age</th>
      <th>Start date</th>
      <th>Salary</th>
      <th>Extn.</th>
      <th>E-mail</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Tiger</td>
      <td>Nixon</td>
      <td>System Architect</td>
      <td>Edinburgh</td>
      <td>61</td>
      <td>2011/04/25</td>
      <td>$320,800</td>
      <td>5421</td>
      <td>t.nixon@datatables.net</td>
    </tr>
    <tr>
      <td>Garrett</td>
      <td>Winters</td>
      <td>Accountant</td>
      <td>Tokyo</td>
      <td>63</td>
      <td>2011/07/25</td>
      <td>$170,750</td>
      <td>8422</td>
      <td>g.winters@datatables.net</td>
    </tr>
    <tr>
      <td>Ashton</td>
      <td>Cox</td>
      <td>Junior Technical Author</td>
      <td>San Francisco</td>
      <td>66</td>
      <td>2009/01/12</td>
      <td>$86,000</td>
      <td>1562</td>
      <td>a.cox@datatables.net</td>
    </tr>
    <tr>
      <td>Cedric</td>
      <td>Kelly</td>
      <td>Senior Javascript Developer</td>
      <td>Edinburgh</td>
      <td>22</td>
      <td>2012/03/29</td>
      <td>$433,060</td>
      <td>6224</td>
      <td>c.kelly@datatables.net</td>
    </tr>
  </tbody>
</table>
                
                
            </div>

            

            











        </div>

        <footer class="credit">Develped By : <a href="https://appitsoftware.com/" rel="nofollow" target="_blank"> Appit Software </a></footer>