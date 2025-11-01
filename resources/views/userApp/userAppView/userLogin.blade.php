@extends('userApp.layouts.userAppLayout')
@section('title', 'Dashboard')

@section('content')
    <style>
        .appBottomMenu {
            display: none;
        }
    </style>

        <style>
        .form-group .input-wrapper.active .label {
            color: #fdff00 !important;
        }

        .appBottomMenu {
            display: none;
        }

        .form-group.basic .form-control,
        .form-group.basic .custom-select {
            padding: 0 15px 0 15px;
            border-radius: 10px;
            background: unset !important;
            color:#fff;
        }
    </style>

    <style>
        .glow-users {
            background: #060676;
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

    <style>
        .form-group .label {
            font-size: 14px;

            color: #fff;

        }

        /* Metallic gradient background */
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298, #6dd5ed);
            background-size: 600% 600%;
            animation: metallicShift 30s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
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

        /* Capsule with metallic sheen */
        #appCapsule {
            width: 100%;
            max-width: 440px;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.02));
            padding: 30px 20px;
            border-radius: 16px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.05), 0 15px 40px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e0e0e0;
        }

        h1 {
            font-size: 32px;
            font-weight: 600;
            color: #ffffff;
            /* margin-bottom: 24px; */
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        .alert {
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #fff;
        }

        .alert-primary {
            background-color: rgba(0, 123, 255, 0.25);
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.25);
        }

        .card {
            background: rgb(56 0 234);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 20px;
        }

        .form-control {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 14px;
            color: #ffffff;
            width: 100%;
            box-shadow: inset 0 0 5px rgba(255, 255, 255, 0.1);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus {
            outline: none;
            border-color: #6dd5ed;
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 6px rgba(109, 213, 237, 0.6);
        }

        .label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #eeeeee;
            font-size: 14px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.6);
        }

        .clear-input {
            color: rgba(255, 255, 255, 0.4);
        }

        /* Form links metallic blue */
        .form-links a {
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .form-links a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        /* Metallic button */
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
            box-shadow: 0 4px 20px rgba(109, 213, 237, 0.3);
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(to right, #2193b0, #6dd5ed);
            box-shadow: 0 6px 25px rgba(109, 213, 237, 0.5);
        }

        /* AppHeader if used */
        .appHeader {
            backdrop-filter: blur(5px);
        }

        @media (max-width: 480px) {
            #appCapsule {
                padding: 24px 15px;
            }
        }
    </style>


    <!-- App Header -->
    <div class="appHeader no-border transparent position-absolute">

        <div class="pageTitle"></div>
        <div class="right">
        </div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="section mt-2 text-center">
            <h1>Log in</h1>

        </div>
        <div class="section mb-5 p-2">

            {{-- @if (session('success'))

                 <div class="alert alert-primary mb-1" role="alert">
                        {!! session('success') !!}
                        <br>
                        Save It Carefully
                    </div>
                @endif --}}



            @if (session('success'))
                <div class="alert alert-primary mb-1" role="alert">
                    {!! session('success') !!}
                    <br>
                    Save It Carefully
                </div>
            @endif

            {{-- Check localStorage for JS-injected success message --}}
            <script>
                const savedMessage = localStorage.getItem('success_flash');
                if (savedMessage) {
                    document.write(`<div class="alert alert-primary mb-1" role="alert">${savedMessage}</div>`);
                    localStorage.removeItem('success_flash');
                }
            </script>


            @if (session('error'))
                <div class="alert alert-danger mb-1" role="alert">
                    {!! session('error') !!}
                </div>
            @endif




            <form method="POST" action="{{ route('loginUserApp.userApp') }}">
                @csrf
                <div class="card">
                    <div class="card-body pb-1">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="phone_number">User Name <sup>Phone No</sup></label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="User Name" value="{{ old('phone_number') }}" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Your password" autocomplete="off" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-links mt-2">
                    <div>
                        <a class="glow-users" href="{{ route('userRegister.app') }}">Register Now</a>
                    </div>
                    {{-- <div><a href="app-forgot-password.html.htm" class="text-muted">Forgot Password?</a></div> --}}

                    <div>
                        <a href="#" class="glow-users" data-bs-toggle="modal" data-bs-target="#contactUs">Forgot Password</a>
                    </div>
                </div>

                <div class="form-button-group transparent">
                    <button style=" font-size: 25px; type="submit" class=" glow-users btn btn-primary btn-block btn-lg">Log in</button>
                </div>
            </form>






        </div>

    </div>
    <!-- * App Capsule -->

    <div class="modal fade" id="contactUs" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Forgot Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- <form method="POST" action="{{ route('contactUsMessagePost.list') }}"> --}}
                    <form method="POST" action="#">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label class="label" for="introducer_number">Phone Number</label>
                                <input type="text" class="form-control" id="introducer_id" name="introducer_number"
                                    value="{{ old('introducer_number') }}">
                            </div>
                            <div class="col-6">
                                <button style="margin-top: 24px;" type="button" id="introduceIDBtn"
                                    class="btn btn-primary">Send OTP</button>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label>Your Name</label>
                            <input type="text" class="form-control" name="introducer_name" id="introducer_name" readonly
                                style="background: #b3ff00;" value="">
                        </div>

                        <div class="form-group mb-2">
                            <label>OTP</label>
                            {{-- <textarea class="form-control" name="whyText" rows="4" cols="50"></textarea> --}}
                            <input type="text" name="" id="">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </form>


                    <div id="passwordUpdateMsg" class="mt-3 text-center"></div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                            alert('User not found');
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
