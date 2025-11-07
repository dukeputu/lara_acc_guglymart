  @php
    $userId = session('app_user_id');
    // $userName = session('app_user_name');
    // $userPhone = session('app_user_phone');
    // $userPic = session('app_user_photo');
    // $userWallet = session('app_user_wallet');
    $userName = DB::table('app_users')->where('id', $userId)->value('app_u_name');
    // $currentuser_wallet = DB::table('app_users')->where('id', $userId)->value('user_wallet');
    // $totalWithdrawalReq = DB::table('app_users')->where('id', $userId)->value('total_withdrawal_req');
    // $life_time_eran = DB::table('app_users')->where('id', $userId)->value('life_time_eran');


    //   $renewCount = \DB::table('user_balance_request')->where('status', 2)->count();
    //   $withdrawalCount = \DB::table('user_withdraw_request')->where('status', 2)->count();
  @endphp
  <style>
      .submenu-link i {
          font-size: unset;
      }
  </style>
  <!-- Modern Header -->
  <header class="modern-header">
      <div class="header-left">
          <button class="menu-toggle" onclick="toggleSidebar()">
              <i class="fas fa-bars"></i>
          </button>
          <!-- Logo -->
          <div class="">
              <img style="border-radius: 10px;margin: 4px 0 0 15px;" width="120"
                  src="{{ url('userApp/assets/goldoffLogo.webp') }}" alt="">
          </div>
          <div class="header-search">
          </div> <!-- search form -->
      </div>
      <div class="header-right">
      </div>
  </header>


  <!-- Modern Sidebar -->
  <aside class="modern-sidebar" id="modernSidebar">

      <!-- Action Buttons -->
      <div class="sidebar-actions">
         
          <a href="{{ route('user.Logout') }}" class="sidebar-btn logout">
              <i class="fas fa-sign-out-alt"></i>
              Logout
          </a>
      </div>
      <br>

      <!-- search form -->
      {{-- <form action="{{ route('appUsers.listAdminPanel') }}" method="get" class="sidebar-form"
          onsubmit="storePhoneNumber()">
          <div class="input-group">
              <input type="text" id="phoneInput" name="phone" class="form-control"
                  placeholder="App Users Phone No..." />
              <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                      <i class="fa fa-search"></i>
                  </button>
              </span>
          </div>
      </form> --}}

      <script>
          // Load value from localStorage when page loads
          document.addEventListener('DOMContentLoaded', function() {
              const savedPhone = localStorage.getItem('searchedPhone');
              if (savedPhone) {
                  document.getElementById('phoneInput').value = savedPhone;
              }
          });

          // Store value in localStorage on form submit
          function storePhoneNumber() {
              const phone = document.getElementById('phoneInput').value;
              localStorage.setItem('searchedPhone', phone);
          }
      </script>
      <br>

      <!-- Menu -->
      <ul class="sidebar-menu" id="sidebarMenu">
          <li class="menu-header">MAIN NAVIGATION USERS {{$userName}}</li>

          <!-- Search Box -->
          <li class="menu-search p-2">
              <input type="text" id="menuSearch" class="form-control form-control-sm" placeholder="Search menu...">
          </li>

          <!-- Dashboard -->
          <li class="menu-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
              <a href="{{ route('user.dashboard') }}" class="menu-link">
                  <i class="fas fa-home"></i>
                  <span>Dashboard</span>
              </a>
          </li>

          <!-- Business Plan -->
          <li class="menu-item {{ request()->routeIs('business.plan.*') ? 'active open' : '' }}">
              <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                  <i class="fas fa-briefcase"></i>
                  <span>Business Plan</span>
                  <i class="fas fa-chevron-right menu-arrow"></i>
              </a>
              <ul class="submenu" style="{{ request()->routeIs('business.plan.*') ? 'display:block;' : '' }}">
                  <li>
                      <a href="{{ route('business.plan.add') }}"
                          class="submenu-link {{ request()->routeIs('business.plan.add') ? 'active' : '' }}">
                          <i class="fas fa-circle"></i> Add
                      </a>
                  </li>

                
                  <li>
                      <a href="{{ route('business.plan.view') }}"
                          class="submenu-link {{ request()->routeIs('business.plan.view') ? 'active' : '' }}">
                          <i class="fas fa-circle"></i> View
                      </a>
                  </li>

                    <li>
                      <a href="{{ route('business.plan.addRd') }}"
                          class="submenu-link {{ request()->routeIs('business.plan.addRd') ? 'active' : '' }}">
                          <i class="fas fa-circle"></i> RD
                      </a>
                  </li>
              </ul>
          </li>

          <!-- Daily Update -->
          <li class="menu-item {{ request()->routeIs('daily.update.*') ? 'active open' : '' }}">
              <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                  <i class="fas fa-calendar-day"></i>
                  <span>Daily Update</span>
                  <i class="fas fa-chevron-right menu-arrow"></i>
              </a>
              <ul class="submenu" style="{{ request()->routeIs('daily.update.*') ? 'display:block;' : '' }}">
                  <li>
                      <a href="{{ route('daily.update.add') }}"
                          class="submenu-link {{ request()->routeIs('daily.update.add') ? 'active' : '' }}">
                          <i class="fas fa-circle"></i> Add
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('daily.update.view') }}"
                          class="submenu-link {{ request()->routeIs('daily.update.view') ? 'active' : '' }}">
                          <i class="fas fa-circle"></i> View
                      </a>
                  </li>
              </ul>
          </li>


          <!-- Monthly Update -->
          <li class="menu-item {{ request()->routeIs('monthly.update.*') ? 'active open' : '' }}">
              <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                  <i class="fas fa-calendar-day"></i>
                  <span>Monthly Update</span>
                  <i class="fas fa-chevron-right menu-arrow"></i>
              </a>
              <ul class="submenu" style="{{ request()->routeIs('monthly.update.*') ? 'display:block;' : '' }}">
                  <li>
                      <a href="{{ route('monthly.update.add') }}"
                          class="submenu-link {{ request()->routeIs('monthly.update.add') ? 'active' : '' }}">
                          <i class="fas fa-circle"></i> Add
                      </a>
                  </li>
                  {{-- <li>
                      <a href="{{ route('daily.update.view') }}"
                          class="submenu-link {{ request()->routeIs('daily.update.view') ? 'active' : '' }}">
                          <i class="fas fa-circle"></i> View
                      </a>
                  </li> --}}
              </ul>
          </li>

             <li class="menu-item">
              <a target="_blank" href="{{ route('monthly.update.report') }}" class="menu-link">
                  <i class="fas fa-home"></i>
                  <span>Monthly Report</span>
              </a>
          </li>



      </ul>





  </aside>
