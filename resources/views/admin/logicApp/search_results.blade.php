@extends('layouts.app')
@section('title', 'Search Results')

@section('content')
<style>
    .search-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
    }

    .search-form {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .user-result-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .user-result-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .result-stat {
        display: inline-block;
        margin-right: 20px;
        padding: 5px 10px;
        background: #f8f9fa;
        border-radius: 5px;
        font-size: 14px;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="search-header">
        <h1>🔍 Search Users</h1>
        <p class="mb-0">Find users by name, phone, level, or business volume</p>
    </div>

    <!-- Search Form -->
    <div class="search-form">
        <form action="{{ route('admin.mlm.search') }}" method="GET">
            <div class="row">
                <div class="col-md-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Search by name" value="{{ request('name') }}">
                </div>
                <div class="col-md-3">
                    <label>Phone Number</label>
                    <input type="text" name="phone" class="form-control" placeholder="Search by phone" value="{{ request('phone') }}">
                </div>
                <div class="col-md-2">
                    <label>Level</label>
                    <select name="level" class="form-control">
                        <option value="">All Levels</option>
                        @for($i=0; $i<=10; $i++)
                            <option value="{{ $i }}" {{ request('level') == $i ? 'selected' : '' }}>
                                Level {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Min Business</label>
                    <input type="number" name="min_business" class="form-control" placeholder="₹ Min" value="{{ request('min_business') }}">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <a href="{{ route('admin.mlm.search') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                    <a href="{{ route('admin.mlm.tree') }}" class="btn btn-info">
                        <i class="fas fa-sitemap"></i> Back to Tree
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Search Results -->
    <div class="mb-3">
        <h5>
            Found {{ $users->total() }} user(s)
            @if(request()->anyFilled(['name', 'phone', 'level', 'min_business']))
                matching your criteria
            @endif
        </h5>
    </div>

    @if($users->count() > 0)
        <div class="results-container">
            @foreach($usersWithData as $userData)
                @if($userData)
                <div class="user-result-card">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h5 class="mb-1">{{ $userData['name'] }}</h5>
                            <p class="text-muted mb-0">
                                📞 {{ $userData['phone'] }}<br>
                                🆔 ID: #{{ $userData['user_id'] }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <span class="result-stat">
                                <strong>💰 Total Business:</strong> ₹{{ number_format($userData['total_business'], 0) }}
                            </span>
                            <span class="result-stat">
                                <strong>🏆 Level:</strong> 
                                <span class="badge bg-primary">{{ $userData['qualified_level'] }}</span>
                            </span>
                            <span class="result-stat">
                                <strong>📊 Top Leg:</strong> {{ $userData['top_leg_percentage'] }}%
                            </span>
                            @if($userData['qualified_level'] >= 4)
                            <span class="result-stat">
                                <strong>🔐 40:60:</strong> 
                                <span class="badge {{ $userData['is_4060_compliant'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $userData['ratio_status'] }}
                                </span>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="{{ route('admin.mlm.userReport', $userData['user_id']) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <small class="text-muted">
                                💼 Self Business: ₹{{ number_format($userData['self_business'], 0) }} | 
                                💵 Monthly Salary: ₹{{ number_format($userData['salary'], 0) }} | 
                                📈 Next Level Need: ₹{{ number_format($userData['business_needed'], 0) }}
                            </small>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No users found matching your search criteria.
        </div>
    @endif
</div>

@endsection