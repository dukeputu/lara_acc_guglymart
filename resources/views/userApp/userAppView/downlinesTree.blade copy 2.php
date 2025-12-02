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
            Down Lines
        </div>

    </div>
    <!-- * App Header -->


    <!-- App Capsule -->
    <div id="appCapsule">

        <!-- Transactions -->
        <div class="section mt-2">



            <style>
                .user-card strong {
                    display: unset;
                    font-size: 11px;
                    color: #fff;
                    line-height: 1.3em;
                    margin-top: 8px;
                }




                .user-card {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                    color: white;
                    padding: 20px;
                    transition: all 0.3s ease;
                    border: none;
                }

                .user-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
                }

                .user-card h4 {
                    color: #fff;
                    font-weight: bold;
                    margin-bottom: 15px;
                    border-bottom: 2px solid rgba(255, 255, 255, 0.3);
                    padding-bottom: 10px;
                }

                .user-card p {
                    margin-bottom: 8px;
                    font-size: 14px;
                }

                .badge-level {
                    background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
                    padding: 5px 12px;
                    border-radius: 20px;
                    font-weight: bold;
                    display: inline-block;
                }

                .accordion-button {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    font-weight: bold;
                    font-size: 16px;
                }

                .accordion-button:not(.collapsed) {
                    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                    color: white;
                }

                .accordion-button:focus {
                    box-shadow: none;
                    border-color: rgba(0, 0, 0, .125);
                }

                .list-group-item {
                    border-left: 4px solid #667eea;
                    margin-bottom: 10px;
                    transition: all 0.2s ease;
                }

                .list-group-item:hover {
                    background: #f8f9fa;
                    transform: translateX(5px);
                }

                .openIncomeModal {
                    cursor: pointer;
                    text-decoration: underline;
                    transition: color 0.2s ease;
                }

                .openIncomeModal:hover {
                    color: #f5576c !important;
                }

                .status-badge {
                    display: inline-block;
                    padding: 3px 10px;
                    border-radius: 12px;
                    font-size: 12px;
                    font-weight: bold;
                }

                .status-eligible {
                    background: #28a745;
                    color: white;
                }

                .status-not-eligible {
                    background: #dc3545;
                    color: white;
                }

                .mlm-tree {
                    margin-top: 30px;
                }

                .section-title {
                    text-align: center;
                    margin: 40px 0 30px;
                    font-weight: bold;
                    color: #667eea;
                    font-size: 28px;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                }
            </style>

            <div class="container mt-5">

                {{-- User's Own Details Section --}}
                <h2 class="section-title">🌟 Your Profile 🌟</h2>

                @if ($userBusinessData)
                    <div class="row justify-content-center mb-5">
                        <div class="col-md-8">
                            <div class="user-card">
                                <h4 class="text-center">👤 {{ $user->app_u_name }}</h4>

                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>📞 Phone:</strong> {{ $user->phone_number }}</p>
                                        <p><strong>💼 Self Business:</strong>
                                            ₹{{ number_format($userBusinessData['self_business'], 2) }}</p>
                                        <p><strong>💰 Total Business:</strong>
                                            ₹{{ number_format($userBusinessData['total_business'], 2) }}</p>
                                        <p><strong>🏆 Qualified Level:</strong>
                                            <span class="badge-level">Level
                                                {{ $userBusinessData['qualified_level'] }}</span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p><strong>💵 Monthly Salary:</strong>
                                            ₹{{ number_format($userBusinessData['salary'], 2) }}</p>
                                        <p><strong>📅 Salary Duration:</strong> {{ $userBusinessData['salary_months'] }}
                                            Months</p>
                                        <p><strong>📈 Next Level Business Needed:</strong>
                                            ₹{{ number_format($userBusinessData['business_needed'], 2) }}
                                        </p>
                                        <p><strong>🔐 40:60 Status:</strong>
                                            <span
                                                class="status-badge {{ $userBusinessData['is_4060_compliant'] ? 'status-eligible' : 'status-not-eligible' }}">
                                                {{ $userBusinessData['ratio_status'] }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <p><strong>📊 Top Leg Business:</strong>
                                        ₹{{ number_format($userBusinessData['top_leg_business'], 2) }}
                                        ({{ $userBusinessData['top_leg_percentage'] }}%)
                                    </p>
                                    <p><strong>🧾 Salary Status:</strong>
                                        <span
                                            class="status-badge {{ $userBusinessData['salary_eligible'] === 'Yes' ? 'status-eligible' : 'status-not-eligible' }}">
                                            {{ $userBusinessData['salary_eligible'] }}
                                        </span>
                                    </p>
                                </div>

                                @if ($userBusinessData['salary_info'])
                                    <div class="mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.3);">
                                        <p><strong>💳 Salary Details:</strong></p>
                                        <ul>
                                            <li>Amount:
                                                ₹{{ number_format($userBusinessData['salary_info']['salary_amount'], 2) }}/month
                                            </li>
                                            <li>Months Paid:
                                                {{ $userBusinessData['salary_info']['months_paid'] }}/{{ $userBusinessData['salary_info']['months_total'] }}
                                            </li>
                                            <li>Next Payment: {{ $userBusinessData['salary_info']['next_payment_date'] }}
                                            </li>
                                            <li>Status: <span
                                                    class="badge bg-success">{{ ucfirst($userBusinessData['salary_info']['status']) }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Downline Tree Section --}}
            
<h3 class="section-title">Your Downline Network</h3>
                <div class="mlm-tree">
                    @if (!empty($downlinesByLevel))
                        
                        <div class="accordion" id="downlineAccordion">
                            @foreach ($downlinesByLevel as $level => $members)
                                @if (!empty($members))
                                    <div class="accordion-item mb-3">
                                        <h2 class="accordion-header" id="heading{{ $level }}">
                                            <button class="accordion-button {{ $level == 1 ? '' : 'collapsed' }}"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $level }}"
                                                aria-expanded="{{ $level == 1 ? 'true' : 'false' }}"
                                                aria-controls="collapse{{ $level }}">
                                                📊 Level {{ $level }} - {{ count($members) }} Members
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $level }}"
                                            class="accordion-collapse collapse {{ $level == 1 ? 'show' : '' }}"
                                            aria-labelledby="heading{{ $level }}"
                                            data-bs-parent="#downlineAccordion">
                                            <div class="accordion-body">
                                                <div class="list-group">
                                                    @foreach ($members as $member)
                                                        <div class="list-group-item">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div class="flex-grow-1">
                                                                    <h5 class="mb-2">
                                                                        👤 <strong>{{ $member['name'] }}</strong>
                                                                        <span class="text-primary openIncomeModal"
                                                                            data-user-name="{{ $member['name'] }}"
                                                                            data-user-phone="{{ $member['phone'] }}"
                                                                            data-user-id="{{ $member['user_id'] }}"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#incomeDetailModal">
                                                                            [{{ $member['phone'] }}]
                                                                        </span>
                                                                    </h5>

                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <p class="mb-1">💼 <strong>Self
                                                                                    Business:</strong>
                                                                                ₹{{ number_format($member['self_business'], 2) }}
                                                                            </p>
                                                                            <p class="mb-1">💰 <strong>Total
                                                                                    Business:</strong>
                                                                                ₹{{ number_format($member['total_business'], 2) }}
                                                                            </p>
                                                                            <p class="mb-1">🏆 <strong>Level:</strong>
                                                                                <span class="badge bg-primary">Level
                                                                                    {{ $member['qualified_level'] }}</span>
                                                                            </p>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <p class="mb-1">💵 <strong>Salary:</strong>
                                                                                ₹{{ number_format($member['salary'], 2) }}
                                                                                ({{ $member['salary_months'] }} Mo.)
                                                                            </p>
                                                                            <p class="mb-1">📈 <strong>Next Level
                                                                                    Need:</strong>
                                                                                ₹{{ number_format($member['business_needed'], 2) }}
                                                                            </p>
                                                                            <p class="mb-1">
                                                                                🧾 <strong>Salary Eligible:</strong>
                                                                                {!! $member['salary_eligible'] === 'Yes'
                                                                                    ? '<span class="badge bg-success">✅ Yes</span>'
                                                                                    : '<span class="badge bg-danger">❌ No</span>' !!}
                                                                            </p>
                                                                            <p class="mb-1">
                                                                                🔐 <strong>40:60 Status:</strong>
                                                                                {{-- @php
                                                                                    dd($member['is_4060_compliant']);
                                                                                @endphp --}}
                                                                                {!! $member['is_4060_compliant']
                                                                                    ? '<span class="badge bg-success">✅ Unlocked</span>'
                                                                                    : '<span class="badge bg-warning text-dark">🔒 Locked</span>' !!}
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mt-2 pt-2"
                                                                        style="border-top: 1px solid #dee2e6;">
                                                                        <small>
                                                                            <strong>📊 Top Leg:</strong>
                                                                            ₹{{ number_format($member['top_leg_business'], 2) }}
                                                                            ({{ $member['top_leg_percentage'] }}%)
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> You don't have any downline members yet.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Income Detail Modal --}}
            <div class="modal fade" id="incomeDetailModal" tabindex="-1" aria-labelledby="incomeDetailModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="incomeDetailModalLabel">Income Details</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6>User: <span id="modalUserName"></span></h6>
                            <p>Phone: <span id="modalUserPhone"></span></p>
                            <hr>
                            <div id="modalIncomeDetails">
                                <p>Loading income details...</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


            <script>
                // Handle income modal click
                document.addEventListener('DOMContentLoaded', function() {
                    const modalTriggers = document.querySelectorAll('.openIncomeModal');

                    modalTriggers.forEach(trigger => {
                        trigger.addEventListener('click', function() {
                            const userName = this.dataset.userName;
                            const userPhone = this.dataset.userPhone;
                            const userId = this.dataset.userId;

                            document.getElementById('modalUserName').textContent = userName;
                            document.getElementById('modalUserPhone').textContent = userPhone;

                            // Fetch income details via AJAX
                            fetch(`/api/user-income-details/${userId}`)
                                .then(response => response.json())
                                .then(data => {
                                    let html = '<table class="table table-striped">';
                                    html +=
                                        '<thead><tr><th>Date</th><th>Type</th><th>Amount</th><th>Status</th></tr></thead>';
                                    html += '<tbody>';

                                    if (data.transactions && data.transactions.length > 0) {
                                        data.transactions.forEach(txn => {
                                            html += `<tr>
                                    <td>${txn.date}</td>
                                    <td>${txn.type}</td>
                                    <td>₹${txn.amount}</td>
                                    <td><span class="badge bg-success">${txn.status}</span></td>
                                </tr>`;
                                        });
                                    } else {
                                        html +=
                                            '<tr><td colspan="4" class="text-center">No transactions found</td></tr>';
                                    }

                                    html += '</tbody></table>';
                                    document.getElementById('modalIncomeDetails').innerHTML = html;
                                })
                                .catch(error => {
                                    document.getElementById('modalIncomeDetails').innerHTML =
                                        '<div class="alert alert-danger">Failed to load income details</div>';
                                });
                        });
                    });
                });
            </script>








        </div>
        <!-- * Transactions -->
    </div>
    <!-- * App Capsule -->




    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>





@endsection
