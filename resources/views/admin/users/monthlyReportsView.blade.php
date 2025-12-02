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
                                    {{-- <th>Date</th> --}}
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($months as $index => $m)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $m['text'] }} {{ $m['year'] }}</td>
                                        {{-- <td>{{ $m['format'] }}</td> --}}
                                        <td class="text-center">




                                            @php
                                                // Convert month format (e.g., "2025-02") to a date (10th of that month)
                                                $monthDate10 = \Carbon\Carbon::parse($m['format'] . '-10');
                                                $today = \Carbon\Carbon::today();
                                            @endphp

                                            @if (in_array($m['format'], $availableMonths) && $today->gt($monthDate10))
                                                {{-- After 10th → Allow Download --}}
                                                <a target="_blank" href="{{ url('monthly/report/' . $m['format']) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download"></i> Download Report
                                                </a>

                                                <a href="{{ route('monthly.report.pdf', [$m['format'], $userId]) }}" class="btn btn-primary">Download PDF</a>


             

                                            @elseif (in_array($m['format'], $availableMonths))
                                                {{-- Before or On 10th → Show View Disabled --}}
                                                <button class="btn btn-sm btn-success" disabled>
                                                    <i class="fas fa-check"></i> After Date 10th View
                                                </button>
                                            @else
                                                {{-- Not in available months --}}
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fas fa-ban"></i> Not Available
                                                </button>
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
