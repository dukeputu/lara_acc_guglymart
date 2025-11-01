@extends('layouts.app')
@section('title', 'Add')

@section('content')


    <div class="member-join-container">

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="flash-message flash-success">
                <i class="fa fa-check-circle"></i>
                <div>{!! session('success') !!}</div>
                <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div class="flash-message flash-error">
                <i class="fa fa-exclamation-circle"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    @if (session('error'))
                        <li>{{ session('error') }}</li>
                    @endif
                </ul>
                <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
            </div>
        @endif

        <!-- Form Card -->
        <div class="form-card">

         

            <form method="POST" action="{{ route('registerUserApp.userApp') }}" enctype="multipart/form-data">
                @csrf

                <!-- User Information Section -->
                <div class="section-header">
                    <div class="section-icon"><i class="fa fa-user"></i></div>
                    <h3>User Information</h3>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Name <sup>*</sup></label>
                        <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Phone Number <sup>*</sup></label>
                        <input min="0" type="number" name="phone_number" class="form-control" value="{{ old('phone_number') }}"
                            required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>PAN Number <sup>*</sup></label>
                        <input type="text" name="pan_number" class="form-control" value="{{ old('pan_number') }}" >
                    </div>

                    <div class="form-group col-md-4">
                        <label>Address</label>
                        <input type="text" name="user_address" class="form-control" value="{{ old('user_address') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Pin Code</label>
                        <input type="text" name="pin_code" class="form-control" value="{{ old('pin_code') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>CIN No</label>
                        <input type="text" name="cin_no" class="form-control" value="{{ old('cin_no') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Contact Person No</label>
                        <input type="text" name="contact_person_no" class="form-control" value="{{ old('contact_person') }}">
                    </div>
                      <div class="form-group col-md-4">
                        <label>Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control">
                    </div>

                  
                </div>

                <!-- Bank Information Section -->
                <div class="section-header">
                    <div class="section-icon"><i class="fa fa-bank"></i></div>
                    <h3>Bank Information</h3>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Bank Account No.</label>
                        <input min="0" type="number" name="bank_account_no" class="form-control"
                            value="{{ old('bank_account_no') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>IFSC Code</label>
                        <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>UPI ID</label>
                        <input type="text" name="upi_id" class="form-control" value="{{ old('upi_id') }}">
                    </div>

                  

                    <div class="form-group col-md-4">
                        <label>UPI QR Code</label>
                        <input type="file" name="upi_qr_code" class="form-control">
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="submit-section mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-user-plus"></i> Register User
                    </button>
                </div>
            </form>



        </div>
    </div>







    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if ($errors->any())
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
                // $('#introducer_id').focusout(function () {
                var id = $('#introducer_id').val();

                if (id) {
                    $.get('/get-introducer/' + id, function(data) {
                        if (data && data.name) {
                            $('#introducer_id_hidden').val(data.introducer_id_hidden);
                            $('#introducer_name').val(data.name);
                            $('#introducer_phone').val(data.phone);
                            $('#introducer_address').val(data.address);

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
