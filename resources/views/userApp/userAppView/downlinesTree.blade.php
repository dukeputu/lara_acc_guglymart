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
    {{-- 
  
  .user-card strong {
                    display: unset;
                    font-size: 11px;
                    color: #fff;
                    line-height: 1.3em;
                    margin-top: 8px;
                }
                    
                --}}

    <!-- App Capsule -->
    <div id="appCapsule">





        <style>
            .user-card strong {
                display: unset;
                font-size: 11px;
                color: unset;
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

            .earnings-box {
                background: rgba(255, 255, 255, 0.2);
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                margin-bottom: 15px;
                transition: transform 0.3s ease;
            }

            .earnings-box:hover {
                transform: translateY(-5px);
            }

            .earnings-box h6 {
                margin-bottom: 10px;
                opacity: 0.9;
                font-size: 14px;
            }

            .earnings-box h4 {
                font-size: 32px;
                font-weight: bold;
                margin: 10px 0;
            }

            .earnings-box small {
                opacity: 0.8;
                font-size: 12px;
            }

            .commission-table {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 10px;
                overflow: hidden;
                color: #333;
            }

            .commission-table thead {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .commission-table thead th {
                border: none;
                padding: 12px;
                font-weight: 600;
            }

            .commission-table tbody td {
                padding: 10px 12px;
                border-color: #e9ecef;
            }

            .commission-table tfoot tr {
                font-weight: bold;
            }

            .commission-table tfoot tr:nth-child(1) {
                background: rgba(102, 126, 234, 0.2);
            }

            .commission-table tfoot tr:nth-child(2) {
                background: rgba(102, 126, 234, 0.3);
            }

            .commission-table tfoot tr:nth-child(3) {
                background: rgba(102, 126, 234, 0.4);
                font-size: 16px;
            }

            /* Nested Accordion Styles */
            .nested-accordion {
                margin-left: 20px;
                border-left: 3px solid #667eea;
            }

            .accordion-button {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                font-weight: bold;
                font-size: 15px;
                position: relative;
            }

            .accordion-button:not(.collapsed) {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                color: white;
            }

            .accordion-button:focus {
                box-shadow: none;
                border-color: rgba(0, 0, 0, .125);
            }

            /* Level-based colors for nested accordions */
            .level-1 .accordion-button {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .level-2 .accordion-button {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }

            .level-3 .accordion-button {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }

            .level-4 .accordion-button {
                background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            }

            .level-5 .accordion-button {
                background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            }

            .level-6 .accordion-button {
                background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
            }

            .level-7 .accordion-button {
                background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            }

            .level-8 .accordion-button {
                background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            }

            .level-9 .accordion-button {
                background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            }

            .level-10 .accordion-button {
                background: linear-gradient(135deg, #ff6e7f 0%, #bfe9ff 100%);
            }

            .member-count-badge {
                position: absolute;
                top: 5px;
                left: 10px;
                background: #ff6b6b;
                color: white;
                padding: 3px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: bold;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            }

            .user-info-card {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 15px;
                margin-bottom: 15px;
                border-left: 4px solid #667eea;
                transition: all 0.2s ease;
            }

            .user-info-card:hover {
                transform: translateX(5px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

            .section-title {
                text-align: center;
                margin: 40px 0 30px;
                font-weight: bold;
                color: #667eea;
                font-size: 28px;
                text-transform: uppercase;
                letter-spacing: 2px;
            }

            .tree-path {
                font-size: 12px;
                color: #6c757d;
                margin-bottom: 5px;
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
                                        <span class="badge-level">Level {{ $userBusinessData['qualified_level'] }}</span>
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <p><strong>💵 Monthly Salary:</strong>
                                        ₹{{ number_format($userBusinessData['salary'], 2) }}</p>
                                    <p><strong>📅 Salary Duration:</strong> {{ $userBusinessData['salary_months'] }} Months
                                    </p>
                                    <p><strong>📈 Next Level Need:</strong>
                                        ₹{{ number_format($userBusinessData['business_needed'], 2) }}
                                    </p>
                                    <p><strong>🔐 40:60 Status:</strong>
                                        @if ($userBusinessData['qualified_level'] >= 4)
                                            <span
                                                class="status-badge {{ $userBusinessData['is_4060_compliant'] ? 'status-eligible' : 'status-not-eligible' }}">
                                                {{ $userBusinessData['ratio_status'] }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">N/A (Level 1-3)</span>
                                        @endif
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
                                        <li>Next Payment: {{ $userBusinessData['salary_info']['next_payment_date'] }}</li>
                                        <li>Status: <span
                                                class="badge bg-success">{{ ucfirst($userBusinessData['salary_info']['status']) }}</span>
                                        </li>
                                    </ul>
                                </div>
                            @endif

                            {{-- Monthly Commission Breakdown --}}
                            @if ($userBusinessData['monthly_commissions'])
                                <hr style="border-color: rgba(255,255,255,0.3); margin: 30px 0;">

                                <h3 class="" style="color: white;"> This Month's Earnings
                                    ({{ $userBusinessData['monthly_commissions']['month_name'] }})</h3>

                                <div class="row text-center mb-4">
                                    <div class="col-md-4">
                                        <div class="earnings-box">
                                            <h6>💰 Referral Income</h6>
                                            <h4>₹{{ number_format($userBusinessData['monthly_commissions']['total_referral_income'], 0) }}
                                            </h4>
                                            <small>{{ $userBusinessData['monthly_commissions']['transaction_count'] }}
                                                transactions</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="earnings-box">
                                            <h6>💵 Salary</h6>
                                            <h4>₹{{ number_format($userBusinessData['monthly_commissions']['total_salary'], 0) }}
                                            </h4>
                                            <small>{{ $userBusinessData['qualified_level'] >= 4 ? 'Level ' . $userBusinessData['qualified_level'] . ' Salary' : 'No Salary' }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="earnings-box">
                                            <h6>🎯 Total Earnings</h6>
                                            <h4>₹{{ number_format($userBusinessData['monthly_commissions']['total_monthly_earnings'], 0) }}
                                            </h4>
                                            <small>This Month</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6 class="mb-3">📈 Level-wise Commission Breakdown:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm commission-table">
                                            <thead>
                                                <tr>
                                                    <th>Level</th>
                                                    <th>Rate</th>
                                                    <th>Transactions</th>
                                                    <th>Amount Earned</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $hasCommissions = false;
                                                @endphp
                                                @foreach ($userBusinessData['monthly_commissions']['level_wise'] as $level => $data)
                                                    @if ($data['count'] > 0)
                                                        @php $hasCommissions = true; @endphp
                                                        <tr>
                                                            <td><strong>Level {{ $level }}</strong></td>
                                                            <td>{{ number_format($data['percentage'], 2) }}%</td>
                                                            <td>{{ $data['count'] }}</td>
                                                            <td><strong>₹{{ number_format($data['amount'], 0) }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                @if (!$hasCommissions)
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">No commissions
                                                            earned this month yet</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            @if ($hasCommissions)
                                                <tfoot>
                                                    <tr style="background: rgba(102, 126, 234, 0.2);">
                                                        <td colspan="3"><strong>Total Referral Income</strong></td>
                                                        <td><strong>₹{{ number_format($userBusinessData['monthly_commissions']['total_referral_income'], 0) }}</strong>
                                                        </td>
                                                    </tr>
                                                    @if ($userBusinessData['monthly_commissions']['total_salary'] > 0)
                                                        <tr style="background: rgba(102, 126, 234, 0.3);">
                                                            <td colspan="3"><strong>+ Salary Income</strong></td>
                                                            <td><strong>₹{{ number_format($userBusinessData['monthly_commissions']['total_salary'], 0) }}</strong>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr style="background: rgba(102, 126, 234, 0.4); font-size: 16px;">
                                                        <td colspan="3"><strong>🎯 TOTAL MONTHLY EARNINGS</strong></td>
                                                        <td><strong>₹{{ number_format($userBusinessData['monthly_commissions']['total_monthly_earnings'], 0) }}</strong>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-4 p-3" style="background: rgba(255,255,255,0.1); border-radius: 10px;">
                                    <h6 class="mb-2">💡 Commission Breakdown Explanation:</h6>
                                    <ul style="font-size: 14px; line-height: 1.8;">
                                        <li><strong>Level 1 (2%):</strong> Direct downline purchases - Highest commission
                                            rate</li>
                                        <li><strong>Level 2-3:</strong> Second and third generation downlines</li>
                                        <li><strong>Level 4-10 (0.25-0.75%):</strong> Deeper network levels - Lower rates
                                            but more volume</li>
                                        <li><strong>Salary:</strong> Monthly fixed income for Level 4+ qualifiers (if 40:60
                                            compliant)</li>
                                    </ul>
                                </div>

                                <div class="mt-3 text-center">
                                    <p style="opacity: 0.8; font-size: 14px;">
                                        📅 Data from: {{ \Carbon\Carbon::now()->startOfMonth()->format('F d, Y') }} to
                                        {{ \Carbon\Carbon::now()->endOfMonth()->format('F d, Y') }}<br>
                                        @if ($userBusinessData['salary_info'] && isset($userBusinessData['salary_info']['next_payment_date']))
                                            💳 Next salary payment:
                                            {{ \Carbon\Carbon::parse($userBusinessData['salary_info']['next_payment_date'])->format('F d, Y') }}
                                        @endif
                                    </p>
                                </div>
                            @endif





                        </div>
                    </div>
                </div>
            @endif

            {{-- Nested Downline Tree Section --}}
            <h2 class="section-title">Your Heartline ❤</h2>

            <div class="mlm-tree">
                @if (!empty($nestedDownlines))
                    {!! $nestedDownlines !!}
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

                                // Add summary
                                if (data.summary) {
                                    html = `
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Total Level Income:</strong> ₹${data.summary.total_income}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Total Salary:</strong> ₹${data.summary.total_salary}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Total Package Purchase:</strong> ₹${data.summary.total_package_purchase}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Current Wallet:</strong> ₹${data.summary.current_wallet}
                                    </div>
                                </div>
                                <hr>
                            ` + html;
                                }

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
