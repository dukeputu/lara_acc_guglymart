<!DOCTYPE html>
<html>

<head>
    <title>Member Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f2f7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            max-width: 400px;
            margin: auto;
            margin-top: 12vh;
        }

        .card {
            border: none;
            border-radius: 1rem;
            background: rgb(56 0 234);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 20px;
        }

        .card-header {
            border-radius: 1rem 1rem 0 0;
            background-color: #1e3c72;
            text-align: center;
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

        .form-group .label {
            font-size: 14px;
            color: #fff;
        }

        .btn-block {
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

        .btn-block:hover {
            background: linear-gradient(to right, #2193b0, #6dd5ed);
            box-shadow: 0 6px 25px rgba(109, 213, 237, 0.5);
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

        .form-group label {
            font-size: 14px;
            color: #eeeeee;
        }

        .clear-input {
            color: rgba(255, 255, 255, 0.4);
        }

        .label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #eeeeee;
            font-size: 14px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.6);
        }

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

        @media (max-width: 480px) {
            .login-container {
                margin-top: 10vh;
            }

            .card-header h4 {
                font-size: 1.3rem;
            }

            .form-group label {
                font-size: 0.9rem;
            }
        }


        .form-control:focus {
            color: #ffffff;
            background-color: tomato;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
        }
    </style>
</head>

<body>

    <div class="container login-container">
        <div class="card shadow-lg">
            <center><img class="my-3" style="max-width: 75px; border-radius: 20px; "
                    src="{{ url('userApp/assets/goldoffLogo.webp') }}" alt="">
            </center>
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Member Login</h4>
            </div>
            <div class="card-body">
                {{-- Show session errors --}}
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('member.login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="member_id">Member ID</label>
                        <input type="text" name="member_id" class="form-control" placeholder="Enter Member ID"
                            value="{{ old('member_id') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter Password"
                            required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">🔐 Login</button>
                </form>



                
            </div>
        </div>
    </div>

</body>

</html>
