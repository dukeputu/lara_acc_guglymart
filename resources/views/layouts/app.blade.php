
    @include('layouts.partials.head')



    {{-- Top Navbar --}}
    @include('layouts.partials.header')

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Main Content --}}
    <div class="content-wrapper main-content"> 

         <!-- Content Header (Page header) -->
        <section class="page-header">
          <h1>
            
            @yield('title', 'App')
            <small>Preview</small>
          </h1>
         
        </section>

                @yield('content')
           
    </div>

    {{-- Footer --}}
@include('layouts.partials.footer')



{{-- Scripts --}}
@include('layouts.partials.scripts')
</body>
</html>
