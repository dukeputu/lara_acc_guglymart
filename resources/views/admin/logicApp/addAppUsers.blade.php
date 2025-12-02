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



            <form method="POST" action="{{ $isEdit ? route('company.update.update', $update->id) : route('user.company.store') }}" enctype="multipart/form-data">
                @csrf
              
                @if ($isEdit)
                    @method('PUT')
                @endif

                <!-- User Information Section -->
                <div class="section-header">
                    <div class="section-icon"><i class="fa fa-user"></i></div>
                    <h3>User Information</h3>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Company Name <sup>*</sup></label>
                        <input type="text" name="CompanyName" class="form-control"
                            value="{{ $isEdit ? $update->app_u_name : old('CompanyName') }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Company CIN No <sup>*</sup></label>
                        <input  type="text" name="CompanyCIN" class="form-control"
                            value="{{ $isEdit ? $update->cin_no : old('CompanyCIN') }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>PAN Number</label>
                        <input type="text" name="pan_number" class="form-control"
                            value="{{ $isEdit ? $update->pan_number : old('pan_number') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Mobile Number</label>
                        <input type="text" name="MobailNumber" class="form-control"
                            value="{{ $isEdit ? $update->phone_number : old('MobailNumber') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="text" name="user_email" class="form-control"
                            value="{{ $isEdit ? $update->user_email : old('user_email') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Address</label>
                        <input type="text" name="user_address" class="form-control"
                            value="{{ $isEdit ? $update->app_u_address : old('user_address') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Police Station</label>
                        <input type="text" name="PoliceStation" class="form-control"
                            value="{{ $isEdit ? $update->police_station : old('PoliceStation') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>District</label>
                        <input type="text" name="user_district" class="form-control"
                            value="{{ $isEdit ? $update->user_district : old('user_district') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>State Name</label>
                        <input type="text" name="user_state" class="form-control"
                            value="{{ $isEdit ? $update->user_state : old('user_state') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Pin Code</label>
                        <input type="text" name="pin_code" class="form-control"
                            value="{{ $isEdit ? $update->pin_code : old('pin_code') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Contact Person Name</label>
                        <input type="text" name="contact_person_no" class="form-control"
                            value="{{ $isEdit ? $update->contact_person_no : old('contact_person_no') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control">
                        @if ($isEdit && $update->user_pic_img)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $update->user_pic_img) }}" alt="Profile Picture"
                                    width="100">
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Bank Information Section -->
                <div class="section-header">
                    <div class="section-icon"><i class="fa fa-bank"></i></div>
                    <h3>1st Bank Information</h3>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" class="form-control"
                            value="{{ $isEdit ? $update->bank_name : old('bank_name') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Bank Account No.</label>
                        <input min="0" type="number" name="bank_account_no" class="form-control"
                            value="{{ $isEdit ? $update->bank_account_no : old('bank_account_no') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>IFSC Code</label>
                        <input type="text" name="ifsc_code" class="form-control"
                            value="{{ $isEdit ? $update->ifsc_code : old('ifsc_code') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>UPI ID</label>
                        <input type="text" name="upi_id" class="form-control"
                            value="{{ $isEdit ? $update->upi_id : old('upi_id') }}">
                    </div>
                </div>

                <!-- 2nd Bank Information Section (If Needed) -->
                <div class="section-header">
                    <div class="section-icon"><i class="fa fa-bank"></i></div>
                    <h3>2nd Bank Information (If Needed)</h3>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Bank Name</label>
                        <input type="text" name="second_bank_name" class="form-control"
                            value="{{ $isEdit ? $update->second_bank_name : old('second_bank_name') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Bank Account No.</label>
                        <input min="0" type="number" name="second_bank_account_no" class="form-control"
                            value="{{ $isEdit ? $update->second_bank_account_no : old('second_bank_account_no') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>IFSC Code</label>
                        <input type="text" name="second_ifsc_code" class="form-control"
                            value="{{ $isEdit ? $update->second_ifsc_code : old('second_ifsc_code') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>UPI ID</label>
                        <input type="text" name="second_upi_id" class="form-control"
                            value="{{ $isEdit ? $update->second_upi_id : old('second_upi_id') }}">
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="submit-section mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-user-plus"></i> {{ $isEdit ? 'Update User' : 'Register User' }}
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
