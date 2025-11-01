@php
    $userId = session('app_user_id');
    $currentuser_wallet = DB::table('app_users')->where('id', $userId)->value('user_wallet');
    
@endphp


@extends('userApp.layouts.userAppLayout')
@section('title', 'Dashboard')

@section('content')



    <!-- App Header -->
    <div class="appHeader">
        <div class="left">
            <a href="{{ route('dashboard.app') }}" class="headerButton ">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            ALL PIN List
        </div>
        <div class="right">
            <span style="cursor: pointer;" onclick="location.reload();" class="headerButton ">
                <ion-icon name="refresh-outline"></ion-icon>
            </span>
        </div>

    </div>
    <!-- * App Header -->
    <style>
        table.dataTable.stripe tbody tr.odd,
        table.dataTable.display tbody tr.odd {
            background-color: #004aad !important;
        }

        table.dataTable tbody tr {
            background-color: #004aad !important;
        }

        table.dataTable.display tbody tr.even>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.even>.sorting_1 {
            background-color: #004aad !important;
        }

        table.dataTable.display tbody tr.odd>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.odd>.sorting_1 {
            background-color: #004aad !important;
        }
    </style>

    <style>
        .totalAmountAnimated {
            position: relative;
            display: inline-block;
            padding: 4px 12px;
            font-weight: bold;
            font-size: 0.7rem;
            color: #000;
            /* Keeps the text readable */
            background: linear-gradient(90deg, #ffe082, #ffe082, #fff8e1);
            background-size: 200% 100%;
            border-radius: 30px;
            animation:
                blinkEffect 1.5s steps(2, start) infinite,
                pulseGlow 2.5s ease-in-out infinite,
                shimmerBg 3s linear infinite;

            /* Outer glow (shadow) */
            box-shadow:
                0 0 10px rgba(255, 193, 7, 0.6),
                0 0 20px rgba(255, 193, 7, 0.5),
                0 0 30px rgba(255, 193, 7, 0.4);
        }

        /* Blinking effect (opacity toggle) */
        @keyframes blinkEffect {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.9;
            }
        }

        /* Glowing text shadow */
        @keyframes pulseGlow {
            0% {
                text-shadow: 0 0 6px rgba(255, 193, 7, 0.5);
            }

            50% {
                text-shadow: 0 0 15px rgba(255, 193, 7, 1);
            }

            100% {
                text-shadow: 0 0 6px rgba(255, 193, 7, 0.5);
            }
        }

        /* Background shimmer animation */
        @keyframes shimmerBg {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
    </style>

    <!-- App Capsule -->
    <div id="appCapsule">
        @php
            $totalPins = 0;
            $activePins = 0;
        @endphp
        <!-- Transactions -->
        <div class="section mt-2">



            <div class="table-responsive">
                <table id="fileTable1" class="table bg-primary display nowrap " style="width:100%">

                    <thead>
                        <tr>
                            <th>PIN</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>


                    @foreach ($pinsPaginator as $pin)
                        @php
                            $totalPins++;
                            if ($pin['status'] != 1) {
                                $activePins++;
                            }
                        @endphp

                        <tr id="row-{{ $pin['pin'] }}">
                            <td>{{ $pin['pin'] }}</td>
                            <td>
                                @if ($pin['status'] == 1)
                                    <button class="activatePinBtn totalAmountAnimated" data-pin="{{ $pin['pin'] }}"
                                        data-req="{{ $pin['req_id'] }}">
                                        Click To Activate PIN
                                    </button>
                                @else
                                    <span class="badge bg-success">Used</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    <tfoot>
                        <tr>
                            <td class="text-end"><strong>Total PIN:</strong></td>
                            <td class="text-end"><strong id="totalPins"> {{ $pins }}</strong></td>

                        </tr>
                        <tr>
                            <td class="text-end"><strong>Active PINs:</strong></td>
                            <td class="text-end"><strong id="activePins">{{ $status2Count }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-end"><strong>Inactive PINs:</strong></td>
                            <td class="text-end"><strong id="inactivePins">{{ $pins - $status2Count }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-end"><strong>Total Invested Amount:</strong></td>
                            <td class="text-end"><strong>₹  {{ is_numeric($currentuser_wallet) ? number_format($currentuser_wallet) : 'Balance Not Add' }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-end"><strong>PIN Active Balance:</strong></td>
                            <td class="text-end"><strong id="activeBalance">₹ {{ $status2Count * 50 }}</strong></td>
                        </tr>
                    </tfoot>


                </table>




            </div>

            <form method="POST" action="{{ route('userPin.activateByCount') }}" onsubmit="Did You?">
                @csrf
                <input type="hidden" name="req_id" value="{{ $allPins1[0]['req_id'] ?? '' }}">

                <div class="mb-3">
                    <label for="count" class="form-label">Enter number of inactive pins to activate:</label>
                    <input type="number" name="count" id="count" min="1" max="{{ $pins - $activePins }}"
                        class="form-control" placeholder="Enter Pins" required>
                    <button type="submit" class="btn btn-primary">Activate Pins</button>
                </div>


            </form>





        </div>
        <!-- * Transactions -->
    </div>
    <!-- * App Capsule -->


    <script>
        document.querySelectorAll(".activatePinBtn").forEach(btn => {
            btn.addEventListener("click", function() {
                let pin = this.dataset.pin;
                let reqId = this.dataset.req;
                // Assuming you already initialized your table
                let table = $('#fileTable1').DataTable();

                fetch("{{ route('userPin.activate') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            pin: pin,
                            req_id: reqId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            let row = document.getElementById("row-" + pin);
                            row.querySelector("td:nth-child(2)").innerHTML =
                                '<span class="badge bg-success">Used</span>';

                            // Update footer numbers
                            // document.getElementById("totalPins").innerText = 2;
                            // ✅ Recalculate from DataTable (all rows, not just current page)
                            let allData = table.rows().nodes();

                            let usedCount = $(allData).find(".badge.bg-success").length;
                            let inactiveCount = $(allData).find(".activatePinBtn").length;

                            document.getElementById("activePins").innerText = usedCount;
                            document.getElementById("inactivePins").innerText = inactiveCount1;
                            document.getElementById("activeBalance").innerText = usedCount * 50;
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(err => console.error(err));
            });
        });
    </script>












    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS & CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>




    <script>
        $(document).ready(function() {
            $('#fileTable1').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 20, 50],
                scrollX: false,
                responsive: true,
                order: [
                    [1, 'asc']
                ], // sort by Status column
                columnDefs: [{
                    targets: 1, // Status column
                    orderDataType: "pin-status"
                }]
            });

            // Calculate totals
            const jsonPins = {!! json_encode($decodedPins ?? []) !!};
            let invested = 0;
            let wallet = 0;
            const pinValue = 50;

            jsonPins.forEach(pin => {
                invested += pinValue;
                if (pin.status == 2) {
                    wallet += pinValue;
                }
            });

            $('#totalInvested').text('₹ ' + invested.toFixed(2));
            $('#totalWallet').text('₹ ' + wallet.toFixed(2));
        });
    </script>




@endsection
