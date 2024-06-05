<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <title>Co-working Space</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet"/>
</head>

<body class="antialiased">

    <div class="container">
        <div class="card">
            <div class="form">
                <div class="left-side col-sm-3 col-md-3">
                    <div class="left-heading">
                        <h3 class="text-center pt-2">Skillsrator</h3>
                        <hr>
                    </div>
                    <div class="steps-content">
                        <h3>Step <span class="step-number">1</span></h3>

                    </div>
                    <ul class="progress-bar1">
                        <li class="active">Personal Information</li>
                        <li>Incubator</li>
                        <li>Summary</li>

                    </ul>



                </div>
                <div class="right-side col-sm-12 col-md-9">
                    <div class="main active">

                        <div class="text">
                            <h2 class="text-center">Co-working Space Form</h2>
                            <hr>
                            <h3> Personal Information</h3>
                            <p style="color:black;">Enter your personal information.</p>
                        </div>
                        <div class="input-text">
                            <div class="input-div">
                                <input type="text" required id="user_name">
                                <span> Name</span>
                            </div>

                            <div class="input-div">
                                <input type="text" name="email" id="email" required>
                                <span>E-mail Address</span>
                            </div>
                        </div>
                        <div class="input-text">

                            <div class="input-div">
                                <input type="text" id="cnic_number" name="cnic_number" maxlength="15" minlength="15"
                                    required>
                                <span>CNIC Number</span>
                            </div>
                            <div class="input-div">
                                <input type="text" id="whatsapp_number" name="whatsapp_number" maxlength="11"
                                    minlength="11" required>
                                <input type="hidden" id="utm_source" value="{{@$_GET['utm_source']}}">
                                <input type="hidden" id="utm_medium" value="{{@$_GET['utm_medium']}}">
                                <input type="hidden" id="utm_campaign" value="{{@$_GET['utm_campaign']}}">
                                <input type="hidden" id="utm_content" value="{{@$_GET['utm_content']}}">
                                <span>WhatsApp Number</span>
                            </div>

                        </div>

                        <div class="input-text">



                            <div class="input-div">
                                <input type="text" id="facebook_profile" name="facebook_profile" required>
                                <span>FaceBook Profile </span>
                            </div>


                        </div>
                        <div class="input-text">



                            <div class="input-div">
                                <textarea required id="purpose"></textarea>
                                <span>Purpose / Reason</span>
                            </div>

                        </div>
                        <h5>Gender</h5>
                        <div class="input-text">

                            <div class="radio_div">
                                <input type="radio" id="gender" name="gender" value="male" required>
                                <label for="male">Male</label>
                            </div>

                            <div class="radio_div">
                                <input type="radio" id="gender" name="gender" value="female" required>
                                <label for="female">Female</label>
                            </div>

                        </div>

                        <div class="buttons">
                            <button class="next_button">Next Step</button>
                        </div>
                    </div>
                    <div class="step-number-content"></div>
                    <div class="main">
                        <div class="div-payment-form-data">
                            <div class="text">
                                <h2>Incubator Details</h2>
                                {{-- <p>Inform companies about your education life.</p> --}}
                            </div>


                            <div class="input-text">
                                <div class="input-div">
                                    <select required name="incubator_city" id="incubator_city">
                                        <option disabled selected value="">---Choose-Incubator-City---</option>
                                        <option value="Lahore">Lahore</option>
                                        <option value="Karachi">Karachi</option>
                                        <option value="Islamabad-Rawalpindi">Islamabad-Rawalpindi</option>
                                        <option value="Faisalabad">Faisalabad</option>
                                        <option value="Multan">Multan</option>

                                    </select>
                                </div>
                            </div>
                            <h5>Preferred Timing</h5>
                            <div class="input-text">

                                <div class="radio_div">


                                    <input type="radio" name="preferred_timing" value="Morning,8AM-4PM" required>
                                    <label for="preferred_timing">Morning (8AM - 4PM)</label>
                                </div>


                                <div class="radio_div">


                                    <input type="radio" name="preferred_timing" value="Evening,4PM-12AM" required>
                                    <label for="preferred_timing">Evening (4PM - 12AM)</label>
                                </div>


                                <div class="radio_div" id="night_div">
                                    <input type="radio" name="preferred_timing" id="night_time"
                                        value="Night,12AM-8AM" required>
                                    <label for="preferred_timing">Night (12AM - 8AM)</label>
                                </div>

                            </div>

                            <h5>Subscription Period</h5>
                            <div class="input-text">
                                <div class="input-div">
                                    <select name="subscription_period" id="subscription_period" required>
                                        <option disabled selected value="">---Choose-Subscription-Period---
                                        </option>
                                        <option value="1">1 month</option>
                                        <option value="2">2 month</option>
                                        <option value="3">3 months</option>
                                        {{-- <option value="4">4 months</option> --}}
                                        {{-- <option value="5">5 months</option> --}}
                                        <option value="6">6 months</option>
                                {{-- <option value="7">After 6 months - 40% off</option> --}}

                                    </select>
                                </div>
                            </div>

                            <h5>Coupon</h5>
                            <div class="input-text">
                                <div class="input-div">
                                    <input type="text" name="coupon_code" id="coupon_code">
                                    <span>Enter Coupon Code </span>
                                    <div id="coupon_msg" class="text-danger"></div>
                                </div>
                                <div class="input-div buttons">
                                    <button id="couponCode">Apply</button>
                                </div>
                            </div>

                        </div>

                        <div class="s_card">
                            <p class="s_card-title"> Payment Summary</p>
                            <h6 class="title-incubator"></h6>
                            <h6>Co-working Space Charges</h6>



                            <table id="top-table">
                                <tr>
                                    <th>
                                        SHIFT</th>
                                    <th>TIMING</th>
                                    <th>CHARGES</th>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <h6>Students</h6>
                        <div id="subscription-table">


                        </div>
                        <div class="buttons button_space">
                            <button class="back_button">Back</button>
                            <button class="next_button" id="showSummary">Next Step</button>
                        </div>
                    </div>




                    <div class="step-number-content"></div>
                    <div class="main">


                        <div class="s_card">
                            <p class="s_card-title">Program</p>
                            <p class="small-desc">
                                Co-working Space
                            </p>
                            <div class="go-corner">
                                <div class="go-arrow">1</div>
                            </div>
                        </div>

                        <div id="summary">

                        </div>
                        <div class="buttons button_space">
                            <button class="back_button">Back</button>
                            <button class="submit_button">Submit</button>
                        </div>
                    </div>
                    <div class="step-number-content"></div>
                    <div class="main">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>

                        <div class="text congrats">
                            <h2>Congratulations!</h2>
                            <p>Thanks Mr./Mrs. <span class="shown_name"></span> your information have been submitted
                                successfully for the future reference we will contact you soon.</p>
                        </div>
                    </div>
                    <div class="step-number-content"></div>





                </div>
            </div>
        </div>
    </div>





    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var next_click = document.querySelectorAll(".next_button");
        var main_form = document.querySelectorAll(".main");
        var step_list = document.querySelectorAll(".progress-bar1 li");
        var num = document.querySelector(".step-number");
        let formnumber = 0;

        next_click.forEach(function(next_click_form) {
            next_click_form.addEventListener('click', function() {

                var valid = validateform();
                if (!valid) {
                    return;
                }
                formnumber++;
                updateform();
                progress_forward();
                contentchange();
            });
        });

        var back_click = document.querySelectorAll(".back_button");
        back_click.forEach(function(back_click_form) {
            back_click_form.addEventListener('click', function() {
                formnumber--;
                updateform();
                progress_backward();
                contentchange();
            });
        });

        var username = document.querySelector("#user_name");
        var shownname = document.querySelector(".shown_name");

        var submit_click = document.querySelectorAll(".submit_button");
        submit_click.forEach(function(submit_click_form) {
            submit_click_form.addEventListener('click', function() {


                Swal.fire({
                    title: 'Submitting form...',
                    confirmButton: false,
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false
                });
                const userName = $('#user_name').val();
                const email = $('#email').val();
                const cnicNumber = $('#cnic_number').val();
                const whatsappNumber = $('#whatsapp_number').val();
                const facebookProfile = $('#facebook_profile').val();
                const preferred_timing = $('input[name="preferred_timing"]:checked').val().split(',');
                const shift = preferred_timing[0];
                const timing = preferred_timing[1];

                const gender = $('input[name="gender"]:checked').val();
                const incubator_city = $('#incubator_city').val();

                const subscriptionPeriod = $('#subscription_period').val();
                const couponCode = $('#coupon_code').val();
                const totalAmount = $('#totalAmount').text();
                const purpose = $('#purpose').val();
                const utm_source = $('#utm_source').val();
                const utm_medium = $('#utm_medium').text();
                const utm_campaign = $('#utm_campaign').val();
                const utm_content = $('#utm_content').val();
                $.ajax({
                    url: '{{ url("store-coworking-space") }}',
                    type: 'POST',
                    data: {
                        user_name: userName,
                        email: email,
                        cnic_number: cnicNumber,
                        whatsapp_number: whatsappNumber,
                        facebook_profile: facebookProfile,
                        gender: gender,
                        incubator_city: incubator_city,
                        timing: timing,
                        shift: shift,
                        subscription_period: subscriptionPeriod,
                        utm_source: utm_source,
                        utm_medium: utm_medium,
                        utm_campaign: utm_campaign,
                        utm_content: utm_content,
                    },
                    success: function(response) {
                        if (response.error) {
                            Swal.fire({
                                title: 'Error!',
                                confirmButtonColor: '#304767',
                                text: response.error,
                                icon: 'error',
                                confirmButtonText: 'Ok',
                            });

                        } else if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Co-working Space Form Submit successfully.',
                                icon: 'success',
                                confirmButtonColor: '#304767',
                                confirmButtonText: 'OK',
                            });
                            shownname.innerHTML = username.value;
                            formnumber++;
                            updateform();
                        }

                        // Swal.fire("Payment Summary");

                    },
                    error: function(xhr, status, error) {
                        // Handle any errors that occur during the request
                        Swal.fire({
                            confirmButtonColor: '#304767',
                            text: "Something went wrong"
                        });
                        console.error(xhr.responseText);
                    }
                });

            });
        });

        function updateform() {
            main_form.forEach(function(mainform_number) {
                mainform_number.classList.remove('active');
            });
            main_form[formnumber].classList.add('active');
        }

        function progress_forward() {
            num.innerHTML = formnumber + 1;
            step_list[formnumber].classList.add('active');
        }

        function progress_backward() {
            var form_num = formnumber + 1;
            step_list[form_num].classList.remove('active');
            num.innerHTML = form_num;
        }

        var step_num_content = document.querySelectorAll(".step-number-content");

        function contentchange() {
            step_num_content.forEach(function(content) {
                content.classList.remove('active');
                content.classList.add('d-none');
            });
            step_num_content[formnumber].classList.add('active');
        }

        function validateform() {
            var validate = true;
            var validate_inputs = document.querySelectorAll(".main.active input[required]");
            var validate_selects = document.querySelectorAll(".main.active select[required]");
            var validate_radios = document.querySelectorAll(".main.active input[type='radio'][required]");

            var empty_fields = [];
            var invalid_fields = [];

            validate_inputs.forEach(function(vaildate_input) {
                vaildate_input.classList.remove('warning');
                if (vaildate_input.value.trim() === '') {
                    validate = false;
                    vaildate_input.classList.add('warning');
                    empty_fields.push(vaildate_input.getAttribute('name') || vaildate_input.getAttribute('id'));
                    console.log("Value of " + (vaildate_input.getAttribute('name') || vaildate_input.getAttribute(
                        'id')) + " is empty");
                } else if (vaildate_input.id === 'whatsapp_number' && !validateNumber(vaildate_input.value)) {
                    validate = false;
                    vaildate_input.classList.add('warning');
                    invalid_fields.push(vaildate_input.getAttribute('name') || vaildate_input.getAttribute('id'));
                    console.log("Value of " + (vaildate_input.getAttribute('name') || vaildate_input.getAttribute(
                        'id')) + " is not a valid number");
                } else {
                    console.log("Value of " + (vaildate_input.getAttribute('name') || vaildate_input.getAttribute(
                        'id')) + " is " + vaildate_input.value);
                }
            });

            validate_selects.forEach(function(validate_select) {
                validate_select.classList.remove('warning');
                if (validate_select.value.trim() === '') {
                    validate = false;
                    validate_select.classList.add('warning');
                    empty_fields.push(validate_select.getAttribute('name') || validate_select.getAttribute('id'));
                    console.log("Value of " + (validate_select.getAttribute('name') || validate_select.getAttribute(
                        'id')) + " is empty");
                } else {
                    console.log("Value of " + (validate_select.getAttribute('name') || validate_select.getAttribute(
                        'id')) + " is " + validate_select.value);
                }
            });

            var radioGroups = {};
            validate_radios.forEach(function(validate_radio) {
                validate_radio.classList.remove('warning');
                if (!radioGroups[validate_radio.name]) {
                    radioGroups[validate_radio.name] = false;
                }
                if (validate_radio.checked) {
                    radioGroups[validate_radio.name] = validate_radio.value;
                }
            });

            Object.keys(radioGroups).forEach(function(group) {
                if (!radioGroups[group]) {
                    validate = false;
                    console.log("No radio button checked in group " + group);
                } else {
                    console.log("Radio button group " + group + " is checked with value " + radioGroups[group]);

                }
            });

            if (empty_fields.length > 0 || invalid_fields.length > 0) {
                var error_message = '';
                if (empty_fields.length > 0) {
                    error_message += 'Please fill in all required fields:<br>' + empty_fields.join(', ');
                }
                if (invalid_fields.length > 0) {
                    if (error_message !== '') {
                        error_message += '<br>';
                    }
                    error_message += 'Please correct the following invalid fields:<br>' + invalid_fields.join(', ');
                }
                Swal.fire({
                    title: 'Warning!',
                    html: error_message,
                    icon: 'warning',
                    confirmButtonColor: '#304767',
                    confirmButtonText: 'OK'
                });
            } else {
                console.log("Validation successful");
            }

            return validate;
        }

        function validateNumber(number) {
            return /^\d{11}$/.test(number);
        }
    </script>

    <script type="text/javascript">
        const formElements = document.querySelectorAll(
            '.div-payment-form-data select, .div-payment-form-data input[type="text"]'
        );

        const tableRows = document.querySelectorAll('#top-table tr');
        const incubator_title = document.querySelector('.title-incubator');

        // Function to update the table with form data
        function updateTable() {
            const data = Array.from(formElements).reduce((acc, element) => {
                if (element.type === 'radio' && element.checked) {
                    if (!acc[element.name]) {
                        acc[element.name] = [];
                    }
                    if (Array.isArray(acc[element.name])) {
                        acc[element.name].push(element.value);
                    } else {
                        acc[element.name] = [acc[element.name], element.value];
                    }
                } else {
                    acc[element.name] = element.value;
                }
                return acc;
            }, {});

            // Update the table with the form data

            incubator_title.textContent = data.incubator_city;

            tableRows[1].children[0].textContent = "Night";
            tableRows[2].children[0].textContent = "Evening";
            tableRows[3].children[0].textContent = "Morning";

            tableRows[1].children[1].textContent = "(12AM - 8AM)";
            tableRows[2].children[1].textContent = "(4PM - 12AM)";

            tableRows[3].children[1].textContent = "(8AM - 4PM)";

            // tableRows[1].children[2].textContent = data.subscription_period;
            if (data.incubator_city == 'Faisalabad' || data.incubator_city == 'Multan') {


                tableRows[1].children[2].textContent = "11,000 PKR";
                tableRows[2].children[2].textContent = "11,000 PKR"; // You can calculate this based on the form data
                tableRows[3].children[2].textContent = "11,000 PKR"; // You can calculate this based on the form data
                // You can calculate this based on the form data
            }
            else if (data.incubator_city == 'Multan') {


                tableRows[1].children[2].textContent = "12,000 PKR";
                tableRows[2].children[2].textContent = "12,000 PKR"; // You can calculate this based on the form data
                tableRows[3].children[2].textContent = "12,000 PKR"; // You can calculate this based on the form data
                // You can calculate this based on the form data
            }
             else {

                tableRows[1].children[2].textContent = "18,000 PKR"; // You can calculate this based on the form data
                tableRows[2].children[2].textContent = "18,000 PKR"; // You can calculate this based on the form data
                tableRows[3].children[2].textContent = "18,000 PKR"; // You can calculate this based on the form data

            }

            if (data.incubator_city == 'Faisalabad' || data.incubator_city == 'Multan' || data.incubator_city == 'Islamabad-Rawalpindi') {
                document.getElementById('night_time').disabled = true;
                document.getElementById("night_div").style.display = "none";
            } else {
                document.getElementById('night_time').disabled = false;
                document.getElementById("night_div").style.display = "block";
            }
            // You can add more logic to calculate charges and total amount based on the form data
        }

        // Event listener to update the table on form field change
        formElements.forEach(element => {
            element.addEventListener('change', updateTable);
        });

        // Event listener for the Apply button to update the table
    </script>

    <script type=text/javascript>
        const subscriptionPeriodSelect = document.getElementById('subscription_period');

        // Add event listener for change event
        subscriptionPeriodSelect.addEventListener('change', function() {
            // Get the selected values of Preferred Timing and Incubator City
            const preferredTiming = document.querySelector('input[name="preferred_timing"]:checked').value;
            const incubatorCity = document.getElementById('incubator_city').value;

            const cityTimingArray = preferredTiming.split(',');

            // Traverse the table to find a match and retrieve charges
            const tableRows1 = document.getElementById('top-table').getElementsByTagName('tr');
            for (let i = 1; i < tableRows1.length; i++) { // Start from index 1 to skip header row
                const cells = tableRows1[i].getElementsByTagName('td');
                if (cells.length === 3) {
                    const city = cells[0].innerText.trim();
                    const timing = cells[1].innerText.trim();
                    if (city === cityTimingArray[0] && timing === cityTimingArray[1]) {
                        const charges = cells[2].innerText.trim(); // Retrieve charges
                        console.log('Charges:', charges);

                        break;
                    }
                }
            }
        });
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('#incubator_city, input[name="preferred_timing"]').on('change', function() {
                var city = $('#incubator_city').val();
                if (city === 'Multan') {
                    $('#subscription_period option:eq(4)').text('4 months - Multan Special - 25% off');
                    $('#subscription_period option:eq(5)').text('5 months - Multan Special - 30% off');
                } else {
                    $('#subscription_period option:eq(4)').text('4 months');
                    $('#subscription_period option:eq(5)').text('5 months');
                }
                $('#subscription_period').val('');
                $('#subscription_period').val('');
            });
            $('#subscription_period').change(function() {
                let selectedOption = $(this).val();
                let incubator_city = $('#incubator_city').val();

                let preferred_timing = $('input[name="preferred_timing"]:checked').val().split(',');

                let gender = $('input[name="gender"]:checked').val();


                if (!selectedOption || selectedOption.trim() === '') {
                    $(this).val('');
                    alert('Please select an option.');
                    $(this).focus();
                }

                if (!incubator_city || incubator_city.trim() === '') {
                    $('#incubator_city').val('');
                    alert('Please enter the incubator city.');
                    $('#incubator_city').focus();
                }

                if (!preferred_timing || preferred_timing[0].trim() === '' || preferred_timing[1].trim() ===
                    '') {
                    $('input[name="preferred_timing"]').prop('checked', false);
                    alert('Please select a preferred timing.');
                    $('input[name="preferred_timing"]').eq(0).focus();
                }

                calculate(selectedOption,incubator_city,gender,preferred_timing);
            });


            $(document).on('click', '#showSummary', function() {
                const userName = $('#user_name').val();
                const email = $('#email').val();
                const cnicNumber = $('#cnic_number').val();
                const whatsappNumber = $('#whatsapp_number').val();
                const facebookProfile = $('#facebook_profile').val();
                const preferred_timing = $('input[name="preferred_timing"]:checked').val().split(',');
                const shift = preferred_timing[0];
                const timing = preferred_timing[1];

                const gender = $('input[name="gender"]:checked').val();
                const incubator_city = $('#incubator_city').val();

                const subscriptionPeriod = $('#subscription_period').val();
                const couponCode = $('#coupon_code').val();
                const totalAmount = $('#totalAmount').text();


                $.ajax({
                    url: '{{ url('incubator/summary') }}',
                    type: 'POST',
                    data: {
                        user_name: userName,
                        email: email,
                        cnic_number: cnicNumber,
                        whatsapp_number: whatsappNumber,
                        facebook_profile: facebookProfile,
                        gender: gender,
                        incubator_city: incubator_city,
                        timing: timing,
                        shift: shift,
                        subscription_period: subscriptionPeriod,
                        coupon_code: couponCode,
                        totalAmount: totalAmount
                    },
                    success: function(response) {
                        $('#summary').html(response);
                        // Swal.fire("Payment Summary");

                    },
                    error: function(xhr, status, error) {
                        // Handle any errors that occur during the request
                        console.error(xhr.responseText);
                    }
                });
            });
        });

        function calculate(selectedOption,incubator_city,gender,preferred_timing,coupon = null){
            $.ajax({
                    url: '/coworking/calculate',
                    method: 'POST',
                    data: {
                        subscription_period: selectedOption,
                        incubator_city: incubator_city,
                        gender: gender,
                        shift: preferred_timing[0],
                        timing: preferred_timing[1],
                        coupon:coupon,
                    },
                    success: function(response) {
                        $('#subscription-table').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
        }


        $('body').on('click','#couponCode', function(event) {
        var button = $(this);
        button.text('Processing...').prop('disabled',true);
        $.ajax({
          url: 'coupon',
          type: 'POST',
          data: {_token: $('meta[name="csrf-token"]').attr('content'),coupon:$('#coupon_code').val()},
        })
        .done(function(result) {
          if (result.success == 'true'){
            button.text('Apply').prop('disabled',false);
            $('#coupon_msg').text('');
            let selectedOption = $('#subscription_period').val();
            let incubator_city = $('#incubator_city').val();
            let preferred_timing = $('input[name="preferred_timing"]:checked').val().split(',');
            let gender = $('input[name="gender"]:checked').val();
            let couponCode = $('#coupon_code').val()
            calculate(selectedOption,incubator_city,gender,preferred_timing,couponCode);
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

    </script>
</body>

</html>
