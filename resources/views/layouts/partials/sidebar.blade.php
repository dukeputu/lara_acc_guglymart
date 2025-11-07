
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
          {{-- <a href="{{ route('userLogin.app') }}" class="sidebar-btn user" target="_blank">
              <i class="fas fa-user"></i>
              User
          </a> --}}
          <a href="{{ route('member.logout') }}" class="sidebar-btn logout">
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


      <ul class="sidebar-menu" >
    <li class="menu-header">MAIN NAVIGATION</li>

    <!-- 🔍 Menu Search -->
    <li class="menu-search p-2">
        <input type="text" id="menuSearch" class="form-control form-control-sm" placeholder="Search menu...">
    </li>

    <!-- Dashboard -->
    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard') }}" class="menu-link">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Admin List -->
    <li class="menu-item {{ request()->routeIs('addAdmin.*') || request()->routeIs('viewAdmins.*') ? 'active open' : '' }}">
        <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
            <i class="fas fa-users-cog"></i>
            <span>Admin List</span>
            <i class="fas fa-chevron-right menu-arrow"></i>
        </a>
        <ul class="submenu" style="{{ request()->routeIs('addAdmin.*') || request()->routeIs('viewAdmins.*') ? 'display:block;' : '' }}">
            <li>
                <a href="{{ route('addAdmin.adminCreate') }}" class="submenu-link {{ request()->routeIs('addAdmin.adminCreate') ? 'active' : '' }}">
                    <i class="fas fa-circle"></i> Add Admin
                </a>
            </li>
            <li>
                <a href="{{ route('viewAdmins.list') }}" class="submenu-link {{ request()->routeIs('viewAdmins.list') ? 'active' : '' }}">
                    <i class="fas fa-circle"></i> View Admins List
                </a>
            </li>
        </ul>
    </li>

    <!-- Add Company -->
    <li class="menu-item {{ request()->routeIs('addCompany.*') || request()->routeIs('appUsers.listAdminPanel') ? 'active open' : '' }}">
        <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
            <i class="fas fa-users"></i>
            <span>Add Company</span>
            <i class="fas fa-chevron-right menu-arrow"></i>
        </a>
        <ul class="submenu" style="{{ request()->routeIs('addCompany.*') || request()->routeIs('appUsers.listAdminPanel') ? 'display:block;' : '' }}">
            <li>
                <a href="{{ route('addCompany.User') }}" class="submenu-link {{ request()->routeIs('addCompany.User') ? 'active' : '' }}">
                    <i class="fas fa-circle"></i> Add Company
                </a>
            </li>
            <li>
                <a href="{{ route('appUsers.listAdminPanel') }}" class="submenu-link {{ request()->routeIs('appUsers.listAdminPanel') ? 'active' : '' }}">
                    <i class="fas fa-circle"></i> View Company
                </a>
            </li>
        </ul>
    </li>


      


</ul>






{{-- 
      <!-- Menu -->
      <ul class="sidebar-menu">
          <li class="menu-header">MAIN NAVIGATION</li>

          <li class="menu-item">
              <a href="{{ route('admin.dashboard') }}" class="menu-link">
                  <i class="fas fa-home"></i>
                  <span>Dashboard</span>
              </a>
          </li>

          <li class="menu-item">
              <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                  <i class="fas fa-users-cog"></i>
                  <span>Admin List</span>
                  <i class="fas fa-chevron-right menu-arrow"></i>
              </a>
              <ul class="submenu">
                  <li>
                      <a href="{{ route('addAdmin.adminCreate') }}" class="submenu-link">
                          <i class="fas fa-circle"></i>
                          Add Admin
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('viewAdmins.list') }}" class="submenu-link">
                          <i class="fas fa-circle"></i>
                          View Admins List
                      </a>
                  </li>
              </ul>
          </li>


          <li class="menu-item">
              <a href="javascript:void(0)" class="menu-link" onclick="toggleSubmenu(this)">
                  <i class="fas fa-users"></i>
                  <span>Add Company</span>
                  <i class="fas fa-chevron-right menu-arrow"></i>
              </a>
              <ul class="submenu">
                  <li>
                      <a href="{{ route('addCompany.User') }}" class="submenu-link">
                          <i class="fas fa-circle"></i>
                          Add Company
                      </a>
                  </li>

                  <li class="menu-item">
                      <a href="{{ route('appUsers.listAdminPanel') }}" class="menu-link">
                          <i class="fas fa-circle"></i>
                          <span>View Company</span>
                      </a>
                  </li>
              </ul>
          </li>




          
          <li class="menu-item">
              <a href="{{ route('packageMaster.list') }}" class="menu-link">
                  <i class="fas fa-box"></i>
                  <span>Package Master</span>
              </a>
          </li>

         
          <li class="menu-item">
              <a href="{{ route('addBalanceRequest.list') }}" class="menu-link">
                  <i class="fas fa-wallet"></i>
                  <span>Balance Request</span>
                  @if ($renewCount > 0)
                      <span class="menu-badge">{{ $renewCount }}</span>
                  @endif
              </a>
          </li>

          <li class="menu-item">
              <a href="{{ route('packageBuyingRequest.list') }}" class="menu-link">
                  <i class="fas fa-shopping-cart"></i>
                  <span>Package Buying</span>
              </a>
          </li>

          <li class="menu-item">
              <a href="{{ route('withdrawalRequest.list') }}" class="menu-link">
                  <i class="fas fa-money-bill-wave"></i>
                  <span>Withdrawal Request</span>
                  @if ($withdrawalCount > 0)
                      <span class="menu-badge">{{ $withdrawalCount }}</span>
                  @endif
              </a>
          </li>

          <li class="menu-item">
              <a href="{{ route('appBannerView.list') }}" class="menu-link">
                  <i class="fas fa-images"></i>
                  <span>App Banners</span>
              </a>
          </li>

          <li class="menu-header">MLM SYSTEM</li>

          <li class="menu-item">
              <a href="{{ route('admin.mlm.tree') }}" class="menu-link">
                  <i class="fas fa-sitemap"></i>
                  <span>Member Tree</span>
              </a>
          </li>

          <li class="menu-item">
              <a href="{{ route('admin.mlm.search') }}" class="menu-link">
                  <i class="fas fa-search"></i>
                  <span>Search Users</span>
              </a>
          </li>

          <li class="menu-item">
              <a href="{{ route('admin.mlm.levelStats') }}" class="menu-link">
                  <i class="fas fa-chart-bar"></i>
                  <span>Level Statistics</span>
              </a>
          </li> --}}




      {{-- </ul> --}}



  </aside>
