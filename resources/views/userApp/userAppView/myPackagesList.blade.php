@php
    $userId = session('app_user_id');
    $userName = session('app_user_name');
    $userPhone = session('app_user_phone');
    $userPic = session('app_user_photo');
    $userWallet = session('app_user_wallet');

    // dd($userPic);
    // exit;
    /*

@if ($userPic)
<img src="{{ url(asset($userPic)) }}" alt="image" class="imaged w32">
@endif
*/

@endphp

@extends('userApp.layouts.userAppLayout')
@section('title', 'Dashboard')

@section('content')

    <style>
        .card-block {
            height: 16rem;
        }
    </style>

    <!-- App Header -->
    <div class="appHeader">
        <div class="left">
            <a href="{{ route('dashboard.app') }}" class="headerButton ">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            Packages List


        </div>

    </div>
    <!-- * App Header -->
    <br>





    <!-- App Capsule -->
    <div id="appCapsule">


        <h1 class="text-center pt-2">My Packages </h1>

        <style>
            /* 🔹 Premium Dark Metallic Card */
            .card {
                background: linear-gradient(145deg, #1a1a1f, #23232b);
                border-radius: 18px !important;
                border: 1px solid rgba(255, 255, 255, 0.05) !important;
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.8),
                    inset 0 1px 1px rgba(255, 255, 255, 0.08);
                transition: all 0.3s ease-in-out;
                color: #e5e5e5;
                backdrop-filter: blur(6px);
            }

            /* Hover Effect - subtle metallic glow */
            .card:hover {
                transform: translateY(-6px);
                box-shadow: 0 14px 28px rgba(0, 0, 0, 0.95),
                    inset 0 2px 2px rgba(255, 255, 255, 0.15);
                border: 1px solid rgba(212, 175, 55, 0.4) !important;
                /* Gold highlight */
            }

            /* 🔹 Title */
            .card-body h5 {
                font-weight: 600;
                letter-spacing: 0.5px;
                color: #ffffff;
                text-shadow: 0 0 6px rgba(0, 74, 173, 0.4);
                /* Royal Blue glow */
            }

            /* 🔹 Metallic Badges */
            .badge-primary {
                background: linear-gradient(145deg, #004aad, #003580) !important;
                /* Royal Blue */
                box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.2);
                border: 1px solid rgba(0, 0, 0, 0.5);
                font-weight: 500;
            }

            .badge-success {
                background: linear-gradient(145deg, #d4af37, #a67c00) !important;
                /* Gold */
                box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.25);
                border: 1px solid rgba(0, 0, 0, 0.6);
                font-weight: 500;
                color: #fff !important;
            }

            .badge-warning {
                background: linear-gradient(145deg, #ffb300, #f57c00) !important;
                border: 1px solid rgba(0, 0, 0, 0.6);
                font-weight: 500;
            }

            /* 🔹 Amount Text with metallic glow */
            .text-success {
                font-weight: 600;
                color: #d4af37 !important;
                /* Gold */
                text-shadow: 0 0 8px rgba(212, 175, 55, 0.5);
            }

            .text-danger {
                font-weight: 600;
                color: #ff5252 !important;
                text-shadow: 0 0 8px rgba(255, 82, 82, 0.4);
            }

            .text-info {
                font-weight: 600;
                color: #004aad !important;
                /* Royal Blue */
                text-shadow: 0 0 8px rgba(0, 74, 173, 0.5);
            }

            /* 🔹 Sub Text */
            .card-body small {
                font-size: 0.85rem;
                color: #b0b0b0;
                letter-spacing: 0.3px;
            }
        </style>

        <!-- Stats -->
        <div class="section  ">

            <div class="row mt-3 ">
                @foreach ($appPackages as $pkg)
                    <div class="card mb-3 shadow-sm border-left-{{ $pkg->type_id == 2 ? 'primary' : 'success' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">
                                    <span class="badge badge-{{ $pkg->type_id == 2 ? 'primary' : 'success' }}">
                                        {{ $pkg->type_name }}
                                    </span>
                                </h5>
                                <h5 class="mb-0 {{ $pkg->type_id == 3 ? 'text-success' : 'text-danger' }}">
                                    ₹{{ number_format($pkg->amount, 2) }}
                                    <span class="text-muted small">{{ $pkg->type_id == 2 ? 'Dr' : 'Cr' }}</span>
                                </h5>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                @if ($pkg->type_id == 2)
                                    <div class="">
                                        <strong class="d-block">📦 Package Details:</strong>
                                        <small><strong>Name:</strong> {{ $pkg->package_name }}</small><br>
                                        <small><strong>Package Amount:</strong>
                                            ₹{{ number_format($pkg->package_amount, 2) }}</small><br>
                                        <small><strong>Get Total Amount:</strong>
                                            ₹{{ number_format($pkg->package_total_amount, 2) }}</small><br>
                                        <small><strong>Package %:</strong> {{ $pkg->package_payout_per }}%</small><br>
                                        <small><strong>Time Duration:</strong> {{ $pkg->package_time_duration }}
                                            Months</small><br>
                                    </div>
                                @endif


                                <p class="">

                                    <strong>Status:</strong>
                                    <span class="badge badge-{{ $pkg->status == 'Done' ? 'success' : 'warning' }}">
                                        {{ $pkg->status }}
                                    </span>

                                    <br>
                                    <strong>Wallet Before:</strong> ₹{{ number_format($pkg->wallet_before, 2) }}<br>
                                    <strong>Wallet After:</strong> ₹{{ number_format($pkg->wallet_after, 2) }}<br>
                                    <strong>Requested:</strong>
                                    {{ \Carbon\Carbon::parse($pkg->requested_at)->format('d-m-Y h:i A') }}<br>
                                    @if ($pkg->done_at)
                                        <strong>Done At:</strong>
                                        {{ \Carbon\Carbon::parse($pkg->done_at)->format('d-m-Y h:i A') }}
                                    @endif
                                </p>

                            </div>

                        </div>
                    </div>
                @endforeach
            </div>




        </div>
        <!-- * Stats -->





    </div>
    <!-- * App Capsule -->












@endsection
