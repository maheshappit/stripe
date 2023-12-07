@extends('layouts.dashboard') 
@section('dashboard-content')


<script>
    $(document).ready(function (e) {
        
        $('#updateButton').on('click', function () {


            
        var updateId = document.getElementById('userid').value;

            var formData = new FormData($("#myForm")[0]);

        // Add CSRF token to the form data
        formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: '{{ route('user.update', ['id' => '']) }}' + updateId,
                type: 'POST',
                data: formData,
            processData: false,
            contentType: false,
            success: function(response) {

                if(response.status_code=='200'){
                    console.log(response.message);
                    toastr.success(response.message);

                }
                // Handle success
            },
            error: function(error) {
                // Handle error
                console.error(error);
            }
            });
        });
    });
</script>

<div class="item">
        <div class="col-md-12">
                <div class="card-header">{{ __('Update Conference') }}</div>
                <br>

                    <form id="myForm">
                        @csrf

                        <input type="text"  hidden value="{{$user->id}}" id="userid"  >

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$user->name}}"   autofocus>

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
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email}}"  autocomplete="email" readonly>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="row mb-3">
                            <label for="country" class="col-md-4 col-form-label text-md-end">{{ __('Country') }}</label>

                            <div class="col-md-6">
                                <input id="country" type="text" class="form-control @error('country') is-invalid @enderror" name="country"  value="{{$user->country}}" >

                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                       


                        <div class="row mb-3">
                            <label for="conference" class="col-md-4 col-form-label text-md-end">{{ __('Conference') }}</label>

                            <div class="col-md-6">
                                <input id="conference" type="text" class="form-control @error('conference') is-invalid @enderror" name="conference"  value="{{$user->conference}}" >

                                @error('conference')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <input type="text" hidden name="article" value="{{$user->article}}">


                        <div class="row mb-3">
                            <label for="country" class="col-md-4 col-form-label text-md-end">{{ __('Client Status') }}</label>

                            <div class="col-md-6">

                            <select class="custom-select" name="client_status">
                                <option value="">--select--</option>
                                <option value="positive" {{ $user->client_status == 'positive' ? 'selected' : '' }}>Positive</option>
                                <option value="negative" {{ $user->client_status == 'negative' ? 'selected' : '' }}>Negative</option>
                                <option value="unsubscribed" {{ $user->client_status == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                            </select>

                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <form id="commentform">
                        <div class="row mb-3">
                            <label for="country" class="col-md-4 col-form-label text-md-end">{{ __('Feed back Message') }}</label>

                            <div class="col-md-6">

                           <textarea name="comment" class="col-md-12" >{{$user->comment}}</textarea>

                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        </form>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button id="updateButton"class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
        </div>
</div>
@endsection
