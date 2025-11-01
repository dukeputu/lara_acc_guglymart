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
                action="{{ $isEdit ? route('daily.update.update', $update->id) : route('daily.update.store') }}">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="section-header">
                    <div class="section-icon"><i class="fa fa-calendar-day"></i></div>
                    <h3>{{ $isEdit ? 'Edit Daily Update' : 'Add Daily Update' }}</h3>
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
                        <label>Business Plan Category <sup>*</sup></label>
                        <select name="business_plan_id" class="form-control" required>
                            <option value="" disabled selected>Select Business Plan</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}"
                                    {{ $isEdit && $update->business_plan_id == $plan->id ? 'selected' : '0' }}>
                                    {{ $plan->business_category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Date <sup>*</sup></label>
                        <input type="date" name="date_entry" class="form-control"
                            value="{{ $isEdit ? $update->date_entry : date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today EMI (Collection)</label>
                        <input type="number" step="0.01" name="today_emi" class="form-control"
                            value="{{ $isEdit ? $update->today_emi : '0' }}">
                    </div>


                    <div class="form-group col-md-4">
                        <label>Today Close Customers</label>
                        <input type="number" name="today_close_customers" class="form-control"
                            value="{{ $isEdit ? $update->today_close_customers : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today New Customers</label>
                        <input type="number" name="today_new_customers" class="form-control"
                            value="{{ $isEdit ? $update->today_new_customers : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today Total Daily Colletion Loan</label>
                        <input type="number" name="total_daily_colletion" class="form-control"
                            value="{{ $isEdit ? $update->total_daily_colletion : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today Total Weekly Colletion Loan</label>
                        <input type="number" name="total_weekly_colletion" class="form-control"
                            value="{{ $isEdit ? $update->total_weekly_colletion : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today Total Bi-Weekly Colletion Loan</label>
                        <input type="number" name="total_bi_weekly_colletion" class="form-control"
                            value="{{ $isEdit ? $update->total_bi_weekly_colletion : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today Total Monthly Colletion Loan</label>
                        <input type="number" name="total_monthly_colletion" class="form-control"
                            value="{{ $isEdit ? $update->total_monthly_colletion : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today Loan in A/C</label>
                        <input type="number" step="0.01" name="today_loan_in_ac" class="form-control"
                            value="{{ $isEdit ? $update->today_loan_in_ac : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today Loan in Cash</label>
                        <input type="number" step="0.01" name="today_loan_in_cash" class="form-control"
                            value="{{ $isEdit ? $update->today_loan_in_cash : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today Total Loan Amount</label>
                        <input type="number" step="0.01" name="today_total_loan_amount" class="form-control"
                            value="{{ $isEdit ? $update->today_total_loan_amount : '0' }}">
                    </div>


                    <div class="form-group col-md-4">
                        <label>Today Closing Balance in A/C</label>
                        <input type="number" step="0.01" name="today_closing_balance_ac" class="form-control"
                            value="{{ $isEdit ? $update->today_closing_balance_ac : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today Closing Balance in Cash</label>
                        <input type="number" step="0.01" name="today_closing_balance_cash" class="form-control"
                            value="{{ $isEdit ? $update->today_closing_balance_cash : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Current Balance (Cash in Hand & Account)</label>
                        <input type="number" step="0.01" name="current_balance" class="form-control"
                            value="{{ $isEdit ? $update->current_balance : '0' }}">
                    </div>
                </div>

                    <div class="section-header">
                    <div class="section-icon"><i class="fa-brands fa-font-awesome"></i></div>
                    <h3>RD Entry</h3>
                </div>

                <div class="row">              
                    

                   
                    <div class="form-group col-md-4">
                        <label> Received RD Amount <sup>*</sup></label>
                        <input type="number" step="0.01" name="rd_amount" class="form-control"
                            value="{{ $isEdit ? $update->rd_amount : '0' }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>RD Withdrawal <sup>*</sup></label>
                        <input type="number" step="0.01" name="rd_withdrawal" class="form-control"
                            value="{{ $isEdit ? $update->rd_withdrawal : '0' }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Paid RD Interest  <sup>*</sup></label>
                        <input type="number" step="0.01" name="rd_interest" class="form-control"
                            value="{{ $isEdit ? $update->rd_interest : '0' }}" required>
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
