@extends('layout2')
@section('title','Register new Student | '.env('APP_NAME'))
@section('app_path','Register Today')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card table--no-card">
                <div class="card-header" style="background-color:#333333; color: white !important;">{{ __('Register with Extreme Commerce') }}</div>

                <div class="card-body">
                    @include('messages')
                    @include('errors')
                    	<form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Full Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date_of_birth" class="col-md-4 col-form-label text-md-right">{{ __('Date of Birth') }}</label>

                            <div class="col-md-6">
                                <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">{{ __('City') }}</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kids" class="col-md-4 col-form-label text-md-right">{{ __('Kids (if any)') }}</label>

                            <div class="col-md-6">
                                <input id="kids" type="number" class="form-control @error('kids') is-invalid @enderror" name="kids" value="{{ old('kids') ? old('kids') : '0' }}" min="0" max="20">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="whatsapp_number" class="col-md-4 col-form-label text-md-right">{{ __('Whatsapp Number (if different)') }}</label>

                            <div class="col-md-6">
                                <input id="whatsapp_number" type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" name="whatsapp_number" value="{{ old('whatsapp_number') }}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fb_profile_link" class="col-md-4 col-form-label text-md-right">{{ __('FB Profile Link') }}</label>

                            <div class="col-md-6">
                                <input id="fb_profile_link" type="text" class="form-control @error('fb_profile_link') is-invalid @enderror" name="fb_profile_link" value="{{ old('fb_profile_link') }}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="facebook_group_joining_date" class="col-md-4 col-form-label text-md-right">{{ __('FB Group Joining Date') }}</label>

                            <div class="col-md-6">
                                <input id="facebook_group_joining_date" type="date" class="form-control @error('facebook_group_joining_date') is-invalid @enderror" name="facebook_group_joining_date" value="{{ old('facebook_group_joining_date') }}" >
                            </div>
                        </div>

                        <div class="form-group row employer_name is-hidden">
                            <label for="employer_name" class="col-md-4 col-form-label text-md-right">{{ __('Employer Name') }}</label>

                            <div class="col-md-6">
                                <input id="employer_name" type="text" class="form-control @error('employer_name') is-invalid @enderror" name="employer_name" value="{{ old('employer_name') }}" >
                            </div>
                        </div>

                        <div class="form-group row business_nature is-hidden">
                            <label for="business_nature" class="col-md-4 col-form-label text-md-right">{{ __('Business Nature') }}</label>

                            <div class="col-md-6">
                                <input id="business_nature" type="text" class="form-control @error('business_nature') is-invalid @enderror" name="business_nature" value="{{ old('business_nature') }}" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="monthly_income" class="col-md-4 col-form-label text-md-right">{{ __('Income') }}</label>

                            <div class="col-md-6">
                                <input id="monthly_income" type="text" class="form-control @error('monthly_income') is-invalid @enderror" name="monthly_income" value="{{ old('monthly_income') }}" >
                                <small class="form-text text-muted">Write Rs 150k per month or Write $100k per annum</small>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4 col-form-label text-md-right">
                                <label for="cnic" class=" form-control-label">CNIC (Front)</label>
                            </div>
                            <div class="col-md-6">
                                <input type="file" name="cnic" class="form-control-file" value="{{ old('cnic') }}">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4 col-form-label text-md-right">
                                <label class="">Seminar Attended</label>
                            </div>
                            <div class="col-md-6">
                                <select name="seminar_attended" onchange="checkAttendant()"  class="select form-control">
                                    <option {{ old('seminar_attended') == 0 ? 'selected' : '' }} value="0">No</option>
                                    <option {{ old('seminar_attended') == 1 ? 'selected' : '' }} value="1">Yes</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group is-hidden seminar">
                            <div class="col-md-4 col-form-label text-md-right">
                                <label class="">Seminar Location</label>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control {{$errors->has('seminar_location') ? 'is-danger' : ''}}" type="text" placeholder="City Name" name="seminar_location" value="{{ old('seminar_location') }}">
                            </div>
                        </div>

                        <div class="row form-group is-hidden seminar">
                            <div class="col-md-4 col-form-label text-md-right">
                                <label class="">Seminar Date</label>
                            </div>
                            <div class="col-md-6">
                                <input  class="form-control {{$errors->has('seminar_date') ? 'is-danger' : ''}}" type="date" placeholder="" name="seminar_date" value="{{ old('seminar_date') }}">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4 col-form-label text-md-right">
                                <label class="">Meetup Attended</label>
                            </div>
                            <div class="col-md-6">
                                <select name="meetup_attended" onchange="checkAttendant()" class="select form-control">
                                    <option {{ old('meetup_attended') == 0 ? 'selected' : '' }} value="0">No</option>
                                    <option {{ old('meetup_attended') == 1 ? 'selected' : '' }} value="1">Yes</option>
                                </select>
                            </div>
                        </div>

                        <div class="row form-group is-hidden meetup">
                            <div class="col-md-4 col-form-label text-md-right">
                                <label class="">Meetup Location</label>
                            </div>
                            <div class="col-md-6">
                                <input  class="form-control {{$errors->has('meetup_location') ? 'is-danger' : ''}}" type="text" placeholder="City Name" name="meetup_location" value="{{ old('meetup_location') }}">
                            </div>
                        </div>

                        <div class="row form-group is-hidden meetup">
                            <div class="col-md-4 col-form-label text-md-right">
                                <label class="">Meetup Date</label>
                            </div>
                            <div class="col-md-6">
                                <input  class="form-control {{$errors->has('meetup_date') ? 'is-danger' : ''}}" type="date" placeholder="" name="meetup_date" value="{{ old('meetup_date') }}">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
