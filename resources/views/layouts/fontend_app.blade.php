@include('layouts.partials.head')




{{-- Main Content --}}
<style>
    .fontend-h1 {
        text-align: center;
        background: tomato;
        margin: 0 35% !important;
        padding: 3px 0;
        border-radius: 20px;
        color: #fff;
        font-weight: 700;
    }
</style>
<div class="content-wrapper" style="display: unset;">

    {{-- <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="fontend-h1">
            
            @yield('title', 'MLM')
     
        </h1>

    </section> --}}

    @yield('content')

</div>



{{-- Scripts --}}
@include('layouts.partials.scripts')
</body>

</html>
