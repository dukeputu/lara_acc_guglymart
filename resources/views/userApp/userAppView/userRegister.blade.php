@extends('userApp.layouts.userAppLayout')
@section('title', 'Dashboard')

@section('content')

<style>
    .from-top-header {
        text-align: center;
        font-size: 20px;
        font-weight: 700;
        background: #004aad;
        color: #fff;
        display: block;
        border-radius: 20px;
    }

    .appBottomMenu {
        display: none;
    }
</style>

    <style>
        .from-top-header {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            background: #6236FF;
            color: #fff;
            display: block;
            border-radius: 20px;
        }

        .appBottomMenu {
            display: none;
        }

        .form-group.basic .form-control,
        .form-group.basic .custom-select {
            padding: 0 15px 0 15px;
            border-radius: 10px;
            background: #f1f1f1;

        }
    </style>




    <style>
        .form-group .label {
            font-size: 14px;

            color: #fff;

        }

        html,
        body {

            background: linear-gradient(135deg, #1e3c72, #2a5298, #6dd5ed);
            background-size: 600% 600%;
            animation: metallicShift 25s ease infinite;

        }

        @keyframes metallicShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        #appCapsule {
            width: 100%;
            max-width: 500px;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.07), rgba(255, 255, 255, 0.015));
            padding: 30px 20px;
            border-radius: 16px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.06), 0 15px 50px rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #e8e8e8;
        }

        h1,
        h4,
        .from-top-header {
            color: #ffffff;
            text-shadow: 0 2px 3px rgba(0, 0, 0, 0.4);
        }

        h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        h4 {
            font-size: 18px;
            font-weight: 400;
            margin-bottom: 25px;
            opacity: 0.9;
        }

        .card {
            background: linear-gradient(135deg, rgb(34 9 88), rgba(34, 9, 88, 0.1));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 0px;
            box-shadow: inset 0 0 8px rgba(255, 255, 255, 0.03);
        }

        .label {
            font-size: 14px;
            font-weight: 600;
            color: #e0e0e0;
            margin-bottom: 6px;
            display: inline-block;
        }

        .form-control {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.015));
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            padding: 12px 14px;
            color: #fff;
            width: 100%;
            box-shadow: inset 0 0 6px rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #6dd5ed;
            background: rgba(255, 255, 255, 0.07);
            box-shadow: 0 0 6px rgba(109, 213, 237, 0.6);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.45);
        }

        .clear-input ion-icon {
            color: rgba(255, 255, 255, 0.4);
        }

        .btn {
            background: linear-gradient(to right, #6dd5ed, #2193b0);
            color: #0b2e4a;
            font-weight: 700;
            font-size: 16px;
            padding: 14px;
            border: none;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(109, 213, 237, 0.4);
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(to right, #2193b0, #6dd5ed);
            box-shadow: 0 6px 30px rgba(109, 213, 237, 0.5);
        }

        .btn-outline-primary {
            border: 1px solid #6dd5ed;
            color: #6dd5ed;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover,
        .btn-check:checked+.btn-outline-primary {
            background-color: #6dd5ed;
            color: #0b2e4a;
            box-shadow: 0 0 8px rgba(109, 213, 237, 0.5);
        }

        .flash-message {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            background-color: rgba(40, 167, 69, 0.25);
            border-left: 4px solid #28a745;
            position: relative;
            color: #ffffff;
        }

        .flash-message .close-btn {
            position: absolute;
            right: 12px;
            top: 8px;
            background: none;
            border: none;
            color: #ffffff;
            font-size: 20px;
            cursor: pointer;
        }

        .alert-outline-danger {
            background-color: rgba(220, 53, 69, 0.15);
            color: #fff;
            border-left: 4px solid #dc3545;
        }

        .custom-file-upload label {
            display: block;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 14px;
            text-align: center;
            cursor: pointer;
            color: #cceeff;
            transition: all 0.3s ease;
        }

        .custom-file-upload label:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: #6dd5ed;
            color: #ffffff;
        }

        .form-check-label a {
            color: #6dd5ed;
            text-decoration: underline;
        }

        .form-check-label a:hover {
            color: #ffffff;
        }

        @media (max-width: 480px) {
            #appCapsule {
                padding: 24px 15px;
            }
        }

        .appHeader .left .headerButton,
        .appHeader .right .headerButton {
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            color: #ffffff;

        }
    </style>

    <style>
        .glow-users {
            background: #060676 !important;
            padding: 3px 7px;
            border-radius: 10px;
            font-size: 13px;
            color: #fff;
            animation: glowPulse 2s ease-in-out infinite;
        }

        @keyframes glowPulse {
            0% {
                box-shadow: 0 0 5px #060676, 0 0 10px #060676;
                text-shadow: 0 0 3px #fff;
            }

            50% {
                box-shadow: 0 0 15px #060676, 0 0 30px #060676;
                text-shadow: 0 0 8px #fff;
            }

            100% {
                box-shadow: 0 0 5px #060676, 0 0 10px #060676;
                text-shadow: 0 0 3px #fff;
            }
        }
    </style>



<!-- App Header -->
<div class="appHeader no-border transparent position-absolute">
    <div class="left">
        <a href="{{route('userLogin.app') }}" class="headerButton ">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"></div>
    <div class="right">
        <a href="{{ route('userLogin.app') }}" class="headerButton glow-users">
            Login
        </a>
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">

    <div class="section mt-2 text-center">
        <h1>Register now</h1>
        <h4>Create an account</h4>
    </div>
    <div class="section mb-5 p-2">
        @if (session('success'))
        <div class="flash-message flash-success">
            {{ session('success') }}
            <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
        @endif

        @if (session('error') ||$errors->any())
        {{-- <div class="flash-message flash-error">
            <ul style="margin-bottom: 0;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

                <li>{{ session('error') }}</li>
            </ul>
            <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
        </div> --}}

            <div class="alert alert-outline-danger mb-1" role="alert">
                         <ul style="margin-bottom: 0;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

                <li>{{ session('error') }}</li>
            </ul>
                    </div>
        @endif



        <form action="{{ route('registerUserApp.userApp') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="user_name">Name</label>
                            <input required type="text" class="form-control" id="user_name" name="user_name"
                                value="{{ old('user_name') }}">
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="phone_number">Phone Number <sup>(Phone Number Is User
                                    Name)</sup></label>
                            <input required type="number" class="form-control" id="phone_number" name="phone_number"
                                value="{{ old('phone_number') }}">
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    {{-- <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="introducer_number">Introducer ID <sup>(Phone Number Is Introducer ID)</sup></label>
                            <input  type="text" class="form-control" id="introducer_number" name="introducer_number"
                                value="{{ old('introducer_number') }}">
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div> --}}

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="user_email">Email</label>
                            <input  type="text" class="form-control" id="user_email" name="user_email"
                                value="{{ old('user_email') }}">
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>



                    <div class="custom-file-upload" id="fileUpload1">
                        <input type="file" id="profile_picture" accept=".png, .jpg, .jpeg" name="profile_picture">
                        <label for="profile_picture">
                            <span>
                                <strong>
                                    <ion-icon name="arrow-up-circle-outline"></ion-icon>
                                    <i>Profile Picture</i>
                                </strong>
                            </span>
                        </label>
                    </div>

                    {{-- <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="user_password">Password</label>
                            <input required type="text" class="form-control" id="user_password" name="user_password"
                                autocomplete="off" value="{{ old('password', '000111') }}">
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div> --}}

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="address">Address</label>
                            <input required type="text" class="form-control" id="user_address" name="user_address"
                                value="{{ old('user_address') }}">
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="pin_code">Pin Code</label>
                            <input required type="text" class="form-control" id="pin_code" name="pin_code"
                                value="{{ old('pin_code') }}">
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    <p class="from-top-header">Bank Information</p>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="bank_name">Bank Name</label>
                            <input  type="text" class="form-control" id="bank_name" name="bank_name"
                                value="{{ old('bank_name') }}">
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="fice_code">IFSC Code</label>
                            <input  type="text" class="form-control" id="ifsc_code" name="ifsc_code"
                                value="{{ old('ifsc_code') }}">
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="bank_account_no">Bank Account No</label>
                            <input  type="text" class="form-control" id="bank_account_no" name="bank_account_no"
                                value="{{ old('bank_account_no') }}">
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="upi_id">UPI Id</label>
                            <input  type="text" class="form-control" id="upi_id" name="upi_id"
                                value="{{ old('upi_id') }}">
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>


                     <div class="custom-file-upload" id="fileUpload2">
                        <input  type="file" id="upi_qr_code" accept=".png, .jpg, .jpeg" name="upi_qr_code" >
                        <label for="upi_qr_code">
                            <span>
                                <strong>
                                    <ion-icon name="arrow-up-circle-outline"></ion-icon>
                                    <i>UPI QR Code</i>
                                </strong>
                            </span>
                        </label>
                    </div>

                    <br>
                        <p class="from-top-header">Introducer Information</p>
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="label" for="introducer_number">Introducer ID <sup>(Phone
                                                        Number Is
                                                        Introducer ID)</sup></label>
                                                <input required type="number" class="form-control" id="introducer_id"
                                                    name="introducer_number" value="{{ old('introducer_number') }}">

                                                <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                                            </div>
                                            <div class="col-6">

                                                <button type="button" id="introduceIDBtn" class="btn btn-primary">Search
                                                    Name</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-4 my-3">
                                        <label class="label" for="introducer_name">Introducer Name </label>
                                        <input type="text" class="form-control" name="introducer_name"
                                            id="introducer_name" readonly style=" background: #b3ff00; " value="">
                                    </div>
                                    {{-- <div class="col-md-4 my-3">
                                        <label class="label" for="select_plan_name">Introducer Position </label>
                                        <input type="text" class="form-control" name="select_plan_name"
                                            id="select_plan_name" readonly style=" background: #b3ff00; " value="">

                                        <input type="hidden" class="form-control" name="select_plan_id"
                                            id="select_plan_id" readonly style=" background: #b3ff00; " value="">
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                    {{-- <div class="custom-control custom-checkbox mt-2 mb-1">
                        <div class="form-check">
                            <input required type="checkbox" class="form-check-input" id="customCheckb1">
                            <label class="form-check-label" for="customCheckb1">
                                I agree <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and
                                    conditions</a>
                            </label>
                        </div>
                    </div> --}}
                </div>
            </div>

            <div class="mt-3">
                <button style=" font-size: 25px; type="submit" class=" glow-users btn btn-primary btn-block btn-lg">Register</button>
            </div>
        </form>



    </div>

</div>
<!-- * App Capsule -->


<!-- Terms Modal -->
<div class="modal fade modalbox" id="termsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms and Conditions</h5>
                <a href="#" data-bs-dismiss="modal">Close</a>
            </div>
            <div class="modal-body">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc fermentum, urna eget finibus
                    fermentum, velit metus maximus erat, nec sodales elit justo vitae sapien. Sed fermentum
                    varius erat, et dictum lorem. Cras pulvinar vestibulum purus sed hendrerit. Praesent et
                    auctor dolor. Ut sed ultrices justo. Fusce tortor erat, scelerisque sit amet diam rhoncus,
                    cursus dictum lorem. Ut vitae arcu egestas, congue nulla at, gravida purus.
                </p>
                <p>
                    Donec in justo urna. Fusce pretium quam sed viverra blandit. Vivamus a facilisis lectus.
                    Nunc non aliquet nulla. Aenean arcu metus, dictum tincidunt lacinia quis, efficitur vitae
                    dui. Integer id nisi sit amet leo rutrum placerat in ac tortor. Duis sed fermentum mi, ut
                    vulputate ligula.
                </p>
                <p>
                    Vivamus eget sodales elit, cursus scelerisque leo. Suspendisse lorem leo, sollicitudin
                    egestas interdum sit amet, sollicitudin tristique ex. Class aptent taciti sociosqu ad litora
                    torquent per conubia nostra, per inceptos himenaeos. Phasellus id ultricies eros. Praesent
                    vulputate interdum dapibus. Duis varius faucibus metus, eget sagittis purus consectetur in.
                    Praesent fringilla tristique sapien, et maximus tellus dapibus a. Quisque nec magna dapibus
                    sapien iaculis consectetur. Fusce in vehicula arcu. Aliquam erat volutpat. Class aptent
                    taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
                </p>
            </div>
        </div>
    </div>
</div>
<!-- * Terms Modal -->



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if ($errors -> any())
        toastr.error("{{ $errors->first() }}");
    @endif
</script>

<script>
    setTimeout(() => {
        document.querySelectorAll('.flash-message').forEach(el => {
            el.style.transition = "opacity 0.5s";
            el.style.opacity = 0;
            setTimeout(() => el.style.display = 'none', 500);
        });
    }, 4000);
</script>


<script>
        $(document).ready(function() {
            $('#introduceIDBtn').click(function() {
                // $('#introducer_id').focusout(function() {
                var id = $('#introducer_id').val();

                if (id) {
                    $.get('/get-introducer/' + id, function(data) {
                        if (data && data.name) {
                            $('#introducer_id_hidden').val(data.introducer_id_hidden);
                            $('#introducer_name').val(data.name);
                            $('#select_plan_name').val(data.select_plan_name);
                            $('#select_plan_id').val(data.select_plan_id);

                            // Set Position radio button
                            if (data.position === 'Left') {
                                $('#position_left').prop('checked', true);
                            } else if (data.position === 'Right') {
                                $('#position_right').prop('checked', true);
                            }
                        } else {
                            alert('Introducer not found');
                        }
                    }).fail(function() {
                        alert('Something went wrong');
                    });
                } else {
                    // alert('Please enter Introducer ID');
                }
            });
        });
    </script>



@endsection