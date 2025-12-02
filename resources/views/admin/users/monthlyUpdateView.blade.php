@php
    $adminSession = session('member_id');
@endphp

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
                                    <th>Director Loan</th>
                                    <th>Bank Loan</th>
                                    {{-- <th>Investment For Invertor</th> --}}
                                    {{-- <th>Date</th> --}}
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach ($updates as $index => $u)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        {{-- <td>{{ $u->month_name }}</td> --}}

                                        <td>{{ \Carbon\Carbon::parse($u->month_name . '-01')->format('F Y') }}</td>

                                     
                                        {{-- <td>{{ $u->business_plan_name ?? 'N/A' }}</td> --}}
                                        <td>₹{{ number_format($u->director_loan, 2) }}</td>
                                        <td>₹{{ number_format($u->bank_loan, 2) }}</td>
                                        {{-- <!-- mapped as today_investment --> --}}
                                        {{-- <td>₹{{ number_format($u->investment_for_invertor, 2) }}</td> --}}
                                        {{-- <!-- mapped as today_expense --> --}}
                                        {{-- <td>{{ \Carbon\Carbon::parse($u->updated_at)->format('d M, Y') }}</td> --}}


                                        <td class="text-center">
                                            <a href="{{ route('monthly.update.edit', $u->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if ($adminSession)
                                                <a href="{{ route('generic.delete', ['table' => 'monthly_update', 'id' => $u->id]) }}"
                                                    onclick="return confirm('Are you sure you want to delete {{ $u->month_name }}?')"
                                                    class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif


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
