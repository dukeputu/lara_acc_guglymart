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





        {{-- Only show if user has a "Daily" plan --}}
        @if ($hasDailyPlan)
            <div style="margin-top: 30px;">
                <h3>Daily Update ({{ now()->format('F Y') }})</h3>

                @foreach ($monthDates as $item)
                    @php
                        $date = $item['date'];
                        $dayName = $item['day_name'];
                        $formatted = \Carbon\Carbon::parse($date)->format('d M Y');

                        if (in_array($dayName, $offDays)) {
                            $class = 'blackBg';
                        } elseif (in_array($date, $existingDates)) {
                            $class = 'primary';
                        } else {
                            $class = 'danger';
                        }
                    @endphp
                    {{-- 
                    <span class="metric-icon {{ $class }}">
                        {{ $formatted }}
                    </span> --}}

                    <a href="#date_entry">
                        <span class="metric-icon {{ $class }} day-select" data-date="{{ $date }}">
                            {{ $formatted }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif



        <br>

        <div style=" display: flex; flex-direction: column; ">
            <div>

                @if ($hasWeeklyPlan ?? false)
                    <h3>Weekly Update</h3>



                    @php
                        $userId = session('app_user_id');

                        // Get all weekly updates for this user in the current month
                        $weeklyUpdates = DB::table('weekly_update')
                            ->where('user_by', $userId)
                            ->whereMonth('weekly_from', now()->month)
                            ->whereMonth('weekly_to', now()->month)
                            ->orderByDesc('weekly_to')
                            ->get();

                        // Get last weekly_to date for min date
                        // $minDate = $weeklyUpdates->first()->weekly_to ?? now()->startOfMonth()->toDateString();
                        $minDate = now()->startOfMonth()->toDateString();

                        // Set max date to end of current month
                        $maxDate = now()->endOfMonth()->toDateString();
                    @endphp

                    <form action="{{ route('weekly.update.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Weekly From <sup>*</sup></label>
                                <input required type="date" id="weekly_from" name="weekly_from" class="form-control" required
                                    min="{{ $minDate }}" max="{{ $maxDate }}">
                            </div>

                            <div class="form-group col-md-3">
                                <label>Weekly To <sup>*</sup></label>
                                <input required type="date" id="weekly_to" name="weekly_to" class="form-control" required
                                    min="{{ $minDate }}" max="{{ $maxDate }}">
                            </div>

                            <input required type="hidden" name="is_weekly" value="1">

                            <div class="form-group col-md-3 mt-2">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                @endif

            </div>
            <br>

            <div>
                {{-- Weekly dates --}}
                @if ($hasWeeklyPlan)
                    @foreach ($monthWeeklyDates as $day)
                        @if (is_array($day) && isset($day['date']))
                            @php
                                $date = $day['date'];
                                $formatted = \Carbon\Carbon::parse($date)->format('d M Y');
                            @endphp
                            <span class="metric-icon {{ $day['status'] }}">
                                {{ $formatted }}
                            </span>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>


        <style>
            .hidden-input {
                display: none !important;
            }
        </style>

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
                    {{-- <div class="form-group col-md-4">
                        <label>Month <sup>*</sup></label>
                        <select name="month_name" class="form-control" required>
                            <option value="" disabled selected>Select Month</option>
                            @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                <option value="{{ $m }}"
                                    {{ ($isEdit && $update->month_name == $m) || (!$isEdit && date('F') == $m) ? 'selected' : '' }}>
                                    {{ $m }}
                                </option>
                            @endforeach
                        </select>
                    </div>                    --}}





                    @if ($hasDailyPlan)
                        <div class="form-group col-md-4">
                            <style>
                                #offday-warning {

                                    position: absolute;
                                    bottom: 34px;
                                    left: 70px;
                                }
                            </style>

                            <small id="offday-warning" class="text-danger" style="display:none;"></small>
                            <label>Date <sup>*</sup></label>
                            <input required id="date_entry" 
                                type="date" name="date_entry"
                                class="form-control" value="{{ $isEdit ? $update->date_entry : date('Y-m-d') }}" required>
                            {{-- <input required id="date_entry" max="{{ now()->toDateString() }}"
                                min="{{ now()->startOfMonth()->toDateString() }}" type="date" name="date_entry"
                                class="form-control" value="{{ $isEdit ? $update->date_entry : date('Y-m-d') }}" required> --}}

                        </div>
                    @else
                        <div class="form-group col-md-4">
                            <label>Date <sup>*</sup></label>
                            <input required  
                                type="date" name="date_entry" class="form-control"
                                value="{{ $isEdit ? $update->date_entry : date('Y-m-d') }}" required>
                            {{-- <input required max="{{ now()->toDateString() }}" min="{{ now()->startOfMonth()->toDateString() }}"
                                type="date" name="date_entry" class="form-control"
                                value="{{ $isEdit ? $update->date_entry : date('Y-m-d') }}" required> --}}
                        </div>
                    @endif



                    <div class="form-group col-md-4">
                        <label>Today EMI (Collection)</label>
                        <input required type="number" step="0.01" name="today_emi" class="form-control"
                            value="{{ $isEdit ? $update->today_emi : '0' }}">
                    </div>

                    <div class="form-group col-md-4 hidden-input">
                        <label>Previous Carrent Balance</label>
                        <input required readonly type="number" step="0.01" name="PreviousCarrentBalance" class="form-control"
                            value="{{ $isEdit ? $update->PreviousCarrentBalance : '0' }}">
                    </div>

                    @if ($showRdSection)
                        <div class="form-group col-md-4 hidden-input">
                            <label>Previous RD Balance</label>
                            <input required readonly type="number" step="0.01" name="PreviousRDBalance" class="form-control"
                                value="{{ $isEdit ? $update->PreviousRDBalance : '0' }}">
                        </div>
                    @endif


                    <div class="form-group col-md-4 hidden-input">
                        <label>Available Fund</label>
                        <input required readonly type="number" name="AvailableFund" class="form-control"
                            value="{{ $isEdit ? $update->AvailableFund : '0' }}">
                    </div>


                    <div class="form-group col-md-4">
                        <label>Today Close Customers</label>
                        <input required type="number" name="today_close_customers" class="form-control"
                            value="{{ $isEdit ? $update->today_close_customers : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today New Customers</label>
                        <input required type="number" name="today_new_customers" class="form-control"
                            value="{{ $isEdit ? $update->today_new_customers : '0' }}">
                    </div>

                    @if ($hasDaily)
                        <div class="form-group col-md-4">
                            <label>Today Total Daily Colletion Loan</label>
                            <input required type="number" name="total_daily_colletion" class="form-control"
                                value="{{ $isEdit ? $update->total_daily_colletion : '0' }}">
                        </div>
                    @endif
                    
                    @if ($hasWeekly)
                        <div class="form-group col-md-4">
                            <label>Today Total Weekly Colletion Loan</label>
                            <input required type="number" name="total_weekly_colletion" class="form-control"
                                value="{{ $isEdit ? $update->total_weekly_colletion : '0' }}">
                        </div>
                    @endif

                    @if ($hasBiWeekly)
                        <div class="form-group col-md-4">
                            <label>Today Total Bi-Weekly Colletion Loan</label>
                            <input required type="number" name="total_bi_weekly_colletion" class="form-control"
                                value="{{ $isEdit ? $update->total_bi_weekly_colletion : '0' }}">
                        </div>
                    @endif

                    @if ($hasMonthly)
                        <div class="form-group col-md-4">
                            <label>Today Total Monthly Colletion Loan</label>
                            <input required type="number" name="total_monthly_colletion" class="form-control"
                                value="{{ $isEdit ? $update->total_monthly_colletion : '0' }}">
                        </div>
                    @endif

                    <div class="form-group col-md-4 hidden-input">
                        <label>Investment Amount</label>
                        <input required readonly type="number" name="InvestmentAmount" class="form-control"
                            id="InvestmentAmount" value="{{ $isEdit ? $update->InvestmentAmount : '0' }}"
                            id="InvestmentAmount">

                    </div>



                    <div class="form-group col-md-4">
                        <label>Today Loan in A/C</label>
                        <input required type="number" step="0.01" name="today_loan_in_ac" class="form-control"
                            value="{{ $isEdit ? $update->today_loan_in_ac : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today Loan in Cash</label>
                        <input required type="number" step="0.01" name="today_loan_in_cash" class="form-control"
                            value="{{ $isEdit ? $update->today_loan_in_cash : '0' }}">
                    </div>

                    <div class="form-group col-md-4 hidden-input">
                        <label>Today Total Loan Amount</label>
                        <input required readonly type="number" step="0.01" name="today_total_loan_amount"
                            class="form-control" value="{{ $isEdit ? $update->today_total_loan_amount : '0' }}">
                    </div>


                    <div class="form-group col-md-4">
                        <label>Today Closing Balance in A/C</label>
                        <input required type="number" step="0.01" name="today_closing_balance_ac" class="form-control"
                            value="{{ $isEdit ? $update->today_closing_balance_ac : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Today Closing Balance in Cash</label>
                        <input required type="number" step="0.01" name="today_closing_balance_cash" class="form-control"
                            value="{{ $isEdit ? $update->today_closing_balance_cash : '0' }}">
                    </div>

                    <div class="form-group col-md-4 hidden-input">
                        <label>Current Balance (Cash in Hand & Account)</label>
                        <input required readonly type="number" step="0.01" name="current_balance" class="form-control"
                            value="{{ $isEdit ? $update->current_balance : '0' }}">
                    </div>
                </div>




                @if ($showRdSection)
                    <div class="rd-row">
                        <div class="section-header">
                            <div class="section-icon"><i class="fa-brands fa-font-awesome"></i></div>
                            <h3>RD Entry</h3>
                        </div>

                        <div class="row">



                            <div class="form-group col-md-4">
                                <label> Received RD Amount <sup>*</sup></label>
                                <input required type="number" step="0.01" name="rd_amount" class="form-control"
                                    value="{{ $isEdit ? $update->rd_amount : '0' }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label>RD Withdrawal <sup>*</sup></label>
                                <input required type="number" step="0.01" name="rd_withdrawal" class="form-control"
                                    value="{{ $isEdit ? $update->rd_withdrawal : '0' }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Paid RD Interest <sup>*</sup></label>
                                <input required type="number" step="0.01" name="rd_interest" class="form-control"
                                    value="{{ $isEdit ? $update->rd_interest : '0' }}" required>
                            </div>


                        </div>
                    </div>
                @endif

                <div class="submit-section">
                    <button id="submitBtn" type="submit" class="btn btn-primary">
                        <i class="fa {{ $isEdit ? 'fa-save' : 'fa-plus' }}"></i>
                        {{ $isEdit ? 'Update' : 'Save' }}
                    </button>
                    <a href="{{ route('daily.update.view') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </form>






        </div>
    </div>

    {{-- JS to handle off day blocking --}}
    <script>
        const offDays = @json($offDays ?? []); // Example: ["Sunday", "Monday"]
        const dateInput = document.getElementById('date_entry');
        const warning = document.getElementById('offday-warning');
        const submitBtn = document.querySelector('button[type="submit"]'); // Get your submit button

        dateInput.addEventListener('change', e => {
            const d = new Date(e.target.value);
            const day = d.toLocaleDateString('en-US', {
                weekday: 'long'
            });
            const isOff = offDays.includes(day);

            warning.style.display = isOff ? 'inline' : 'none';
            warning.innerHTML = isOff ?
                `<strong>${day}</strong> is marked as an off day. Please select another date.` : '';
            submitBtn.disabled = isOff;
            if (isOff) e.target.value = '';
        });
    </script>

    <script>
        document.querySelectorAll('.day-select').forEach(span => {
            span.addEventListener('click', function() {
                const selectedDate = this.getAttribute('data-date');
                const input = document.getElementById('date_entry');

                input.value = selectedDate; // Set clicked date to input
                input.dispatchEvent(new Event('change')); // Trigger validation if needed
            });
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        const input = document.getElementById('InvestmentAmount');
        const fix = () => {
            if (+input.value < 0) input.value = 0;
        };
        new MutationObserver(fix).observe(input, {
            attributes: true,
            attributeFilter: ['value']
        });
        setInterval(fix, 100);
    </script>





    <script>
        function autoSum(operations, totalFieldName) {
            const totalField = document.querySelector(`[name="${totalFieldName}"]`);

            const fields = operations.map(operation => ({
                element: document.querySelector(`[name="${operation.replace(/^[-+]/,'')}"]`),
                sign: operation[0] === '-' ? -1 : 1
            }));

            const updateTotal = () => {
                totalField.value = fields.reduce((sum, {
                    element,
                    sign
                }) => sum + sign * (+element.value || 0), 0).toFixed();
            };

            fields.forEach(({
                element
            }) => element && element.addEventListener('input', updateTotal));
            updateTotal();
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if ($showRdSection)
                autoSum(['+PreviousCarrentBalance', '+PreviousRDBalance', '+today_emi'], 'AvailableFund');
            @endif
            autoSum(['+today_loan_in_ac', '+today_loan_in_cash'], 'today_total_loan_amount');
            autoSum(['+today_closing_balance_ac', '+today_closing_balance_cash'], 'current_balance');
            autoSum(['+total_daily_colletion', '+total_weekly_colletion', '+total_bi_weekly_colletion',
                '+total_monthly_colletion', '-AvailableFund'
            ], 'InvestmentAmount');
        });
    </script>



    <script>
        $(document).ready(function() {
            function fetchPreviousBalance(date) {
                if (date) {
                    $.ajax({
                        url: "{{ route('daily.update.getPreviousBalance') }}",
                        type: "GET",
                        data: {
                            date: date
                        },
                        success: function(response) {
                            $('input[name="PreviousCarrentBalance"]').val(response.previous_balance);
                            $('input[name="PreviousRDBalance"]').val(response.previous_rd_balance);
                        },
                        error: function() {
                            console.log('Error fetching previous balance');
                        }
                    });
                }
            }

            // Trigger on date change
            $('input[name="date_entry"]').on('change', function() {
                fetchPreviousBalance($(this).val());
            });

            // Trigger automatically on page load
            let initialDate = $('input[name="date_entry"]').val();
            if (initialDate) {
                fetchPreviousBalance(initialDate);
            }
        });
    </script>









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
