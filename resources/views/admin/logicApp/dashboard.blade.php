@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #5568d3;
            --success: #51cf66;
            --warning: #ffd43b;
            --danger: #ff6b6b;
            --info: #4facfe;
            --dark: #2d3748;
        }

        body {
            background: #f7fafc;
            font-family: 'Inter', -apple-system, sans-serif;
        }

        .dashboard-wrapper {
            padding: 20px;
        }

        /* Animated Header */
        .hero-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 40px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .hero-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 15s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .hero-stats {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }

        .hero-stat {
            flex: 1;
            text-align: center;
            padding: 15px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .hero-stat h3 {
            font-size: 36px;
            font-weight: 800;
            margin: 0;
        }

        /* Glass Morphism Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        /* Metric Cards */
        .metric-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, transparent, rgba(102, 126, 234, 0.1));
            border-radius: 0 15px 0 100%;
        }

        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .metric-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .metric-icon.primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .metric-icon.success {
            background: linear-gradient(135deg, #51cf66, #37b24d);
            color: white;
        }

        .metric-icon.warning {
            background: linear-gradient(135deg, #ffd43b, #fab005);
            color: white;
        }

        .metric-icon.danger {
            background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
            color: white;
        }

        .metric-icon.info {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
        }

        .metric-value {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 10px 0;
        }

        .metric-label {
            font-size: 14px;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-change {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        .metric-change.up {
            background: #d3f9d8;
            color: #2b8a3e;
        }

        .metric-change.down {
            background: #ffe3e3;
            color: #c92a2a;
        }

        /* Charts */
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Live Activity Feed */
        .activity-feed {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .activity-feed::-webkit-scrollbar {
            width: 6px;
        }

        .activity-feed::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .activity-feed::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }

        .activity-item {
            display: flex;
            align-items: start;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .activity-item:hover {
            background: #e9ecef;
            border-left-color: #667eea;
            transform: translateX(5px);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        /* Top Performers */
        .performer-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: white;
            border-radius: 12px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .performer-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .performer-rank {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 18px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            flex-shrink: 0;
        }

        .performer-rank.gold {
            background: linear-gradient(135deg, #ffd43b, #fab005);
        }

        .performer-rank.silver {
            background: linear-gradient(135deg, #adb5bd, #868e96);
        }

        .performer-rank.bronze {
            background: linear-gradient(135deg, #ff922b, #fd7e14);
        }

        /* Alert Cards */
        .alert-card {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            border-left: 4px solid;
            display: flex;
            align-items: start;
            gap: 15px;
            transition: all 0.3s ease;
        }

        .alert-card:hover {
            transform: translateX(5px);
        }

        .alert-card.danger {
            background: #ffe0e0;
            border-color: #ff6b6b;
        }

        .alert-card.warning {
            background: #fff9db;
            border-color: #ffd43b;
        }

        .alert-card.info {
            background: #e7f5ff;
            border-color: #4facfe;
        }

        .alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Progress Bars */
        .progress-bar-custom {
            height: 35px;
            border-radius: 20px;
            background: #e9ecef;
            overflow: hidden;
            position: relative;
            margin: 10px 0;
        }

        .progress-fill {
            height: 100%;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
            transition: width 1s ease;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        /* Quick Stats Grid */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .quick-stat-box {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .quick-stat-box h4 {
            font-size: 28px;
            font-weight: 800;
            margin: 10px 0;
        }

        /* Tabs */
        .custom-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .custom-tab {
            padding: 12px 24px;
            border: none;
            background: none;
            cursor: pointer;
            font-weight: 600;
            color: #718096;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .custom-tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-stats {
                flex-direction: column;
            }

            .metric-value {
                font-size: 24px;
            }
        }
    </style>

    <div class="dashboard-wrapper" style="display: none;">
        <!-- Animated Hero Header -->
        <div class="hero-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 style="font-size: 42px; font-weight: 900; margin-bottom: 10px;">
                        🚀 Dashboard
                    </h1>
                  

                    <p id="datetime" style="font-size: 14px; opacity: 0.8; margin-top: 5px;"></p>

                    <script>
                        const datetimeElement = document.getElementById('datetime');

                        function updateDateTime() {
                            const now = new Date();

                            const options = {
                                weekday: 'long',
                                month: 'long',
                                day: '2-digit',
                                year: 'numeric'
                            };
                            const dateStr = now.toLocaleDateString('en-US', options);

                            const hours = now.getHours();
                            const minutes = now.getMinutes();
                            const seconds = now.getSeconds();
                            const ampm = hours >= 12 ? 'PM' : 'AM';
                            const formattedHours = hours % 12 || 12;
                            const formattedMinutes = minutes.toString().padStart(2, '0');
                            const formattedSeconds = seconds.toString().padStart(2, '0');

                            const timeStr = `${formattedHours}:${formattedMinutes}:${formattedSeconds} ${ampm}`;

                            datetimeElement.innerHTML = `📅 ${dateStr} | 🕐 ${timeStr}`;
                        }

                        // Initial call + update every second
                        updateDateTime();
                        setInterval(updateDateTime, 1000);
                    </script>

                </div>




                <div class="col-md-6">
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <h3>₹{{ number_format($stats['total_business'] / 1000000, 1) }}M</h3>
                            <p style="margin: 0; font-size: 14px;">Total Business</p>
                        </div>
                        <div class="hero-stat">
                            <h3>{{ number_format($stats['total_users']) }}</h3>
                            <p style="margin: 0; font-size: 14px;">Network Size</p>
                        </div>
                        <div class="hero-stat">
                            <h3>{{ number_format($stats['compliance_rate'], 0) }}%</h3>
                            <p style="margin: 0; font-size: 14px;">Compliance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <!-- Quick Actions -->
        <div class="col-md-12 " style="display: none;">
            <div class="chart-card">
                <h5>⚡ Quick Actions</h5>
                <a href="{{ route('admin.mlm.tree') }}" class="quick-action-btn">
                    <i class="fas fa-sitemap"></i>
                    <div>View MLM Tree</div>
                </a>
                <a href="{{ route('admin.mlm.search') }}" class="quick-action-btn">
                    <i class="fas fa-search"></i>
                    <div>Search Users</div>
                </a>
                <a href="{{ route('admin.mlm.levelStats') }}" class="quick-action-btn">
                    <i class="fas fa-chart-bar"></i>
                    <div>Level Statistics</div>
                </a>
                <a href="{{ route('admin.mlm.export') }}" class="quick-action-btn">
                    <i class="fas fa-file-export"></i>
                    <div>Export Data</div>
                </a>
            </div>
        </div>


        <!-- Quick Stats Grid -->
        <div class="quick-stats">
            <div class="quick-stat-box" style="background: linear-gradient(135deg, #51cf66, #37b24d);">
                <div>Today's New Users</div>
                <h4>{{ $stats['today_new_users'] }}</h4>
            </div>
            <div class="quick-stat-box" style="background: linear-gradient(135deg, #ffd43b, #fab005);">
                <div>Today's Business</div>
                <h4>₹{{ number_format($stats['today_business'] / 1000, 0) }}K</h4>
            </div>
            <div class="quick-stat-box" style="background: linear-gradient(135deg, #ff6b6b, #ee5a6f);">
                <div>Pending Actions</div>
                <h4>{{ $stats['pending_withdrawal_count'] + $stats['pending_balance_requests'] }}</h4>
            </div>
            <div class="quick-stat-box" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                <div>This Month Payouts</div>
                <h4>₹{{ number_format($stats['month_payouts'] / 1000, 0) }}K</h4>
            </div>
        </div>

        <!-- Core Metrics Row 1 -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="metric-card">
                    <div class="metric-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="metric-label">Total Users</div>
                    <div class="metric-value">{{ number_format($stats['total_users']) }}</div>
                    <div class="metric-change up">
                        <i class="fas fa-arrow-up"></i>
                        +{{ $stats['month_new_users'] }} this month
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="metric-card">
                    <div class="metric-icon success">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="metric-label">Total Business</div>
                    <div class="metric-value">₹{{ number_format($stats['total_business'] / 1000000, 2) }}M</div>
                    <div class="metric-change up">
                        <i class="fas fa-fire"></i>
                        ₹{{ number_format($stats['month_business'] / 1000, 0) }}K this month
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="metric-card">
                    <div class="metric-icon warning">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="metric-label">Total Wallet Balance</div>
                    <div class="metric-value">₹{{ number_format($stats['total_wallet_balance'] / 1000, 0) }}K</div>
                    <div class="metric-change">
                        <i class="fas fa-coins"></i>
                        Pin: ₹{{ number_format($stats['total_pin_balance'] / 1000, 0) }}K
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="metric-card">
                    <div class="metric-icon danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="metric-label">Pending Withdrawals</div>
                    <div class="metric-value">{{ $stats['pending_withdrawal_count'] }}</div>
                    <div class="metric-change down">
                        <i class="fas fa-money-bill-wave"></i>
                        ₹{{ number_format($stats['pending_withdrawals'] / 1000, 0) }}K
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Overview -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="metric-card">
                    <div class="metric-icon info">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="metric-label">Total Referral Income</div>
                    <div class="metric-value">₹{{ number_format($stats['total_referral_income'] / 1000, 0) }}K</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="metric-card">
                    <div class="metric-icon success">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="metric-label">Lifetime Earnings</div>
                    <div class="metric-value">₹{{ number_format($stats['total_lifetime_earnings'] / 1000000, 2) }}M</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="metric-card">
                    <div class="metric-icon primary">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="metric-label">Total Packages</div>
                    <div class="metric-value">{{ number_format($stats['total_packages']) }}</div>
                    <div class="metric-change up">
                        Avg: ₹{{ number_format($stats['avg_package_value'], 0) }}
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="metric-card">
                    <div class="metric-icon warning">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="metric-label">Salary Eligible</div>
                    <div class="metric-value">{{ $stats['salary_eligible_users'] }}</div>
                    <div class="metric-change">
                        Level 4+ Users
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Business Growth Trend -->
            <div class="col-lg-8">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-chart-area"></i>
                        Business Growth Trend (Last 12 Months)
                    </div>
                    <canvas id="businessTrendChart" height="100"></canvas>
                </div>
            </div>

            <!-- Transaction Types -->
            <div class="col-lg-4">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-exchange-alt"></i>
                        Transaction Types
                    </div>
                    <canvas id="transactionTypesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Daily Activity -->
        <div class="row">
            <div class="col-lg-12">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-clock"></i>
                        Today's Hourly Activity
                    </div>
                    <canvas id="hourlyActivityChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- 30 Days Overview -->
        <div class="row">
            <div class="col-lg-12">
                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-calendar-alt"></i>
                        Last 30 Days Overview
                    </div>
                    <canvas id="thirtyDaysChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Level Distribution & Package Stats -->
        <div class="row">
            <!-- Level Distribution -->
            <div class="col-lg-6">
                <div class="glass-card">
                    <h5 class="mb-4"><i class="fas fa-layer-group"></i> Level Distribution</h5>
                    @for ($i = 1; $i <= 10; $i++)
                        @php
                            $count = $stats['level_distribution'][$i] ?? 0;
                            $percentage = $stats['total_users'] > 0 ? ($count / $stats['total_users']) * 100 : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span><strong>Level {{ $i }}</strong></span>
                                <span>{{ $count }} users ({{ number_format($percentage, 1) }}%)</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill" style="width: {{ $percentage }}%">
                                    {{ number_format($percentage, 1) }}%
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Package Performance -->
            <div class="col-lg-6">
                <div class="glass-card">
                    <h5 class="mb-4"><i class="fas fa-box-open"></i> Package Performance</h5>
                    <canvas id="packagePerformanceChart"></canvas>

                    @if ($stats['most_popular_package'])
                        <div class="mt-4 p-3" style="background: #f8f9fa; border-radius: 10px;">
                            <h6>🏆 Most Popular Package</h6>
                            <p class="mb-1"><strong>{{ $stats['most_popular_package']->package_name }}</strong></p>
                            <p class="mb-1">Price:
                                ₹{{ number_format($stats['most_popular_package']->package_amount, 0) }}</p>
                            <p class="mb-0">Sales: {{ $stats['most_popular_package']->purchase_count }} | Revenue:
                                ₹{{ number_format($stats['most_popular_package']->total_revenue, 0) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Performers & Activity -->
        <div class="row">
            <!-- Top by Business -->
            <div class="col-lg-4">
                <div class="glass-card">
                    <h5 class="mb-4"><i class="fas fa-crown"></i> Top by Business</h5>
                    @foreach ($stats['top_by_business'] as $index => $performer)
                        <div class="performer-card" style="display: flex; align-items: center; margin-bottom: 10px;">
                            <div class="performer-rank 
            {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : '')) }}"
                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
                   font-weight: bold; border-radius: 50%; color: #fff; margin-right: 10px;
                   background-color: {{ $index === 0 ? '#FFD700' : ($index === 1 ? '#C0C0C0' : ($index === 2 ? '#CD7F32' : '#718096')) }};">
                                {{ $index + 1 }}
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #2d3748;">
                                    <a href="{{ route('admin.mlm.userReport', ['userId' => $performer['id']]) }}"
                                        target="_blank" style="text-decoration: none; color: inherit;">
                                        {{ $performer['name'] }}
                                    </a>
                                </div>
                                <div style="font-size: 13px; color: #718096;">
                                    ₹{{ number_format($performer['business'], 0) }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <!-- Top by Income -->
            <div class="col-lg-4">
                <div class="glass-card">
                    <h5 class="mb-4"><i class="fas fa-money-bill-wave"></i> Top by Income</h5>
                    @foreach ($stats['top_by_income'] as $index => $performer)
                        <div class="performer-card">
                            <div
                                class="performer-rank {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : '')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #2d3748;">{{ $performer->app_u_name }}</div>
                                <div style="font-size: 13px; color: #718096;">
                                    ₹{{ number_format($performer->referral_income, 0) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top by Team Size -->
            <div class="col-lg-4">
                <div class="glass-card">
                    <h5 class="mb-4"><i class="fas fa-users-cog"></i> Top by Team Size</h5>
                    @foreach ($stats['top_by_team_size'] as $index => $performer)
                        <div class="performer-card">
                            <div
                                class="performer-rank {{ $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : '')) }}">
                                {{ $index + 1 }}
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #2d3748;">{{ $performer['name'] }}</div>
                                <div style="font-size: 13px; color: #718096;">{{ $performer['team_size'] }} members</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- System Alerts -->
        @if (count($stats['alerts']) > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="glass-card">
                        <h5 class="mb-4"><i class="fas fa-bell"></i> System Alerts & Warnings</h5>
                        @foreach ($stats['alerts'] as $alert)
                            <div class="alert-card {{ $alert['type'] }}">
                                <div class="alert-icon"
                                    style="background: {{ $alert['type'] === 'danger' ? '#ff6b6b' : ($alert['type'] === 'warning' ? '#ffd43b' : '#4facfe') }}; color: white;">
                                    <i class="fas fa-{{ $alert['icon'] }}"></i>
                                </div>
                                <div style="flex: 1;">
                                    <strong>{{ $alert['title'] }}</strong>
                                    <p class="mb-0" style="font-size: 14px; margin-top: 5px;">{{ $alert['message'] }}
                                    </p>
                                    @if (isset($alert['action']))
                                        <a href="{{ $alert['action'] }}" class="btn btn-sm btn-primary mt-2">Take
                                            Action</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Activity Feed -->
        <div class="row">
            <div class="col-lg-12">
                <div class="glass-card">
                    <h5 class="mb-4"><i class="fas fa-stream"></i> Live Activity Feed</h5>
                    <div class="activity-feed">
                        @foreach ($stats['recent_activities'] as $activity)
                            @php
                                $types = [
                                    1 => ['icon' => 'fa-plus-circle', 'color' => '#667eea', 'label' => 'Balance Added'],
                                    2 => [
                                        'icon' => 'fa-shopping-cart',
                                        'color' => '#51cf66',
                                        'label' => 'Package Purchase',
                                    ],
                                    3 => ['icon' => 'fa-coins', 'color' => '#ffd43b', 'label' => 'Maturity'],
                                    4 => [
                                        'icon' => 'fa-money-bill-wave',
                                        'color' => '#ff6b6b',
                                        'label' => 'Withdrawal',
                                    ],
                                    5 => ['icon' => 'fa-percentage', 'color' => '#4facfe', 'label' => 'Level Income'],
                                    6 => ['icon' => 'fa-hand-holding-usd', 'color' => '#fa5252', 'label' => 'Salary'],
                                ];
                                $type = $types[$activity->type_id] ?? [
                                    'icon' => 'fa-exchange-alt',
                                    'color' => '#868e96',
                                    'label' => 'Transaction',
                                ];
                            @endphp
                            <div class="activity-item">
                                <div class="activity-icon" style="background: {{ $type['color'] }}; color: white;">
                                    <i class="fas {{ $type['icon'] }}"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: #2d3748;">
                                        {{ $activity->app_u_name }} - {{ $type['label'] }}
                                    </div>
                                    <div style="font-size: 13px; color: #718096;">
                                        ₹{{ number_format($activity->amount, 0) }} |
                                        {{ Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $activity->status === 'Done' ? 'success' : 'warning' }}">
                                        {{ $activity->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <!-- Withdrawal Statistics -->
        <div class="row">
            <div class="col-lg-4">
                <div class="glass-card text-center">
                    <h5 class="mb-4"><i class="fas fa-check-circle"></i> Approved Withdrawals</h5>
                    <div
                        style="font-size: 48px; font-weight: 800; background: linear-gradient(135deg, #51cf66, #37b24d); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ $stats['approved_withdrawal_count'] }}
                    </div>
                    <div style="color: #718096; margin-top: 10px;">
                        ₹{{ number_format($stats['approved_withdrawals'] / 1000, 0) }}K Total
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card text-center">
                    <h5 class="mb-4"><i class="fas fa-clock"></i> Pending Withdrawals</h5>
                    <div
                        style="font-size: 48px; font-weight: 800; background: linear-gradient(135deg, #ffd43b, #fab005); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ $stats['pending_withdrawal_count'] }}
                    </div>
                    <div style="color: #718096; margin-top: 10px;">
                        ₹{{ number_format($stats['pending_withdrawals'] / 1000, 0) }}K Pending
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card text-center">
                    <h5 class="mb-4"><i class="fas fa-times-circle"></i> Rejected Withdrawals</h5>
                    <div
                        style="font-size: 48px; font-weight: 800; background: linear-gradient(135deg, #ff6b6b, #ee5a6f); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ $stats['rejected_withdrawal_count'] }}
                    </div>
                    <div style="color: #718096; margin-top: 10px;">
                        ₹{{ number_format($stats['rejected_withdrawals'] / 1000, 0) }}K Rejected
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="row">
            <div class="col-lg-12">
                <div class="glass-card">
                    <h5 class="mb-4"><i class="fas fa-chart-pie"></i> Financial Summary</h5>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3">
                                <div style="font-size: 14px; color: #718096; margin-bottom: 10px;">Total Inflow</div>
                                <div style="font-size: 32px; font-weight: 800; color: #51cf66;">
                                    ₹{{ number_format($stats['financial_summary']['total_inflow'] / 1000000, 2) }}M
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div style="font-size: 14px; color: #718096; margin-bottom: 10px;">Total Outflow</div>
                                <div style="font-size: 32px; font-weight: 800; color: #ff6b6b;">
                                    ₹{{ number_format($stats['financial_summary']['total_outflow'] / 1000000, 2) }}M
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div style="font-size: 14px; color: #718096; margin-bottom: 10px;">Net Profit</div>
                                <div style="font-size: 32px; font-weight: 800; color: #4facfe;">
                                    ₹{{ number_format($stats['financial_summary']['net_profit'] / 1000000, 2) }}M
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div style="font-size: 14px; color: #718096; margin-bottom: 10px;">Profit Margin</div>
                                <div style="font-size: 32px; font-weight: 800; color: #667eea;">
                                    {{ number_format($stats['financial_summary']['profit_margin'], 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="row">
            <div class="col-lg-3">
                <div class="metric-card text-center">
                    <div style="font-size: 14px; color: #718096; margin-bottom: 10px;">Withdrawal Processing</div>
                    <div style="font-size: 36px; font-weight: 800; color: #667eea;">
                        {{ $stats['system_health']['withdrawal_processing_time'] }}h
                    </div>
                    <div style="font-size: 12px; color: #a0aec0;">Average Time</div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="metric-card text-center">
                    <div style="font-size: 14px; color: #718096; margin-bottom: 10px;">User Satisfaction</div>
                    <div style="font-size: 36px; font-weight: 800; color: #51cf66;">
                        {{ $stats['system_health']['user_satisfaction_score'] }}%
                    </div>
                    <div style="font-size: 12px; color: #a0aec0;">Approval Rate</div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="metric-card text-center">
                    <div style="font-size: 14px; color: #718096; margin-bottom: 10px;">Network Growth</div>
                    <div style="font-size: 36px; font-weight: 800; color: #ffd43b;">
                        {{ $stats['system_health']['network_growth_rate'] }}%
                    </div>
                    <div style="font-size: 12px; color: #a0aec0;">Month over Month</div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="metric-card text-center">
                    <div style="font-size: 14px; color: #718096; margin-bottom: 10px;">Revenue per User</div>
                    <div style="font-size: 36px; font-weight: 800; color: #ff6b6b;">
                        ₹{{ number_format($stats['system_health']['revenue_per_user'], 0) }}
                    </div>
                    <div style="font-size: 12px; color: #a0aec0;">Average</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Business Trend Chart
        new Chart(document.getElementById('businessTrendChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($stats['monthly_comparison'], 'month')) !!},
                datasets: [{
                    label: 'Business Volume',
                    data: {!! json_encode(array_column($stats['monthly_comparison'], 'business')) !!},
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3
                }, {
                    label: 'Payouts',
                    data: {!! json_encode(array_column($stats['monthly_comparison'], 'payouts')) !!},
                    borderColor: '#ff6b6b',
                    backgroundColor: 'rgba(255, 107, 107, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + (value / 1000000).toFixed(1) + 'M';
                            }
                        }
                    }
                }
            }
        });

        // Transaction Types Chart
        new Chart(document.getElementById('transactionTypesChart'), {
            type: 'doughnut',
            data: {
                labels: ['Add Balance', 'Package Buy', 'Maturity', 'Withdrawal', 'Level Income', 'Salary'],
                datasets: [{
                    data: [
                        {{ $stats['add_balance_total'] }},
                        {{ $stats['package_buy_total'] }},
                        {{ $stats['maturity_total'] }},
                        {{ $stats['withdrawal_total'] }},
                        {{ $stats['level_income_total'] }},
                        {{ $stats['salary_total'] }}
                    ],
                    backgroundColor: ['#667eea', '#51cf66', '#ffd43b', '#ff6b6b', '#4facfe', '#fa5252']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Hourly Activity Chart
        new Chart(document.getElementById('hourlyActivityChart'), {
            type: 'bar',
            data: {
                labels: Array.from({
                    length: 24
                }, (_, i) => i + ':00'),
                datasets: [{
                    label: 'Transactions',
                    data: {!! json_encode(array_values($stats['hourly_activity'])) !!},
                    backgroundColor: 'rgba(102, 126, 234, 0.7)',
                    borderColor: '#667eea',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 30 Days Chart
        new Chart(document.getElementById('thirtyDaysChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($stats['daily_stats'], 'date')) !!},
                datasets: [{
                    label: 'New Users',
                    data: {!! json_encode(array_column($stats['daily_stats'], 'users')) !!},
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    yAxisID: 'y',
                }, {
                    label: 'Packages',
                    data: {!! json_encode(array_column($stats['daily_stats'], 'packages')) !!},
                    borderColor: '#51cf66',
                    backgroundColor: 'rgba(81, 207, 102, 0.1)',
                    yAxisID: 'y',
                }, {
                    label: 'Business (₹K)',
                    data: {!! json_encode(
                        array_map(function ($v) {
                            return $v / 1000;
                        }, array_column($stats['daily_stats'], 'business')),
                    ) !!},
                    borderColor: '#ff6b6b',
                    backgroundColor: 'rgba(255, 107, 107, 0.1)',
                    yAxisID: 'y1',
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });

        // Package Performance Chart
        new Chart(document.getElementById('packagePerformanceChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($stats['package_stats']->pluck('package_name')->toArray()) !!},
                datasets: [{
                    label: 'Sales Count',
                    data: {!! json_encode($stats['package_stats']->pluck('purchase_count')->toArray()) !!},
                    backgroundColor: 'rgba(102, 126, 234, 0.7)',
                    borderColor: '#667eea',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

@endsection
