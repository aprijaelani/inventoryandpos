<!DOCTYPE html>
<html lang="en">
<head>        
    <!-- META SECTION -->
    <title>Indo Optik</title>            
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="{{asset('logo.png')}}" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->        
    <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('css/theme-default.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('sweetalert2/dist/sweetalert2.min.css')}}">
    <!-- EOF CSS INCLUDE -->                                    
    <script type="text/javascript" src="{{ asset('js/jquerysatu.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('sweetalert2/dist/sweetalert2.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/jquery/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins.js') }}"></script>        
    <script type="text/javascript" src="{{ asset('js/actions.js') }}"></script>      
    <script type="text/javascript" src="{{ asset('js/jquery.maskMoney.js') }}"></script>
    <!-- <script type="text/javascript" src="{{ asset('js/jquery.number.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>

</head>
<body>
    <!-- START PAGE CONTAINER -->
    <div class="page-container">

        @include('layouts.navigation')            
        <!-- PAGE CONTENT -->
        <div class="page-content">

            <!-- START X-NAVIGATION VERTICAL -->
            <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
                <!-- SEARCH -->
                {{-- <li class="xn-search">
                    <form method="post" action="{{ url('price_list/search') }}">
                        {{ csrf_field() }}
                      <input class="form-control" type="text" name="search" placeholder="Cari Barang..."/> 
                    </form>
                </li>   --}} 
                <!-- END SEARCH -->  
                <!-- SIGN OUT -->
                <li class="pull-right">
                    <a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span>Keluar</a>                        
                </li> 
                <!-- END SIGN OUT -->
            </ul>
            <!-- END X-NAVIGATION VERTICAL -->                     

            <!-- START BREADCRUMB -->
            <!-- END BREADCRUMB -->                       

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                @include('sweet::alert')
                @yield('content')
            </div>
            <!-- END PAGE CONTENT WRAPPER -->                                
        </div>            
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->

    <!-- MESSAGE BOX-->
    <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
        <div class="mb-container">
            <div class="mb-middle">
                <div class="mb-title"><span class="fa fa-sign-out"></span> Anda Ingin Keluar dari Website ?</div>
                <div class="mb-content">
                    <p>Apakah anda ingin keluar dari website?</p>                    
                    <p>Tekan Tidak Jika Anda ingin tetap bekerja. Tekan Iya Jika Anda ingin menggunakan User Lainnya</p>
                </div>
                <div class="mb-footer">
                    <div class="pull-right">
                        <a href="{{ route('logout') }}" 
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-success btn-lg">Iya</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        <button class="btn btn-default btn-lg mb-control-close">Tidak</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MESSAGE BOX-->
    <!-- START PRELOADS -->
    <audio id="audio-alert" src="{{ asset('audio/alert.mp3') }}" preload="auto"></audio>
    <audio id="audio-fail" src="{{ asset('audio/fail.mp3') }}" preload="auto"></audio>
    <!-- END PRELOADS -->                  
    <!-- START SCRIPTS -->
    <!-- START THIS PAGE PLUGINS-->  
    <script>
        $('#flash-overlay-modal').modal();
    </script>      
    <script type='text/javascript' src="{{ asset('js/plugins/icheck/icheck.min.js') }}"></script>        
    <script type="text/javascript" src="{{ asset('js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/scrolltotop/scrolltopcontrol.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/plugins/morris/raphael-min.js') }}"></script>
    <!-- <script type="text/javascript" src="{{ asset('js/plugins/morris/morris.min.js') }}"></script>        -->
    <script type="text/javascript" src="{{ asset('js/plugins/rickshaw/d3.v3.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/rickshaw/rickshaw.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>                
    <script type='text/javascript' src="{{ asset('js/plugins/bootstrap/bootstrap-datepicker.js') }}""></script>                
    <script type="text/javascript" src="{{ asset('js/plugins/owl/owl.carousel.min.js') }}"></script>                 

    <script type="text/javascript" src="{{ asset ('js/plugins/bootstrap/bootstrap-timepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('js/plugins/bootstrap/bootstrap-colorpicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('js/plugins/bootstrap/bootstrap-file-input.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('js/plugins/bootstrap/bootstrap-select.js') }}"></script>
    <script type="text/javascript" src="{{ asset ('js/plugins/tagsinput/jquery.tagsinput.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/plugins/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- END THIS PAGE PLUGINS-->        
    <script type="text/javascript" src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <!-- START TEMPLATE -->
    <!-- <script type="text/javascript" src="{{ asset('js/settings.js') }}"></script> -->
    @yield('javascript')  
</body>
</html>             
