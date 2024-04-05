<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <title>Bitrix Lead Form</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap');

        * {
            padding: 0;
            margin: 0;
        }

        .container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;

        }

        .container .card {
            width: 1000px;
            background-color: #fff;
            position: relative;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            font-family: sans-serif;
            border-radius: 20px;
            /* Remove the fixed height */
            /* height: 570px; */
            /* Add padding for spacing */

            /* Add box-sizing to include padding in width and height */
            box-sizing: border-box;
        }


        .container .card .form {
            width: 100%;
            height: 100%;

            display: flex;
        }

        .container .card .left-side {

            background-color: #304767;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            padding: 20px 30px;
            box-sizing: border-box;
            /* Use flex-grow to make it take up remaining space */
            flex-grow: 1;
        }

        /*left-side-start*/
        .left-heading {
            color: #fff;

        }

        .steps-content {
            margin-top: 30px;
            color: #fff;
        }

        .steps-content p {
            font-size: 12px;
            margin-top: 15px;
        }

        .progress-bar1 {
            list-style: none !important;
            /*color:#fff;*/
            margin-top: 30px !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            counter-reset: container 0 !important;
        }

        .progress-bar1 li {
            position: relative !important;
            margin-left: 40px !important;
            margin-top: 50px !important;
            counter-increment: container 1 !important;
            color: #4f6581 !important;
        }

        .progress-bar1 li::before {
            content: counter(container) !important;
            line-height: 25px !important;
            text-align: center !important;
            position: absolute !important;
            height: 25px !important;
            width: 25px !important;
            border: 1px solid #4f6581 !important;
            border-radius: 50% !important;
            left: -40px !important;
            top: -5px !important;
            z-index: 10 !important;
            background-color: #304767 !important;


        }


        .progress-bar1 li::after {
            content: '' !important;
            position: absolute !important;
            height: 90px !important;
            width: 2px !important;
            background-color: #4f6581 !important;
            z-index: 1 !important;
            left: -27px !important;
            top: -70px !important;
        }


        .progress-bar1 li.active::after {
            background-color: #fff;

        }

        .progress-bar1 li:first-child:after {
            display: none;
        }

        /*.progress-bar li:last-child:after{*/
        /*  display:none;  */
        /*}*/
        .progress-bar1 li.active::before {
            color: #fff;
            border: 1px solid #fff;
        }

        .progress-bar1 li.active {
            color: #fff !important;
        }

        .d-none {
            display: none;
        }






















        /*left-side-end*/
        .container .card .right-side {

            background-color: #fff;
            height: 100%;
            border-radius: 20px;
        }

        /*right-side-start*/
        .main {
            display: none;
        }

        .active {
            display: block;
        }

        .main {
            padding: 10px;
        }

        .main small {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 2px;
            height: 30px;
            width: 30px;
            background-color: #ccc;
            border-radius: 50%;
            color: yellow;
            font-size: 19px;
        }

        .text {
            margin-top: 20px;
        }

        .congrats {
            text-align: center;
        }

        .text p {
            margin-top: 10px;
            font-size: 13px;
            font-weight: 700;
            color: #cbced4;
        }

        .input-text {
            margin: 20px 0;
            display: flex;
            gap: 20px;
        }

        .input-text .input-div {
            width: 100%;
            position: relative;

        }



        input[type="text"] {
            width: 100%;
            height: 40px;
            border: none;
            outline: 0;
            border-radius: 5px;
            border: 1px solid #cbced4;
            gap: 20px;
            box-sizing: border-box;
            padding: 0px 10px;
        }

        textarea {
            width: 100%;
            height: 80px;
            border: none;
            outline: 0;
            border-radius: 5px;
            border: 1px solid #cbced4;
            gap: 20px;
            box-sizing: border-box;
            padding: 0px 10px;
        }

        h5 {
            font-weight: 600;
        }

        h2 {
            font-weight: bold;
        }

        label {
            font-size: 13px !important;
        }

        select {
            font-size: 13px;
            width: 100%;
            height: 40px;
            border: none;
            outline: 0;
            border-radius: 5px;
            border: 1px solid #cbced4;
            gap: 20px;
            box-sizing: border-box;
            padding: 0px 10px;
        }

        .input-text .input-div span {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 14px;
            transition: all 0.5s;
        }

        .input-div input:focus~span,
        .input-div textarea:focus~span,
        .input-div input:valid~span {
            top: -15px;
            left: 6px;
            font-size: 10px;
            font-weight: 600;
        }

        .input-div span {
            top: -15px;
            left: 6px;
            font-size: 10px;
        }

        .buttons button {
            height: 40px;
            width: 100px;
            border: none;
            border-radius: 5px;
            background-color: #304767;
            font-size: 12px;
            color: #fff;
            cursor: pointer;
        }

        .button_space {
            display: flex;
            gap: 20px;
            margin: 30px;

        }

        .button_space button:nth-child(1) {
            background-color: #fff;
            color: #000;
            border: 1px solid#000;
        }

        .user_card {
            margin-top: 20px;
            margin-bottom: 40px;
            height: 200px;
            width: 100%;
            border: 1px solid #c7d3d9;
            border-radius: 10px;
            display: flex;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
        }

        .user_card span {
            height: 80px;
            width: 100%;
            background-color: #dfeeff;
        }

        .circle {
            position: absolute;
            top: 40px;
            left: 60px;
        }

        .circle span {
            height: 70px;
            width: 70px;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        .circle span img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .social {
            display: flex;
            position: absolute;
            top: 100px;
            right: 10px;
        }

        .social span {
            height: 30px;
            width: 30px;
            border-radius: 7px;
            background-color: #fff;
            border: 1px solid #cbd6dc;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: 10px;
            color: #cbd6dc;

        }

        .social span i {
            cursor: pointer;
        }

        .heart {
            color: red !important;
        }

        .share {
            color: red !important;
        }

        .user_name {
            position: absolute;
            top: 110px;
            margin: 10px;
            padding: 0 30px;
            display: flex;
            flex-direction: column;
            width: 100%;

        }

        .user_name h3 {
            color: #4c5b68;
        }

        .detail {
            /*margin-top:10px;*/
            display: flex;
            justify-content: space-between;
            margin-right: 50px;
        }

        .detail p {
            font-size: 12px;
            font-weight: 700;

        }

        .detail p a {
            text-decoration: none;
            color: blue;
        }






        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #7ac142;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #fff;
            stroke-miterlimit: 10;
            margin: 10% auto;
            box-shadow: inset 0px 0px 0px #304767;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scale {

            0%,
            100% {
                transform: none;
            }

            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 30px #7ac142;
            }
        }

        .warning {
            border: 1px solid red !important;
        }


        /*right-side-end*/
        @media (max-width:750px) {
            .container {
                height: scroll;


            }

            .container .card {
                max-width: 350px;
                height: auto !important;
                margin: 30px 0;
            }

            .container .card .right-side {
                width: 100%;

            }

            .input-text {
                display: block;
            }

            .input-text .input-div {
                margin-top: 20px;

            }

            .container .card .left-side {

                display: none;
            }
        }


        .radio_div {
            height: 40px;
            line-height: 40px;
            border: 1px solid #cbced4;
            border-radius: 5px;
            padding: 0 17px 10px;
            box-sizing: border-box;
            gap: 20px;
        }





        .s_card-title {
            color: #262626;
            font-size: 1.5em;
            line-height: normal;
            font-weight: 700;
            margin-bottom: 0.5em;
        }

        .small-desc {
            font-size: 1em;
            font-weight: 400;
            line-height: 1.5em;
            color: #452c2c;
        }

        .small-desc {
            font-size: 1em;
        }

        .go-corner {
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            width: 2em;
            height: 2em;
            overflow: hidden;
            top: 0;
            right: 0;
            background: linear-gradient(135deg, #6293c8, #384c6c);
            border-radius: 0 4px 0 32px;
        }

        .go-arrow {
            margin-top: -4px;
            margin-right: -4px;
            color: white;
            font-family: courier, sans;
        }

        .s_card {
            display: block;
            position: relative;
            max-width: 100%;
            max-height: 320px;
            background-color: #f2f8f9;
            border-radius: 10px;
            padding: 2em 1.2em;
            margin: 12px;
            text-decoration: none;
            z-index: 0;
            overflow: hidden;
            /* background: linear-gradient(to bottom, #c3e6ec, #a7d1d9); */
            background: #ededed75;
            font-family: Arial, Helvetica, sans-serif;
        }



        #top-table,
        #bottom-table {
            font-family: 'Poppins', sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #top-table td,
        #bottom-table td #top-table th,
        #bottom-table th {
            font-size: small;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            padding: 8px;
        }

        #top-table th,
        #bottom-table th {
            background-color: #ddd;
        }



        #top-table th,
        #bottom-table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;

            color: rgb(0, 0, 0);
        }


        input[type="radio"]:checked+label+.radio_div {
            border-color: blue;
        }
    </style>
</head>

<body class="antialiased">

    <div class="container">
        <div class="card">
            <div class="form">
                <div class="left-side col-sm-3 col-md-3">
                    <div class="left-heading">
                        <h3>Signup Form</h3>
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
                            <h2> Personal Information</h2>
                            <p>Enter your personal information.</p>
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
                                <textarea required></textarea>
                                <span>Purpose / Incubation Reason</span>
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
                                        <option>---Choose-Incubator-City---</option>
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


                                    <input type="radio" name="preferred_timing" value="Morning,8AM-4PM">
                                    <label for="preferred_timing">Morning (8AM - 4PM)</label>
                                </div>


                                <div class="radio_div">


                                    <input type="radio" name="preferred_timing" value="Evening,4PM-12AM">
                                    <label for="preferred_timing">Evening (4PM - 12AM)</label>
                                </div>


                                <div class="radio_div">
                                    <input type="radio" name="preferred_timing" value="Night,12AM-8AM">
                                    <label for="preferred_timing">Night (12AM - 8AM)</label>
                                </div>

                            </div>

                            <h5>Subscription Period</h5>
                            <div class="input-text">
                                <div class="input-div">
                                    <select name="subscription_period" id="subscription_period">
                                        <option>---Choose-Subscription-Period---</option>
                                        <option value="1">1 month</option>
                                        <option value="2">2 month</option>
                                        <option value="3">3 months - 10% off</option>
                                        <option value="6">6 months - 20% off</option>

                                    </select>
                                </div>
                            </div>

                            <h5>Coupon</h5>
                            <div class="input-text">
                                <div class="input-div">
                                    <input type="text" name="coupon_code" id="coupon_code">
                                    <span>Enter Coupon Code </span>

                                </div>
                                <div class="input-div buttons">
                                    <button id="applyButton">Apply</button>
                                </div>


                            </div>

                        </div>

                        <div class="s_card">
                            <p class="s_card-title"> Payment Summary</p>
                            <h6 class="title-incubator"></h6>
                            <h6>Incubator Charges</h6>



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
                                Incubator Only
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
                shownname.innerHTML = username.value;
                formnumber++;
                updateform();
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
            var validate_selects = document.querySelectorAll(".main.active select[required]")
            var empty_fields = [];
            validate_inputs.forEach(function(vaildate_input) {
                vaildate_input.classList.remove('warning');
                if (vaildate_input.value.trim() === '') {
                    validate = false;
                    vaildate_input.classList.add('warning');
                    empty_fields.push(vaildate_input.getAttribute('name') || vaildate_input.getAttribute('id'));
                }
            });
            validate_selects.forEach(function(validate_select) {
                validate_select.classList.remove('warning');
                if (validate_select.value.trim() === '') {
                    validate = false;
                    validate_select.classList.add('warning');
                    empty_fields.push(validate_select.getAttribute('name') || validate_select.getAttribute('id'));
                }
            });

            if (!validate) {
                Swal.fire({
                    title: 'Warning!',
                    html: 'Please fill in all required fields:<br>' + empty_fields.join(', '),
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            }

            return validate;
        }
        // Get all the form elements
    </script>

    <script type="text/javascript">
        const formElements = document.querySelectorAll(
            '.div-payment-form-data select, .div-payment-form-data input[type="text"]'
        );
        console.log(formElements);
        // Get the table rows where the data will be displayed
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


                tableRows[1].children[2].textContent = "17,000 PKR";
                tableRows[2].children[2].textContent = "17,000 PKR"; // You can calculate this based on the form data
                tableRows[3].children[2].textContent = "17,000 PKR"; // You can calculate this based on the form data
                // You can calculate this based on the form data
            } else {

                tableRows[1].children[2].textContent = "25,000 PKR"; // You can calculate this based on the form data
                tableRows[2].children[2].textContent = "25,000 PKR"; // You can calculate this based on the form data
                tableRows[3].children[2].textContent = "25,000 PKR"; // You can calculate this based on the form data

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
                        // Update your logic here based on retrieved charges
                        break; // Exit loop after finding a match
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

            $('#subscription_period').change(function() {
                let selectedOption = $(this).val();
                let incubator_city = $('#incubator_city').val();
                let preferred_timing = $('input[name="preferred_timing"]:checked').val().split(',');
                $.ajax({
                    url: '/incubator/calculate',
                    method: 'POST',
                    data: {
                        subscription_period: selectedOption,
                        incubator_city: incubator_city,
                        shift: preferred_timing[0],
                        timing: preferred_timing[1],
                    },
                    success: function(response) {
                        $('#subscription-table').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
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
                // Fetch values using the .attr() method for other elements
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
                        timing:timing,
                        shift:shift,
                        subscription_period: subscriptionPeriod,
                        coupon_code: couponCode,
                        totalAmount: totalAmount
                    },
                    success: function(response) {
                        $('#summary').html(response);
                        Swal.fire("Payment Summary");
                       
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors that occur during the request
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>
