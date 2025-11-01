@extends('layouts.app')
@section('title', 'App User List')

@section('content')


    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        {{-- <h3 class="box-title"> A View</h3> --}}

                        @if (request()->has('phone'))
                            <div class="alert alert-success">
                                Showing results for phone number: <strong>{{ request('phone') }}</strong>
                            </div>
                        @endif

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="fileTable1" class="display responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL No</th>
                                    <th>Name</th>


                                    <th>Active/Inactive</th>

                                    <th>More</th>

                                    <th>Login</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($appUsers as $index => $appUser)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td> {{ $appUser->app_u_name }}

                                            <br>
                                            {{ $appUser->phone_number }}
                                        </td>



                                        <td>
                                            <a href="{{ route('admin.toggleUserStatus', $appUser->id) }}"
                                                class="btn btn-sm {{ $appUser->status == 1 ? 'btn-danger' : 'btn-success' }}"
                                                title="{{ $appUser->status == 1 ? 'Click to deactivate user' : 'Click to activate user' }}">
                                                {{ $appUser->status == 1 ? '✅' : '❌' }}
                                            </a>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#1bankDetailsModal{{ $index }}">
                                                View More
                                            </button>
                                        </td>
                                        <td>

                                            <a target="_blank" href="{{ route('admin.loginAsUser', $appUser->id) }}"
                                                class="btn btn-success btn-sm">
                                                👤 Login
                                            </a>
                                        </td>


                                    </tr>



                                    <!-- Modal -->
                                    <div class="modal fade" id="bankDetailsModal{{ $index }}" tabindex="-1"
                                        role="dialog" aria-labelledby="modalLabel{{ $index }}">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h4 class="modal-title">
                                                        <b>{{ $appUser->app_u_name }}</b> - Bank Details<br>
                                                        Phone No: {{ $appUser->phone_number }}
                                                    </h4>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal"><span>&times;</span></button>
                                                </div>

                                                <div class="modal-body">
                                                    <p><strong>Introducer Name:</strong>
                                                        {{ $appUser->introducer_name ?? 'N/A' }}</p>
                                                    <p><strong>Introducer Phone:</strong>
                                                        {{ $appUser->introducer_phone ?? 'N/A' }}</p>
                                                    <p><strong>User Address:</strong>
                                                        {{ $appUser->app_u_address ?? 'N/A' }}</p>
                                                    <p><strong>User Email ID:</strong> {{ $appUser->user_email ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Bank Name:</strong> {{ $appUser->bank_name ?? 'N/A' }}</p>
                                                    <p><strong>IFSC Code:</strong> {{ $appUser->ifsc_code ?? 'N/A' }}</p>
                                                    <p><strong>AC Holder Name:</strong> {{ $appUser->app_u_name ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Bank AC No.:</strong>
                                                        {{ $appUser->bank_account_no ?? 'N/A' }}</p>
                                                    <p><strong>UPI ID:</strong> {{ $appUser->upi_id ?? 'N/A' }}</p>
                                                    <p><strong>Last Edit</strong>
                                                        {{ \Carbon\Carbon::parse($appUser->updated_at)->format('d-m-Y , h:i A') }}
                                                    </p>

                                                    <p><strong>UPI QR Code:</strong></p>

                                                    @if ($appUser->upi_qr_code)
                                                        <center>
                                                            <img src="{{ asset($appUser->upi_qr_code) }}" width="200"
                                                                alt="UPI QR Code">
                                                        </center>
                                                    @else
                                                        <p>N/A</p>
                                                    @endif
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Close</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

        </div>
    </section>
    </div>




@endsection





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
