<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <!-- SideBar-Menu CSS -->

    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


    <!-- //bootstap css cdn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Demo CSS -->

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>

    <link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/fh-3.4.0/r-2.5.0/sc-2.2.0/sb-1.6.0/datatables.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/fh-3.4.0/r-2.5.0/sc-2.2.0/sb-1.6.0/datatables.js"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>

    <style>

table.dataTable > tbody > tr {
    /* display: inline-block; */
    white-space: nowrap; /* Prevent line breaks within the row */
    margin-right: 10px;
    height: fit-content; /* Add spacing between rows if necessary */
}
    
.toast-message{
            color:black
        }

</style>


<script>
    $(document).ready(function(){
      $("#toggleCheckbox").change(function(){
        if(this.checked){
          $("#hiddenButton").show();
        } else {
          $("#hiddenButton").hide();
        }
      });
    });
  </script>


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

                
                "select": {
            style: 'multi',
            selector: 'td:first-child input[type="checkbox"]'
        },



                "columnDefs": [{
                    "targets": [4], // Assuming "Street" is the second column (index 1)
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
                buttons: [
        'excel',
        'selectNone'
    ],
    
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
                        d.conference = $('#conference').val();
                        d.country = $('#country').val();
                       d.article=$('#article').val();
                       d.user=$('#user').val();
                       d.user_created_at=$('#user_created_at').val();
                       d.user_updated_at=$('#user_updated_at').val();
                       d.email_status=$('#email_status').val();


                       


                        var conference_id = $('#conference').val();

                        var county_id = $('#country').val();

                        var dba = $('#db').val();

                        var search = d.search = $('#search').val();


                        console.log(search);


                    }
                },

                columns: [

                    {
                title: '', // Empty title for the checkbox column
                data: null,
                orderable: false,
                searchable: false,
                defaultContent: '<input type="checkbox" class="checkbox"/>'
            },
                    {
                        title: 'Serial Number',
                        data: 'id',
                        "render": function (data, type, row, meta) {
                    // 'meta.row' is the row index, 'meta.settings._iDisplayStart' is the page start index
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
                    },

                    {
                        title:'Name',
                        data:'name'

                    },
                    {
                        title:'Email',
                        data:'email'

                    },


                    {
                        title:'Email Status',
                        data:'email_sent_status'

                    },

                    {
                        title:'Email Sent Date',
                        data:'email_sent_date'

                    },
                    
                    
                    
                    {
                        title: 'Country',
                        data: 'country'

                    },

                    {
                        title:'Article',
                        data:'article'

                    },{
                        title:'Conference',
                        data:'conference'

                    },
                    {
                        title:'Posted By',
                        data:'posted_by'
                        
                    },
                    {
                        title:'Created Date',
                        data:'user_created_at'

                    },
                    {
                        title:'Updated Date',
                        data:'user_updated_at'
                    },
                    

                   
                    
            
                    {
                        title:'Action',

                        mData: '',
                        render: (data, type, row) => {
                            return `
            <a class="btn btn-primary" href='{{ route('user.edit') }}/?id=${row.id}'>Edit</a>
        `;
                        }
                    }


                ],

            });

            $('.btn.btn-secondary.buttons-excel.buttons-html5').on('click', function() {
                // Trigger the Excel export

                var columnNameToSearch = 'Email';

                var columnIndex = myTable.column(':contains(' + columnNameToSearch + ')').index();
                console.log(columnIndex);

                var allData = myTable.rows().data().toArray();

                var emails = allData.map(function(row) {
                    return row['email'];
                });

                
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
        url: '{{route('download.email')}}',
        method: 'POST', // or 'GET' depending on your server-side implementation
        data: {
            _token: csrfToken, // Include the CSRF token in your data
            emails: emails
        },        success: function(response) {
            // Handle the success response
            console.log(response);
        },
        error: function(error) {
            // Handle the error
            console.error(error);
        }
    });



            });



            // myTable.buttons().disable();



            // Array of specific headers you want to target
            var specificHeaders = ['Industry', 'State', 'Country', 'conference Name'];

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


            $('#dtHorizontalExample').on('click', '.show-more', function() {
                var $row = $(this).closest('tr');
                var $moreText = $row.find('.more-text');
                var $ellipsis = $row.find('.ellipsis');

                $ellipsis.hide();
                $moreText.show();
                $(this).text('Less').removeClass('show-more').addClass('show-less');
            });

            $('#dtHorizontalExample').on('click', '.show-less', function() {
                var $row = $(this).closest('tr');
                var $moreText = $row.find('.more-text');
                var $ellipsis = $row.find('.ellipsis');

                $moreText.hide();
                $ellipsis.show();
                $(this).text('More').removeClass('show-less').addClass('show-more');
            });

            $('#search-btn').on('click', function(e) {

                var selectedCountryId=$('#conference').val();


                if(selectedCountryId === 'All'){
                $('#toggleCheckbox').prop('disabled', true);

                $("#hiddenButton").hide();

            }else{
                $('#toggleCheckbox').prop('disabled', false);

            }

                console.log(name);

                e.preventDefault(); // Prevent the default form submission behavior
                myTable.ajax.reload();
            });


            $('#myTable').on('length.dt', function (e, settings, len) {
      // Log the selected number of entries to the console
      console.log('Show entries changed to:', len);
    });

//         $('#toggleCheckbox').on('change', function() {


//             var tr = $(this).closest('tr');
//         var isSelected = this.checked;

//         // Toggle the selected class on the row
//         tr.toggleClass('selected', isSelected);
//             myTable.rows().nodes().to$().find('.checkbox').prop('checked', isSelected);

// // Toggle the selected class on all rows
// myTable.rows().nodes().to$().toggleClass('selected', isSelected);

       
//     });

    // $('#dtHorizontalExample tbody').on('change', '.checkbox', function() {
    //     // Uncheck "Select All" if any individual checkbox is unchecked
    //     var tr = $(this).closest('tr');
    //     var isSelected = this.checked;

    //     // Toggle the selected class on the row
    //     tr.toggleClass('selected', isSelected);

    //     $("#hiddenButton").show();


        
    // });


     // Event listener for checkbox change
     $('#dtHorizontalExample tbody').on('change', '.checkbox', function() {
        var tr = $(this).closest('tr');
        var isSelected = this.checked;

        // Toggle the selected class on the row
        tr.toggleClass('selected', isSelected);

        // If "Select All" checkbox is clicked
        if ($(this).hasClass('select-all')) {
            // Update all checkboxes in the table
            myTable.rows().nodes().to$().find('.checkbox').prop('checked', isSelected);

            // Toggle the selected class on all rows
            myTable.rows().nodes().to$().toggleClass('selected', isSelected);
        } else {
            // Check the "Select All" checkbox if all checkboxes are checked
            var allCheckboxes = myTable.rows().nodes().to$().find('.checkbox');
            var allChecked = allCheckboxes.length === allCheckboxes.filter(':checked').length;
            myTable.rows().nodes().to$().find('.select-all').prop('checked', allChecked);
        }

        // Get the updated selected rows' data
        var selectedData = myTable.rows('.selected').data().toArray();
    });

    // Event listener for "Select All" checkbox change
        $('#toggleCheckbox').on('change', function() {
    var isSelected = this.checked;

        // Update all checkboxes in the table
        myTable.rows().nodes().to$().find('.checkbox').prop('checked', isSelected);

        // Toggle the selected class on all rows
        myTable.rows().nodes().to$().toggleClass('selected', isSelected);
    });
    
    


            $('#searchButton').on('click', function(e) {
                e.preventDefault(); // Prevent the default form submission behavior

                myTable.ajax.reload();

              

            });

            $('#hiddenButton').on('click', function() {
                

               
        var selectedData = myTable.rows('.selected').data().toArray();


       

        

    // Now, filter the selectedData based on the checkbox status
    console.log(selectedData);

                var conference_id =  $('#conference').val();

                var routeUrl = "{{ route('user.sent.emails') }}"; // Replace 'your.route' with the actual route name

                var csrfToken = $('meta[name="csrf-token"]').attr('content');


                var selectedData = myTable.rows('.selected').data().toArray();


                   $.ajax({
                    type: 'POST',
                    url: routeUrl, 
                    headers: {
          'X-CSRF-TOKEN': csrfToken
        },                    data: {
                    selectedData: selectedData,
                    conference:conference_id,


                    },
                    success: function(response,status,xhr) {
                    // Handle the response from the controller if needed


                     // Create a Blob from the response
                     var blob = new Blob([response], { type: 'text/csv' });
                        
                        // Create a link to download the file
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'downloaded_data.csv';

                        // Append the link to the body and trigger the download
                        document.body.appendChild(link);
                        link.click();

                        // Remove the link from the body
                        document.body.removeChild(link);



                        var statusMessage = xhr.getResponseHeader('X-Status-Message');
                        toastr.success(statusMessage);
                        
                        $("#toggleCheckbox").prop("checked", false);


                },
                error: function (xhr, status, error) {
                    // Handle errors here
                    console.error('Error downloading CSV file:', error);
                }
                     });
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


                var url = "{{ route('all-conferences', ['id' => 'id']) }}";
                url = url.replace('id', my);

                // Make an AJAX request to retrieve conference names based on the selected country
                $.ajax({
                    url: url, // Replace with your server-side script
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Update the conference list with the retrieved data
                        $('#conference').html(displayconferenceNames(data.conferenceNames));
                    },
                    error: function(error) {
                        console.error('Error fetching conference names:', error);
                    }
                });

                $('#country').change(function() {
                    // Get the selected country value
                    var selectedCountry = $(this).val();

                    var url = "{{ route('all-conferences', ['id' => 'id']) }}";
                    url = url.replace('id', selectedCountry);

                    // Make an AJAX request to retrieve conference names based on the selected country
                    $.ajax({
                        url: url, // Replace with your server-side script
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            // Update the conference list with the retrieved data
                            $('#conference').html(displayconferenceNames(data.conferenceNames));
                        },
                        error: function(error) {
                            console.error('Error fetching conference names:', error);
                        }
                    });
                });




                function displayconferenceNames(conferenceNames) {
                    var html = '<select id="conference" class="conference"> <option>All</option>';

                    $.each(conferenceNames, function(index, conferenceName) {
                        html += '<option>' + conferenceName + '</option>';
                    });
                    html += '</select>';
                    return html;
                }

            } else {

                // Listen for changes in the country dropdown
                $('#country').change(function() {
                    // Get the selected country value
                    var selectedCountry = $(this).val();

                    var url = "{{ route('all-conferences', ['id' => 'id']) }}";
                    url = url.replace('id', selectedCountry);

                    // Make an AJAX request to retrieve conference names based on the selected country
                    $.ajax({
                        url: url, // Replace with your server-side script
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            // Update the conference list with the retrieved data
                            $('#conference').html(displayconferenceNames(data.clientNames));
                        },
                        error: function(error) {
                            console.error('Error fetching conference names:', error);
                        }
                    });
                });

                function displayconferenceNames(conferenceNames) {
                    var html = '<select id="conference" class="conference">';

                    $.each(conferenceNames, function(index, conferenceName) {
                        html += '<option>' + conferenceName + '</option>';
                    });
                    html += '</select>';
                    return html;
                }

            }

            // Trigger the change event to make the AJAX request


        });


        $(document).ready(function () {
        $('#conference').on('change', function () {



            var selectedCountryId = $(this).val();
            var selectedCountryName = $(this).find('option:selected').text();




            if (selectedCountryId !== 'all_countries') {
                // Generate the URL using the Laravel route helper
                var url = "{{ route('all-articles', ['id' => 'id']) }}";
                url = url.replace('id', selectedCountryName);

                // Make an AJAX request to the generated URL
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json', // Expect JSON response
                    success: function (data) {

                        // Update the result div with the received client names
                        $('#article').html(displayClientNames(data.topicNames));
                    },
                    error: function (error) {
                        // Handle errors if necessary
                        console.log(error);
                    }
                });
            } else {
                // Handle the case when 'All' is selected
                $('#article').html('');
            }
        });

        function displayClientNames(topicNames) {
            var html = '<h2>Client Names:</h2><select><option value="All">All</option>';
            $.each(topicNames, function (index, clientName) {
                html += '<option>' + clientName + '</option>';
            });
            html += '</select>';
            return html;
        }
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
                STRIPE
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


                        <img src="{{URL::asset('img/pic.png')}}" alt="profile_pic">

                    </div>
                    <div class="profile_info">
                        <p>Welcome</p>
                        <p class="profile_name">

                            @if(Auth::user())
                            {{ Auth::user()->name }}
                            @endif

                        </p>
                    </div>
                </div>
                <ul>


                <li>
                        <a href="{{ route('home') }}" class="{{ ((Request::is('home')) ? 'active' : ' ') }}">
                            <span class="icon"><i class="fas fa-dice-d6"></i></span>
                            <span class="title">Home</span>
                        </a>

                </li>

             
              
                    <li>
                        <a href="{{ route('show.conferences') }}" class="{{ ((Request::is('show.conferences')) ? 'active' : ' ') }}">


                            <span class="icon"><i class="fas fa-dice-d6"></i></span>
                            <span class="title">Conferences</span>
                        </a>

                    </li>

                    <li>
                        <a href="{{route('show.upload')}}" class="{{ ((Request::is('show-upload-form')) ? 'active' : ' ') }}">
                            <span class="icon"><i class="fab fa-delicious"></i></span>
                            <span class="title">Upload</span>
                        </a>
                    </li>
                 
                    <li>
                        <a href="{{route('show.report')}}" class="{{ ((Request::is('show-report')) ? 'active' : ' ') }}">
                            <span class="icon"><i class="fas fa-chart-pie"></i></span>
                            <span class="title">Reports</span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>

        <div class="container">

            @yield('dashboard-content')

        </div>



    </div>