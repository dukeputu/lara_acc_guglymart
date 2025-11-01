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
                action="{{ $isEdit ? route('business.plan.updateRd', $plan->id) : route('business.plan.storeRd') }}"
                enctype="multipart/form-data">
                @csrf

                <div class="section-header">
                    <div class="section-icon"><i class="fa fa-briefcase"></i></div>
                    <h3>{{ $isEdit ? 'Edit RD Business Plan' : 'Add RD Business Plan' }}</h3>
                </div>

                <div class="row">
                    
                  
                    <div class="form-group col-md-4">
                        <label>RD Amount <sup>*</sup></label>
                        <input type="number" step="0.01" name="rd_amount" class="form-control"
                            value="{{ $isEdit ? $plan->rd_amount : '100' }}" required>
                    </div>

              

                    <div class="form-group col-md-4">
                        <label>RD Interest <sup>*</sup></label>
                        <input type="number" step="0.01" name="rd_interest" class="form-control"
                            value="{{ $isEdit ? $plan->rd_interest : '0' }}" required>
                    </div>

                </div>

                <div class="submit-section">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa {{ $isEdit ? 'fa-save' : 'fa-plus' }}"></i>
                        {{ $isEdit ? 'Update Plan' : 'Save Plan' }}
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





