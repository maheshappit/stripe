@extends('layouts.adminapp') 

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Update BD User') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.update',['id' => $user->id]) }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{$user->first_name}}"   autofocus>

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{$user->last_name}}"   autofocus>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email}}"  autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="create_date	" class="col-md-4 col-form-label text-md-end">{{ __('Created Date') }}</label>

                            <div class="col-md-6">
                                <input id="create_date" type="date" class="form-control @error('create_date	') is-invalid @enderror" value="{{$user->create_date}}" name="create_date"  >

                                @error('create_date	')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email_sent_date" class="col-md-4 col-form-label text-md-end">{{ __('Email Sent Date') }}</label>

                            <div class="col-md-6">
                                <input id="email_sent_date" type="date" class="form-control @error('email_sent_date') is-invalid @enderror" name="email_sent_date" value="{{$user->email_sent_date}}"  >

                                @error('email_sent_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="company_source" class="col-md-4 col-form-label text-md-end">{{ __('Company Source') }}</label>

                            <div class="col-md-6">
                                <input id="company_source" type="text" class="form-control @error('company_source') is-invalid @enderror" name="company_source" value="{{$user->company_source}}"  >

                                @error('company_source')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="contact_source" class="col-md-4 col-form-label text-md-end">{{ __('Contact Source') }}</label>

                            <div class="col-md-6">
                                <input id="contact_source" type="text" class="form-control @error('contact_source') is-invalid @enderror" name="contact_source"  value="{{$user->contact_source}}" >

                                @error('contact_source')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="database_creator_name" class="col-md-4 col-form-label text-md-end">{{ __('Database Creator Name') }}</label>

                            <div class="col-md-6">
                                <input id="database_creator_name" type="text" class="form-control @error('database_creator_name') is-invalid @enderror" name="database_creator_name" value="{{$user->database_creator_name}}"  >

                                @error('database_creator_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="technology" class="col-md-4 col-form-label text-md-end">{{ __('Technology') }}</label>

                            <div class="col-md-6">
                                <input id="technology" type="text" class="form-control @error('technology') is-invalid @enderror" name="technology" value="{{$user->technology}}"  >

                                @error('technology')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="client_speciality" class="col-md-4 col-form-label text-md-end">{{ __('Client Speciality') }}</label>

                            <div class="col-md-6">
                                <input id="client_speciality" type="text" class="form-control @error('client_speciality') is-invalid @enderror" name="client_speciality"  value="{{$user->client_speciality}}" >

                                @error('client_speciality')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="client_name" class="col-md-4 col-form-label text-md-end">{{ __('Client Name') }}</label>

                            <div class="col-md-6">
                                <input id="client_name" type="text" class="form-control @error('client_name') is-invalid @enderror" name="client_name" value="{{$user->client_name}}"  >

                                @error('client_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="street" class="col-md-4 col-form-label text-md-end">{{ __('Street') }}</label>

                            <div class="col-md-6">
                                <input id="street" type="text" class="form-control @error('street') is-invalid @enderror" name="street" value="{{$user->street}}"  >

                                @error('street')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="row mb-3">
                            <label for="city" class="col-md-4 col-form-label text-md-end">{{ __('City') }}</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{$user->city}}"  >

                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="state" class="col-md-4 col-form-label text-md-end">{{ __('State') }}</label>

                            <div class="col-md-6">
                                <input id="state" type="text" class="form-control @error('state') is-invalid @enderror" name="state"  value="{{$user->state}}" >

                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="zip_code" class="col-md-4 col-form-label text-md-end">{{ __('Zip Code') }}</label>

                            <div class="col-md-6">
                                <input id="zip_code" type="text" class="form-control @error('zip_code') is-invalid @enderror" name="zip_code" value="{{$user->zip_code}}" >

                                @error('zip_code')
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
                            <label for="website" class="col-md-4 col-form-label text-md-end">{{ __('Website') }}</label>

                            <div class="col-md-6">
                                <input id="website" type="text" class="form-control @error('website') is-invalid @enderror" name="website"  value="{{$user->website}}" >

                                @error('website')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="designation" class="col-md-4 col-form-label text-md-end">{{ __('Designation') }}</label>

                            <div class="col-md-6">
                                <input id="designation" type="text" class="form-control @error('designation') is-invalid @enderror" name="designation"  value="{{$user->designation}}" >

                                @error('website')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="email_response_1" class="col-md-4 col-form-label text-md-end">{{ __('Email Response 1') }}</label>

                            <div class="col-md-6">
                                <input id="email_response_1" type="text" class="form-control @error('email_response_1') is-invalid @enderror" name="email_response_1"  value="{{$user->email_response_1}}" >

                                @error('email_response_1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="email_response_2" class="col-md-4 col-form-label text-md-end">{{ __('Email Response 2') }}</label>

                            <div class="col-md-6">
                                <input id="email_response_2" type="text" class="form-control @error('email_response_2') is-invalid @enderror" name="email_response_2"  value="{{$user->email_response_2}}" >

                                @error('email_response_2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="rating" class="col-md-4 col-form-label text-md-end">{{ __('Rating') }}</label>

                            <div class="col-md-6">
                                <input id="rating" type="text" class="form-control @error('rating') is-invalid @enderror" name="rating"  value="{{$user->rating}}" >

                                @error('rating')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="row mb-3">
                            <label for="followup" class="col-md-4 col-form-label text-md-end">{{ __('FollowUp') }}</label>

                            <div class="col-md-6">
                                <input id="followup" type="text" class="form-control @error('followup') is-invalid @enderror" name="followup"  value="{{$user->followup}}" >

                                @error('followup')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>



                        <div class="row mb-3">
                            <label for="linkedin_link" class="col-md-4 col-form-label text-md-end">{{ __('Linked In Profile Link') }}</label>

                            <div class="col-md-6">
                                <input id="linkedin_link" type="text" class="form-control @error('linkedin_link') is-invalid @enderror" name="linkedin_link" value="{{$user->linkedin_link}}"  >

                                @error('linkedin_link')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="employee_count" class="col-md-4 col-form-label text-md-end">{{ __('Employee Count') }}</label>

                            <div class="col-md-6">
                                <input id="employee_count" type="text" class="form-control @error('employee_count') is-invalid @enderror" name="employee_count"  value="{{$user->employee_count}}" >

                                @error('employee_count')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
