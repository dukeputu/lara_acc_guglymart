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
                action="{{ $isEdit ? route('business.plan.update', $plan->id) : route('business.plan.store') }}"
                enctype="multipart/form-data">
                @csrf

                <div class="section-header">
                    <div class="section-icon"><i class="fa fa-briefcase"></i></div>
                    <h3>{{ $isEdit ? 'Edit Business Plan' : 'Add New Business Plan' }}</h3>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Business Category <sup>*</sup></label>
                        <select  id="business_category" name="business_category_id" class="form-control" required>
                            <option value="" disabled selected>Select Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ $isEdit && $plan->business_category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->category_name }}
                                </option>
                            @endforeach
                        </select>

      

                    </div>

                    <!-- Off Day -->
                    <div class="form-group col-md-4" name="off_day_wrapper" style="display:none;">
                        <label>Off Day <sup>*</sup></label>
                        <select name="off_day" class="form-control">
                            <option value="" disabled selected>Select Off Day</option>
                            @foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','No_Off'] as $day)
                                <option value="{{ $day }}"
                                    {{ $isEdit && $plan->off_day == $day ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <script>
                    document.addEventListener('change', e => {
                        if (e.target.name === 'business_category_id') {
                            document.querySelector('[name="off_day_wrapper"]').style.display =
                                e.target.value === '1' ? 'block' : 'none';
                        }
                    });
                </script>


                <hr>

                <div class="row">


                    <div class="form-group col-md-4">
                        <label>Loan Amount <sup>*</sup></label>
                        <input required  type="number" min="0" step="0.01" name="loan_amount" class="form-control"
                            value="{{ $isEdit ? $plan->loan_amount : '0' }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Total Extra-Amount <sup>*</sup></label>
                        <input required type="number" min="0" step="0.01" name="extra_amount" class="form-control"
                            value="{{ $isEdit ? $plan->extra_amount : '0' }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Number Of Days <sup>*</sup></label>
                        <input required type="number" min="0" step="0.01" name="number_of_days" class="form-control"
                            value="{{ $isEdit ? $plan->number_of_days : '0' }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Membership %</label>
                        <input required type="number" min="0" step="0.01" name="membership_per" class="form-control"
                            value="{{ $isEdit ? $plan->membership_per : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Membership Charge</label>
                        <input required readonly type="number" min="0" step="0.01" name="membership_charge"
                            class="form-control" value="{{ $isEdit ? $plan->membership_charge : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>EMI Amount <sup>*</sup></label>
                        <input required readonly type="number" min="0" step="0.01" name="emi_amount" class="form-control"
                            value="{{ $isEdit ? $plan->emi_amount : '0' }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Processing Charge</label>
                        <input required readonly type="number" min="0" step="0.01" name="processing_charge"
                            class="form-control" value="{{ $isEdit ? $plan->processing_charge : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Loan Insurance Charge</label>
                        <input required readonly type="number" min="0" step="0.01" name="loan_insurance_charge"
                            class="form-control" value="{{ $isEdit ? $plan->loan_insurance_charge : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Interest Amount</label>
                        <input required readonly type="number" min="0" step="0.01" name="interest_amount"
                            class="form-control" value="{{ $isEdit ? $plan->interest_amount : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Interest Rate (%)</label>
                        <input required readonly type="number" min="0" step="0.01" name="interest_rate"
                            class="form-control" value="{{ $isEdit ? $plan->interest_rate : '0' }}">
                    </div>

                     <div class="form-group col-md-4">
                        <label>Other Charges</label>
                        <input required type="number" min="0" step="0.01" name="other_charges" class="form-control"
                            value="{{ $isEdit ? $plan->other_charges : '0' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Final Amount</label>
                        <input required readonly type="number" min="0" step="0.01" name="final_amount"
                            class="form-control" value="{{ $isEdit ? $plan->final_amount : '0' }}">
                    </div>

                   

                </div>

                <div class="submit-section">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa {{ $isEdit ? 'fa-save' : 'fa-plus' }}"></i>
                        {{ $isEdit ? 'Update Plan' : 'Save Plan' }}
                    </button>
                    <a href="{{ route('business.plan.view') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </form>





        </div>
    </div>


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
                }) => sum + sign * (+element.value || 0), 0).toFixed(2);
            };

            fields.forEach(({
                element
            }) => element && element.addEventListener('input', updateTotal));
            updateTotal();
        }

        document.addEventListener('DOMContentLoaded', () => {
            autoSum(['+other_charges', '+final_amount'], 'final_amount');
     
        });
    </script>






    <script>
document.addEventListener('DOMContentLoaded', function() {

    const categorySelect = document.getElementById('business_category');
    const loanAmountInput = document.querySelector('input[name="loan_amount"]');
    const extraAmountInput = document.querySelector('input[name="extra_amount"]');
    const membershipChargeInput = document.querySelector('input[name="membership_charge"]');
    const membershipPerInput = document.querySelector('input[name="membership_per"]');
    const numberOfDaysInput = document.querySelector('input[name="number_of_days"]');

    const processingChargeInput = document.querySelector('input[name="processing_charge"]');
    const loanInsuranceInput = document.querySelector('input[name="loan_insurance_charge"]');
    const interestAmountInput = document.querySelector('input[name="interest_amount"]');
    const finalAmountInput = document.querySelector('input[name="final_amount"]');
    const interestRateInput = document.querySelector('input[name="interest_rate"]');
    const emiAmountInput = document.querySelector('input[name="emi_amount"]');

    function getDaysBasedOnCategory() {
        const selected = parseInt(categorySelect.value);

        if (selected === 1 || selected === 2) {
            return 360; // Daily, Weekly
        } else if (selected === 3 || selected === 4) {
            return 365; // Bi-weekly, Monthly
        }
        return 365;
    }

    function calculateMembershipCharge() {
        const loanAmount = parseFloat(numberOfDaysInput.value) || 0;
        const membershipPer = parseFloat(membershipPerInput.value) || 0;
        const membershipCharge = (loanAmount * membershipPer);
        membershipChargeInput.value = membershipCharge.toFixed(2);
    }

    function calculateValues() {
        calculateMembershipCharge();

        const loanAmount = parseFloat(loanAmountInput.value) || 0;
        const extraAmount = parseFloat(extraAmountInput.value) || 0;
        const membershipCharge = parseFloat(membershipChargeInput.value) || 0;
        const numberOfDays = parseFloat(numberOfDaysInput.value) || 0;

        const processingCharge = loanAmount * 0.01;
        const loanInsuranceCharge = loanAmount * 0.02;
        const interestAmount = extraAmount - (processingCharge + loanInsuranceCharge + membershipCharge);
        const finalAmount = loanAmount + extraAmount;

        // ✔ Get customized 180/365 days
        const customDays = getDaysBasedOnCategory();

        let interestRate = 0;
        if (loanAmount > 0 && numberOfDays > 0) {
            interestRate = (((interestAmount * customDays) * 100) / numberOfDays) / loanAmount;
        }

        let emiAmount = 0;
        if (numberOfDays > 0) {
            emiAmount = (loanAmount + extraAmount) / numberOfDays;
        }

        processingChargeInput.value = processingCharge.toFixed(2);
        loanInsuranceInput.value = loanInsuranceCharge.toFixed(2);
        interestAmountInput.value = interestAmount.toFixed(2);
        finalAmountInput.value = finalAmount.toFixed(2);
        interestRateInput.value = interestRate.toFixed(2);
        emiAmountInput.value = emiAmount.toFixed(2);
    }

    // Trigger when fields change
    [
        loanAmountInput,
        extraAmountInput,
        membershipChargeInput,
        membershipPerInput,
        numberOfDaysInput,
        categorySelect
    ].forEach(input => {
        input.addEventListener('keyup', calculateValues);
        input.addEventListener('change', calculateValues);
    });

});
</script>









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
