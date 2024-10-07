<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <title>Digital Incubation</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}" />
    <style>
    .iti--show-selected-dial-code{width:100%;}
    .select2-container .select2-selection--single {
    height:37px !important;
    border: 1px solid #aaaaaa8f !important;
    }
    .select2-selection__rendered{
        line-height:31px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b{
        margin-top:2px !important;
    }
    .hide{display:none !important;}
    </style>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body class="bg-light">

    <div class="container mb-5">
    <div class="py-5 text-center ">
        <img class="d-block mx-auto mb-4" src="{{asset('assets/img/skillsrator_logo.png')}}" alt="" width="20%" height="72">
        <h2>Digital Incubator form</h2>
        <p class="lead">Unlock Your Financial Freedom with Skillsrator’s Digital Incubator!</p>
    </div>
    <form id="formSubmit" action="POST" autocomplete="off">
        @csrf
    <div class="row">
    <div class="col-md-9">

        <div class="form-row">
            <div class="form-group col-md-6">
            <label for="user_name">Name</label>
            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Name" required>
            </div>
            <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" required placeholder="example@gmail.com">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
            <label for="cnic_number">CNIC Number</label>
            <input type="text" id="cnic_number" class="form-control" name="cnic_number" maxlength="15" minlength="15" required>
            </div>
            <div class="form-group col-md-6">
            <p style="margin-bottom:7px;">WhatsApp Number</p>
            <input type="tel" id="whatsapp_number" name="whatsapp_number" maxlength="11"
                minlength="11" required data-placement="top" title="please write mobile/whatsapp number with country code"
                            class="form-control printSummary" autocomplete="disable">
            <input type="hidden" id="utm_source" value="{{@$_GET['utm_source']}}">
            <input type="hidden" id="utm_medium" value="{{@$_GET['utm_medium']}}">
            <input type="hidden" id="utm_campaign" value="{{@$_GET['utm_campaign']}}">
            <input type="hidden" id="utm_content" value="{{@$_GET['utm_content']}}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
            <label for="country">Country</label>
                <select id="country" class="form-control printSummary" name="country" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                            <option value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
            <label for="city">City</label>
            <select id="city" name="city" class="form-control printSummary" required>
                        <option value="">Select City</option>
            </select>
            </div>
        </div>
        <div class="form-group">
            <p style="margin-bottom:7px;">Gender</p>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="male">
                <label class="form-check-label" for="inlineRadio1">Male</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="female">
                <label class="form-check-label" for="inlineRadio2">Female</label>
            </div>
        </div>
        <h2>Course Selection</h2>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="course1">Course 1</label>
                <select id="course1" class="form-control courses" name="course1" required>
                            <option value="">Select Course</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="course2">Course 2</label>
                <select id="course2" class="form-control courses" name="course2" required>
                            <option value="">Select Course</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="course3">Course 3</label>
                <select id="course3" class="form-control courses" name="course3" required>
                            <option value="">Select Course</option>
                </select>
            </div>
        </div>
        <div class="form-group mt-4 mb-4">
                <div class="captcha">
                    <span>{!! App\Http\Controllers\CaptchaController::generateCaptcha(config('captcha.default.type')) !!}</span>
                    <button type="button" class="btn btn-danger reload" onclick="reloadCaptcha()" id="reload" data-toggle="tooltip" data-placement="top" title="Reload Captcha">↻</button>
                </div>
            </div>
        <div class="form-row">
            <div class="form-group mb-4 col-md-4">
                <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
                <small class="text-danger captcha_msg"></small>
            </div>
        </div>
        <button type="submit" class="btn btn-primary float-right submit_button ml-3">Submit</button>
        <button type="button" class="btn btn-danger float-right btnclear" >Clear</button>
    </div>
    <div class="col-md-3 mt-5 align-self-center">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-black">Payment Details</span>
            {{-- <span class="badge badge-secondary badge-pill">3</span> --}}
          </h4>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Course Amount</h6>
                {{-- <small class="text-muted">Brief description</small> --}}
              </div>
              <span class="text-muted">{{$amount}} PKR</span>
            </li>
            <li class="list-group-item d-flex justify-content-between bg-light hide" id="coupon-area">
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Total Amount</span>
              <strong id="totalamount">{{$amount}} PKR</strong>
            </li>
          </ul>

            <div class="input-group">
              <input type="text" class="form-control" id="coupon_code" name="coupon" placeholder="Coupon code">
              <input type="hidden" id="amount" name="amount" value="{{$amount}}">
              <div class="input-group-append">
                <button type="button" id="couponCode"  class="btn btn-secondary">Apply</button>
              </div>
            </div>
            <small class="text-danger" id="coupon_msg"></small>
        </div>
    </div>
    </form>

    </div>
    <br>
    <br>
    <br>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="{{asset('assets/js/intlTelInput.js')}}"></script>
    <script type="text/javascript">
     $('.btnclear').on('click',function(){
            $("#formSubmit").trigger('reset');
        });
        function reloadCaptcha(){
            $.ajax({
                    type: 'GET',
                    url: 'reload-captcha',
                    success: function(data) {
                        $(".captcha span").html(data.captcha);
                    }
                });
        }

      var input = document.querySelector("#whatsapp_number");
      window.intlTelInput(input, {
        initialCountry: "us",
        utilsScript: "{{asset('assets/js/utils.js')}}",
      });
    </script>
    <script>

    $('#formSubmit').on('submit',function(e){
            e.preventDefault()
            Swal.fire({
                title: 'Submitting form...',
                confirmButton: false,
                showLoaderOnConfirm: true,
                allowOutsideClick: false
            });
            $('.error_msg').html('')

            const formData = new FormData(this)
            $.ajaxSetup({
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            })
            $.ajax({
                contentType: false,
                cache: false,
                processData: false,
                url: "{{url('/store-digital-incubator')}}",
                method: 'POST',
                data: formData,
                beforeSend:function(){
                $(".submit_button").prop("disabled", true);
                },
                success:function(response){
                    if (response.error) {
                                    Swal.fire({
                                        title: 'Error!',
                                        confirmButtonColor: '#304767',
                                        text: response.error,
                                        icon: 'error',
                                        confirmButtonText: 'Ok',
                                    });
                                reloadCaptcha();
                                } else if (response.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        width: 800,
                                        text: response.msg,
                                        icon: 'success',
                                        confirmButtonColor: '#304767',
                                        confirmButtonText: 'OK',
                                    });
                                    location.reload();
                                }
                    $(".submit_button").prop("disabled", false);

                },
                error:function(error){
                    $.each(error.responseJSON.errors,function(key,value) {
                        $("#"+key+"_msg").html(value);
                    });
                        $(".submit_button").prop("disabled", false);
                },
            })

    })

    $('body').on('click','#couponCode', function(event) {
        var button = $(this);
        button.text('Processing...').prop('disabled',true);
        $.ajax({
          url: '{{url("incubator/coupon?type=digital-incubation")}}',
          type: 'POST',
          data: {_token: $('meta[name="csrf-token"]').attr('content'),coupon:$('#coupon_code').val()},
        })
        .done(function(result) {
          if (result.success == 'true'){
            button.text('Apply').prop('disabled',false);
            $('#coupon-area').html('');
            $('#coupon-area').removeClass('hide');
            $('#coupon-area').html(result.msg);
            $('#coupon_msg').text('');
            $('#totalamount').text('');
            var total = ($('#amount').val() - result.data);
            $('#totalamount').text(total +' PKR');
            $('#amount').val(total);
          }else{
            button.text('Apply').prop('disabled',false);
            $('#coupon_msg').text(result.message);
          }
        })
        .fail(function(result) {
            button.text('Apply').prop('disabled',false);
            $('#coupon_msg').text(result.responseJSON.message);
        })
    });

    $('#city').select2();
    $('#country').select2();
            $('#city').select2({
                        minimumInputLength: 3,
                        ajax: {
                            url: '{{ url("get-country-cities") }}',
                            dataType: 'json',
                            data: function (params) {
                            ultimaConsulta = params.term;
                            localidad = $('#country option:selected').val(); //this is the anotherParm
                            return {
                                term: params.term, // search term
                                country_id: localidad,
                            };
                        },

                        },
                    });

       $('body').on('change','#email', function(event) {
        $.ajax({
          url: "{{url('/get-courses')}}",
          type: 'POST',
          data: {_token: $('meta[name="csrf-token"]').attr('content'),id:$(this).val()},
        })
        .done(function(result) {
          if (result.status == '200'){
            $('.courses').html(result.data);
          }
        })
        .fail(function(result) {
        console.log(result);
        })
    });
    </script>

</body>

</html>
