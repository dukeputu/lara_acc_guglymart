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
                        <table id="fileTable1" class="display responsive nowrap table table-bordered table-striped"
                            style="width:100%">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>SL No</th>
                                    <th>Month</th>
                                    <th>Category</th>
                                    <th>EMI</th>
                                    <th>Investment</th>
                                    <th>Expense</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($updates as $index => $u)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $u->month_name }}</td>
                                        <td>{{ $u->business_plan_name ?? 'N/A' }}</td>
                                        <td>₹{{ number_format($u->today_emi, 2) }}</td>
                                        <td>₹{{ number_format($u->total_daily_colletion, 2) }}</td>
                                        <!-- mapped as today_investment -->
                                        <td>₹{{ number_format($u->today_total_loan_amount, 2) }}</td>
                                        <!-- mapped as today_expense -->
                                        <td>{{ \Carbon\Carbon::parse($u->date_entry)->format('d M, Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('daily.update.edit', $u->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <form action="{{ route('daily.update.delete', $u->id) }}" method="POST"
                                                style="display:inline-block;"
                                                onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
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
