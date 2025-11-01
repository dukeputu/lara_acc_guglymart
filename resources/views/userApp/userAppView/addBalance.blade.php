@php
    $userId = session('app_user_id');
    // $userName = session('app_user_name');
    // $userPhone = session('app_user_phone');
    // $userPic = session('app_user_photo');
    // $userWallet = session('app_user_wallet');
    // $currentTotal = DB::table('app_users')->where('id', $userId)->value('total_pakeg_amount');
    // $currentuser_wallet = DB::table('app_users')->where('id', $userId)->value('user_wallet');
    $totalWithdrawalReq = DB::table('app_users')->where('id', $userId)->value('total_withdrawal_req');
    // $life_time_eran = DB::table('app_users')->where('id', $userId)->value('life_time_eran');
@endphp

@extends('userApp.layouts.userAppLayout')
@section('title', 'Dashboard')

@section('content')



    <!-- App Header -->
    <div class="appHeader">
        <div class="left">
            <a href="{{ route('dashboard.app') }}" class="headerButton ">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            Add Balance


        </div>

    </div>
    <!-- * App Header -->

    <br>
    <br>



    <h1 class="text-center pt-2"> Add PIN</h1>

    <div class="section mt-2">

        <div class="card">
            <div class="card-body">

                @php
                    // $userId = session('app_user_id');
                    $userName = session('app_user_name');
                    $userPhone = session('app_user_phone');
                @endphp

                @if (session('success'))
                    <div class="alert alert-primary mb-1">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger mb-1">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mb-1">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <style>
                    .glow-users {
                        background: #060676;
                        padding: 3px 7px;
                        border-radius: 10px;
                        font-size: 10px;
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


                <form action="{{ route('userAddBalance.userApp') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="userId" value="{{ $userId }}">
                    <input type="hidden" name="userName" value="{{ $userName }}">
                    <input type="hidden" name="userPhone" value="{{ $userPhone }}">

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    @if (($totalWithdrawalReq ?? 0) != 0)
                                        <center>

                                            <h5 class="glow-users ">Your PIN Wallat Balance is ₹
                                                {{ ($totalWithdrawalReq ?? 0) == 0 ? 'NA' : number_format($totalWithdrawalReq, 2) }}
                                            </h5>
                                        </center>
                                    @endif


                                    {{-- <label class="label" for="add_pin_bal">Buy PIN <sup>1 PIN = Rs. 500/-</sup></label> --}}

                                    {{-- <input min="10" max="200" required type="number" class="form-control"
                                        id="buyPinBal" name="add_pin_bal" value="" placeholder="Enter Number Of PIN"
                                        onkeyup="document.getElementById('add_balance_amount').value = this.value * 500"> 

                             
                                    <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i> --}}

                                    <style>
                                        .form-group.basic .form-control,
                                        .form-group.basic .custom-select {

                                            padding: 0 30px 0 20px;

                                        }
                                    </style>

                                    {{-- <select id="buyPinBal" class="form-control" onchange="updatePins()">
                                        <option value="" disabled selected>-- Select Package --</option>
                                        <option value="10" data-amount="5000">₹5000 => 10 PINs</option>
                                        <option value="20" data-amount="10000">₹10000 => 20 PINs</option>
                                        <option value="50" data-amount="25000">₹25000 => 50 PINs</option>
                                        <option value="100" data-amount="50000">₹50000 => 100 PINs</option>
                                        <option value="200" data-amount="100000">₹100000 => 200 PINs</option>
                                    </select>

                                    <!-- Hidden inputs -->
                                    <input type="hidden" id="add_pin_bal" name="add_pin_bal">
                                    

                                    <script>
                                        function updatePins() {
                                            const select = document.getElementById("buyPinBal");
                                            const pinCount = select.value;
                                            const amount = select.options[select.selectedIndex].dataset.amount;

                                            document.getElementById("add_pin_bal").value = pinCount;
                                            document.getElementById("add_balance_amount").value = amount;
                                        }
                                    </script> --}}
                                    <br>

                                    @if (($totalWithdrawalReq ?? 0) != 0)
                                        <div class="form-group boxed">
                                            <div class="input-wrapper">
                                                <label class="label" for="select4b">PIN Add By</label>
                                                <select class="form-control custom-select" id="cashOrWallat"
                                                    name="cash_or_wallat">
                                                    <option selected disabled>Select Cash / Wallat</option>
                                                    <option value="1">Cash</option>
                                                    <option value="2">Wallat</option>

                                                </select>



                                            </div>
                                        </div>
                                    @endif




                                    <br>
                                    <div class="input-wrapper">
                                        <label class="label" for="add_balance_amount">Buy PIN <sup>1 PIN = Rs.
                                                50/-</sup></label>
                                        @if (($totalWithdrawalReq ?? 0) != 0)
                                            {{-- <input required type="number" class="form-control" id="add_balance_amountIf"
                                                name="add_balance_amount" value="{{ old('add_balance_amount') }}"
                                                placeholder="Enter Amount Here" style=" padding-left: 20px; " min="50"
                                                max="{{ $totalWithdrawalReq ?? '0.00' }}"> --}}

                                            <input required type="number" class="form-control" id="add_balance_amount"
                                                name="add_balance_amount" placeholder="Enter Amount Here"
                                                style="padding-left: 20px;" min="50" max="100000">
                                        @else
                                            <input required type="number" class="form-control" id="add_balance_amount"
                                                name="add_balance_amount" value="{{ old('add_balance_amount') }}"
                                                placeholder="Enter Amount Here" style=" padding-left: 20px; " min="50"
                                                max="100000">
                                        @endif
                                        <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                                    </div>

                                    <br>

                                    <div class="input-wrapper">
                                        <label class="label" for="add_balance_amount">Payable PIN</label>
                                        <input readonly type="number" class="form-control" id="add_pin_amount"
                                            name="add_pin_bal" value="{{ old('add_balance_amount') }}"
                                            style=" background: yellow; padding-left: 20px; ">
                                        <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                                    </div>
                                </div>


                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Get the input fields
                                        var balanceAmountInput = document.getElementById('add_balance_amount');
                                        var pinAmountInput = document.getElementById('add_pin_amount');

                                        // Variable to store the timeout
                                        var typingTimer;

                                        // Add input event listener on the 'add_balance_amount' field
                                        balanceAmountInput.addEventListener('input', function() {
                                            clearTimeout(typingTimer); // Clear the previous timer if user is still typing

                                            typingTimer = setTimeout(function() {
                                                var amount = parseFloat(balanceAmountInput.value); // Get the value entered

                                                // Ensure the amount is a number and round it to the nearest multiple of 50
                                                if (!isNaN(amount)) {
                                                    var roundedAmount = Math.round(amount / 50) * 50;
                                                    balanceAmountInput.value =
                                                        roundedAmount; // Set the rounded value back to the input field

                                                    // Calculate PIN amount (assuming 1 PIN = Rs. 50)
                                                    var pinAmount = roundedAmount / 50;

                                                    // Update the 'add_pin_amount' field with the calculated PIN amount
                                                    pinAmountInput.value = pinAmount;
                                                }
                                            }, 900); // Delay of 500ms before rounding and updating the pin amount
                                        });
                                    });
                                </script>



                                <h3 class="text-center pt-2">Upload Payment ScreenShot
                                    <span class="glow-users" id="paymentScreenShot" style="display: none;">No Need</span>
                                </h3>

                                <div class="custom-file-upload" id="fileUpload1">
                                    <input required type="file" id="fileuploadInput" accept=".png, .jpg, .jpeg"
                                        name="payment_screenShot">
                                    <label for="fileuploadInput">
                                        <span>
                                            <strong>
                                                <ion-icon name="arrow-up-circle-outline"></ion-icon>
                                                <i>Upload Payment ScreenShot</i>
                                            </strong>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <br><br><br>
                        <div>
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Submit PIN Add Request</button>
                        </div>
                        <br><br><br><br>
                </form>
                {{-- <input required type="number" class="form-control" id="add_balance_amountIf" name="add_balance_amount"
                    placeholder="Enter Amount Here" style="padding-left: 20px;" min="50">

                <input required type="number" class="form-control" id="add_balance_amount" name="add_balance_amount"
                    placeholder="Enter Amount Here" style="padding-left: 20px;" min="50" max="100000"> --}}

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var selectElement = document.getElementById('cashOrWallat');
                        var fileInput = document.getElementById('fileuploadInput');
                        var paymentText = document.getElementById('paymentScreenShot');
                        var addBalanceInput = document.getElementById('add_balance_amount');

                        selectElement.addEventListener('change', function() {
                            var selectedValue = this.value;

                            if (selectedValue === '2') {
                                // Wallet selected: Change max attribute
                                var totalWithdrawalReq = {{ $totalWithdrawalReq ?? 100000 }};
                                addBalanceInput.setAttribute('max', totalWithdrawalReq);

                                // Wallet selected: Remove required attribute for file upload and show payment text
                                fileInput.removeAttribute('required');
                                paymentText.style.display = 'inline'; // Show "No Need"
                            } else {
                                // Cash or any other selected: Reset max attribute to default
                                addBalanceInput.setAttribute('max', '100000');

                                // Cash or other selected: Set file upload as required and hide payment text
                                fileInput.setAttribute('required', 'required');
                                paymentText.style.display = 'none'; // Hide "No Need"
                            }
                        });
                    });
                </script>




            </div>
        </div>

    </div>


    <div class="section mt-5 mb-4">
        <h2 class="text-center pt-2"> Company Payment Details</h2>
        <div class="card">
            <div class="card-body">


                @if ($warningMessage)
                    <div class="alert alert-danger mb-1">
                        {{ $warningMessage }}
                    </div>
                @endif

                @foreach ($membersBankDetails as $bankDetails)
                    <div>
                        @if (!empty($bankDetails->BankName))
                            <strong>Bank Name : {{ $bankDetails->BankName }}</strong><br>
                        @endif

                        @if (!empty($bankDetails->BankIFSC))
                            <strong>Bank IFSC Code: {{ $bankDetails->BankIFSC }}</strong><br>
                        @endif

                        @if (!empty($bankDetails->name))
                            <strong>AC Holder Name : {{ $bankDetails->name }}</strong><br>
                        @endif

                        @if (!empty($bankDetails->BankACNo))
                            <strong>Bank AC. No : {{ $bankDetails->BankACNo }}</strong><br>
                        @endif

                        @if (!empty($bankDetails->upiId))
                            <strong>UPI Id : {{ $bankDetails->upiId }}</strong><br>
                        @endif

                        @if (!empty($bankDetails->qrCodeUpload))
                            <strong>UPI QR Code 👇</strong><br><br>
                            <center class="mb-1">
                                <img src="{{ url(asset($bankDetails->qrCodeUpload)) }}" class="imaged w200">
                            </center>
                        @endif
                    </div>
                @endforeach


            </div>
        </div>


    </div>
    <br><br>


@endsection
