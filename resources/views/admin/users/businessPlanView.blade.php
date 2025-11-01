@extends('layouts.user')
@section('title', 'App User List')

@section('content')


    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        {{-- <h3 class="box-title"> A View</h3> --}}



                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="fileTable1" class="display responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No</th>
                                    {{-- <th>Added By</th> --}}
                                    <th>Category</th>
                                    <th>Loan</th>
                                    <th>Interest</th>
                                    <th>Final</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $index => $plan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        {{-- <td>{{ $plan->add_user_name }}</td> --}}
                                        <td>{{ $plan->business_category_name }}</td>
                                        <td>{{ $plan->loan_amount }}</td>
                                        <td>{{ $plan->interest_rate }}%</td>
                                        <td>{{ $plan->final_amount }}</td>
                                        <td>
                                            <a href="{{ route('business.plan.toggle', $plan->id) }}"
                                                class="btn btn-sm {{ $plan->status == 1 ? 'btn-danger' : 'btn-success' }}">
                                                {{ $plan->status == 1 ? '✅ Active' : '❌ Inactive' }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('business.plan.edit', $plan->id) }}"
                                                class="btn btn-primary btn-sm">✏️ Edit</a>
                                            <a href="{{ route('business.plan.delete', $plan->id) }}"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this plan?')">🗑
                                                Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>



                    </div>
                </div>
            </div>

        </div>
    </section>
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


@endsection
