@extends('layouts.user')
@section('title', $isEdit ? 'Edit Member' : 'Add New Member')

@section('content')


    <div class="member-join-container">

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="flash-message flash-success">
                <i class="fa fa-check-circle"></i>
                <div>{{ session('success') }}</div>
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

            <form method="POST"
                action="{{ $isEdit ? route('daily.update.update', $update->id) : route('monthly.update.store') }}">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="section-header">
                    <div class="section-icon"><i class="fa fa-calendar-day"></i></div>
                    <h3>{{ $isEdit ? 'Edit Monthly Update' : 'Add Monthly Update' }}</h3>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Month <sup>*</sup></label>
                        <select name="month_name" class="form-control" required>
                            <option value="" disabled selected>Select Month</option>
                            @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                <option value="{{ $m }}"
                                    {{ $isEdit && $update->month_name == $m ? 'selected' : '0' }}>{{ $m }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Total Director Loan</label>
                        <input type="number" step="0.01" name="director_loan" class="form-control"
                            value="{{ $isEdit ? $update->director_loan : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Total Bank Loan</label>
                        <input type="number" step="0.01" name="bank_loan" class="form-control"
                            value="{{ $isEdit ? $update->bank_loan : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Total Investment for Invertor</label>
                        <input type="number" step="0.01" name="investment_for_invertor" class="form-control"
                            value="{{ $isEdit ? $update->investment_for_invertor : '0' }}">
                    </div>

                       <div class="form-group col-md-4">
                        <label>Director Salary</label>
                        <input type="number" step="0.01" name="director_salary" class="form-control"
                            value="{{ $isEdit ? $update->director_salary : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Total Staff Salary</label>
                        <input type="number" step="0.01" name="staff_salary" class="form-control"
                            value="{{ $isEdit ? $update->staff_salary : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Office Rent</label>
                        <input type="number" step="0.01" name="office_rent" class="form-control"
                            value="{{ $isEdit ? $update->office_rent : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Electricity Bill</label>
                        <input type="number" step="0.01" name="electricity_bill" class="form-control"
                            value="{{ $isEdit ? $update->electricity_bill : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Internet / Mobile Recharge Bill</label>
                        <input type="number" step="0.01" name="recharge_bill" class="form-control"
                            value="{{ $isEdit ? $update->recharge_bill : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Purchase Furniture Amount</label>
                        <input type="number" step="0.01" name="furniture_amount" class="form-control"
                            value="{{ $isEdit ? $update->furniture_amount : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Other Expences</label>
                        <input type="number" step="0.01" name="other_expences" class="form-control"
                            value="{{ $isEdit ? $update->other_expences : '0' }}">
                    </div>


                </div>

              

                <div class="submit-section">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa {{ $isEdit ? 'fa-save' : 'fa-plus' }}"></i>
                        {{ $isEdit ? 'Update' : 'Save' }}
                    </button>
                    <a href="{{ route('daily.update.view') }}" class="btn btn-secondary">Back to List</a>
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
