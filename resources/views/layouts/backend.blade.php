<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title> @yield('title') | ACAPELA SI CPl</title>

    <meta name="description" content="Sistem Informasi CPl">
    <meta name="author" content="SI CPl">
    <meta name="robots" content="noindex, nofollow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">

    <!-- Fonts and Styles -->
    @yield('css_before')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMoeBzRaENm7P35C1pF7j4Y1BLTXtHXWahCQw4B" crossorigin="anonymous">
    <link rel="stylesheet" id="css-main" href="{{ mix('css/dashmix.css') }}">

    <!-- You can include a specific file from public/css/themes/ folder to alter the default color theme of the template. eg: -->
<!--link rel="stylesheet" id="css-theme" href="{{ mix('css/themes/xmodern.css') }}"-->
@yield('css_after')

<!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
    </script>
</head>

<body>
<!-- Page Container -->
<!--
  Available classes for #page-container:

  GENERIC

    'remember-theme'                            Remembers active color theme and dark mode between pages using localStorage when set through
                                                - Theme helper buttons [data-toggle="theme"],
                                                - Layout helper buttons [data-toggle="layout" data-action="dark_mode_[on/off/toggle]"]
                                                - ..and/or Dashmix.layout('dark_mode_[on/off/toggle]')

  SIDEBAR & SIDE OVERLAY

    'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
    'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
    'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
    'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
    'sidebar-dark'                              Dark themed sidebar

    'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
    'side-overlay-o'                            Visible Side Overlay by default

    'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

    'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

  HEADER

    ''                                          Static Header if no class is added
    'page-header-fixed'                         Fixed Header


  FOOTER

    ''                                          Static Footer if no class is added
    'page-footer-fixed'                         Fixed Footer (please have in mind that the footer has a specific height when is fixed)

  HEADER STYLE

    ''                                          Classic Header style if no class is added
    'page-header-dark'                          Dark themed Header
    'page-header-glass'                         Light themed Header with transparency by default
                                                (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
    'page-header-glass page-header-dark'         Dark themed Header with transparency by default
                                                (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

  MAIN CONTENT LAYOUT

    ''                                          Full width Main Content if no class is added
    'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
    'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)

  DARK MODE

    'sidebar-dark page-header-dark dark-mode'   Enable dark mode (light sidebar/header is not supported with dark mode)
-->
<div id="page-container"
     class="sidebar-mini sidebar-o enable-page-overlay sidebar-dark side-scroll page-header-fixed main-content-narrow remember-theme">
    <div id="page-loader" class="show"></div>

    <!-- Sidebar -->
    <!--
                Sidebar Mini Mode - Display Helper classes

                Adding 'smini-hide' class to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
                Adding 'smini-show' class to an element will make it visible (opacity: 1) when the sidebar is in mini mode
                    If you would like to disable the transition animation, make sure to also add the 'no-transition' class to your element

                Adding 'smini-hidden' to an element will hide it when the sidebar is in mini mode
                Adding 'smini-visible' to an element will show it (display: inline-block) only when the sidebar is in mini mode
                Adding 'smini-visible-block' to an element will show it (display: block) only when the sidebar is in mini mode
            -->
    <nav id="sidebar" aria-label="Main Navigation">
        <!-- Side Header -->
        <div class="bg-header-dark">
            <div class="content-header bg-white-5">
                <!-- Logo -->
                <a class="fw-semibold text-white tracking-wide" href="/">
            <span class="smini-visible">
              <span class="opacity-75">CPL</span>
            </span>
                    <span class="smini-hidden">
              A<span class="opacity-75">CAPELA</span>
            </span>
                </a>
                <!-- END Logo -->

                <!-- Options -->
                <div>
                    <!-- Toggle Sidebar Style -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <!-- Class Toggle, functionality initialized in Helpers.dmToggleClass() -->
                    <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle"
                            data-target="#sidebar-style-toggler" data-class="fa-toggle-off fa-toggle-on"
                            onclick="Dashmix.layout('sidebar_style_toggle');Dashmix.layout('header_style_toggle');">
                        <i class="fa fa-toggle-off" id="sidebar-style-toggler"></i>
                    </button>
                    <!-- END Toggle Sidebar Style -->

                    <!-- Dark Mode -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle"
                            data-target="#dark-mode-toggler" data-class="far fa"
                            onclick="Dashmix.layout('dark_mode_toggle');">
                        <i class="far fa-moon" id="dark-mode-toggler"></i>
                    </button>
                    <!-- END Dark Mode -->

                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout"
                            data-action="sidebar_close">
                        <i class="fa fa-times-circle"></i>
                    </button>
                    <!-- END Close Sidebar -->
                </div>
                <!-- END Options -->
            </div>
        </div>
        <!-- END Side Header -->

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->is('home','home/*') ? ' active' : '' }}" href="/home">
                            <i class="nav-main-link-icon fa fa-location-arrow"></i>
                            <span class="nav-main-link-name">Dashboard</span>
                        </a>
                    </li>
                    @role('admin')
                    <li class="nav-main-heading">Pengguna</li>
                    <li class="nav-main-item{{ request()->is('admin','dosen','admin/*','dosen/*') ? ' open' : '' }}">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                           aria-expanded="true" href="#">
                            <i class="nav-main-link-icon fa fa-user-friends"></i>
                            <span class="nav-main-link-name">Admin & Dosen</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link{{ request()->is('admin','admin/*') ? ' active' : '' }}"
                                   href="{{URL::to('admin')}}">
                                    <span class="nav-main-link-name">Admin</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link{{ request()->is('dosen','dosen/*') ? ' active' : '' }}"
                                   href="{{URL::to('dosen')}}">
                                    <span class="nav-main-link-name">Dosen</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-main-heading">Kelola</li>
                    <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->is('mhs','mhs/*') ? ' active' : '' }}"
                           href="{{URL::to('mhs')}}">
                            <i class="nav-main-link-icon fa fa-user-graduate"></i>
                            <span class="nav-main-link-name">Mahasiswa</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->is('ta','ta/*') ? ' active' : '' }}"
                           href="{{URL::to('ta')}}">
                            <i class="nav-main-link-icon fa fa-calendar"></i>
                            <span class="nav-main-link-name">Tahun Ajaran</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->is('mk','mk/*') ? ' active' : '' }}"
                           href="{{URL::to('mk')}}">
                            <i class="nav-main-link-icon fa fa-book-open"></i>
                            <span class="nav-main-link-name">Mata Kuliah</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->is('pl','pl/*') ? ' active' : '' }}"
                           href="{{URL::to('pl')}}">
                            <i class="nav-main-link-icon fa fa-sticky-note"></i>
                            <span class="nav-main-link-name">PL</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->is('cpl','cpl/*') ? ' active' : '' }}"
                           href="{{URL::to('cpl')}}">
                            <i class="nav-main-link-icon fa fa-book"></i>
                            <span class="nav-main-link-name">CPL</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->is('cpmk','cpmk/*') ? ' active' : '' }}"
                           href="{{URL::to('cpmk')}}">
                            <i class="nav-main-link-icon si si-notebook"></i>
                            <span class="nav-main-link-name">CPMK</span>
                        </a>
                    </li>
                    <li class="nav-main-item{{ request()->is('rumusanAkhirMk','rumusanAkhirCpl','rumusanAkhirMk/*','rumusanAkhirCpl/*') ? ' open' : '' }}">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                           aria-expanded="true" href="#">
                            <i class="nav-main-link-icon fa fa-pencil-ruler"></i>
                            <span class="nav-main-link-name">Rumusan Akhir</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link{{ request()->is('rumusanAkhirMk*') ? ' active' : '' }}"
                                   href="{{ route('rumusanAkhirMk.index') }}">
                                    <span class="nav-main-link-name">MK</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link{{ request()->is('rumusanAkhirCpl','rumusanAkhirCpl/*') ? ' active' : '' }}"
                                   href="{{URL::to('rumusanAkhirCpl')}}">
                                    <span class="nav-main-link-name">CPL</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->is('nilai','nilai/*') ? ' active' : '' }}"
                           href="{{URL::to('nilai')}}">
                            <i class="nav-main-link-icon si si-pencil"></i>
                            <span class="nav-main-link-name">Nilai</span>
                        </a>
                    </li> --}}
                    <li class="nav-main-item">
                        <a class="nav-main-link{{ request()->is('ketercapaian','ketercapaian/*') ? ' active' : '' }}"
                           href="{{URL::to('ketercapaian')}}">
                           <i class="nav-main-link-icon far fa-chart-bar"></i>
                            <span class="nav-main-link-name">ketercapaian</span>
                        </a>
                    </li>
                    {{-- <li class="nav-main-item{{ request()->is('kcpmk','kcpl','kcpmk/*','kcpl/*') ? ' open' : '' }}">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                           aria-expanded="true" href="#">
                            <i class="nav-main-link-icon far fa-chart-bar"></i>
                            <span class="nav-main-link-name">Ketercapaian</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link{{ request()->is('kcpmk','kcpmk/*') ? ' active' : '' }}"
                                   href="{{URL::to('kcpmk')}}">
                                    <span class="nav-main-link-name">CPMK</span>
                                </a>
                            </li> --}}
                            {{-- <li class="nav-main-item">
                                <a class="nav-main-link{{ request()->is('kcpl','kcpl/*') ? ' active' : '' }}"
                                   href="{{URL::to('kcpl')}}">
                                    <span class="nav-main-link-name">CPL</span>
                                </a>
                            </li>
                        </ul>
                    </li> --}}
                    @else
                        <li class="nav-main-heading">Kelola</li>
                        @endrole
                        @hasanyrole('dosen|admin')
                        <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('nilai','nilai/*') ? ' active' : '' }}"
                               href="{{URL::to('nilai')}}">
                                <i class="nav-main-link-icon si si-pencil"></i>
                                <span class="nav-main-link-name">Nilai</span>
                            </a>
                        </li>
                        {{-- <li class="nav-main-item">
                            <a class="nav-main-link{{ request()->is('dpna','dpna/*') ? ' active' : '' }}"
                               href="{{URL::to('dpna')}}">
                                <i class="nav-main-link-icon far fa-list-alt"></i>
                                <span class="nav-main-link-name">DPNA</span>
                            </a>
                        </li>
                         --}}
                        @endhasanyrole
                </ul>
            </div>
            <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
    </nav>
    <!-- END Sidebar -->

    <!-- Header -->
    <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div class="space-x-1">
                <!-- Toggle Sidebar -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="sidebar_toggle">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
                <!-- END Toggle Sidebar -->
            </div>
            <!-- END Left Section -->

            <!-- Right Section -->
            <div class="space-x-1">
                <!-- User Dropdown -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-alt-secondary" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-fw fa-user d-sm-none"></i>
                        <span
                            class="d-none d-sm-inline-block">{{ \App\Http\Controllers\BackendController::getNama() }}</span>
                        <i class="fa fa-fw fa-angle-down opacity-50 ms-1 d-none d-sm-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="page-header-user-dropdown">
                        
                        <div class="p-1">
                           
                            <div role="separator" class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}">
                                <i class="far fa-fw fa-arrow-alt-circle-left me-1"></i> Keluar
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END User Dropdown -->

                <!-- Header Loader -->
                <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
                <div id="page-header-loader" class="overlay-header bg-header-dark">
                    <div class="bg-white-10">
                        <div class="content-header">
                            <div class="w-100 text-center">
                                <i class="fa fa-fw fa-sun fa-spin text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Header Loader -->
            </div>
        </div>
    </header>
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container">
        @yield('content')
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <footer id="page-footer" class="bg-body-light">
        <div class="content py-0">
            <div class="row fs-sm">
                
            </div>
        </div>
    </footer>
    <!-- END Footer -->
</div>
<!-- END Page Container -->

<!-- Dashmix Core JS -->
<script src="{{ mix('js/dashmix.app.js') }}"></script>

<!-- Laravel Original JS -->
<!-- <script src="{{ mix('/js/laravel.app.js') }}"></script> -->

@yield('js_after')
</body>

</html>
