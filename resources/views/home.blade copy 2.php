// Your Blade View
<label for="country">Country:</label>
<select id="country" name="country" class="country">
    <option value="all_countries">All</option>
    @foreach($countries as $code => $name)
        <option value="{{ $code }}">{{ $name }}</option>
    @endforeach
</select>

<!-- Display the result from the AJAX call here -->
<div id="result">
    <!-- Client names will be displayed here -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function () {
        $('#country').on('change', function () {
            var selectedCountryId = $(this).val();
            var selectedCountryName = $(this).find('option:selected').text();


            if (selectedCountryId !== 'all_countries') {
                // Generate the URL using the Laravel route helper
                var url = "{{ route('all-clients', ['id' => 'id']) }}";
                url = url.replace('id', selectedCountryName);

                // Make an AJAX request to the generated URL
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json', // Expect JSON response
                    success: function (data) {
                        // Update the result div with the received client names
                        $('#result').html(displayClientNames(data.clientNames));
                    },
                    error: function (error) {
                        // Handle errors if necessary
                        console.log(error);
                    }
                });
            } else {
                // Handle the case when 'All' is selected
                $('#result').html('');
            }
        });

        function displayClientNames(clientNames) {
            var html = '<h2>Client Names:</h2><select>';
            $.each(clientNames, function (index, clientName) {
                html += '<option>' + clientName + '</option>';
            });
            html += '</select>';
            return html;
        }
    });
</script>
