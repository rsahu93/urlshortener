<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - AdminLTE URL Shortener</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style (AdminLTE 3.2) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        .short-code-badge {
            font-family: monospace;
            font-size: 1.05rem;
            letter-spacing: 1px;
        }
        .table-middle td, .table-middle th {
            vertical-align: middle !important;
        }
    </style>
</head>

@hasSection('full_page')
<body class="hold-transition login-page bg-light">
    @yield('content')

    <!-- REQUIRED SCRIPTS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
@else
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-home me-1"></i> Dashboard</a>
      </li>
      @auth
      @if(auth()->user()->role->belongsToCompany())
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('short-urls.index') }}" class="nav-link"><i class="fas fa-link me-1"></i> Short URLs</a>
      </li>
      @endif
      @endauth
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      @auth
      <li class="nav-item d-flex align-items-center mr-3">
        <span class="badge badge-info p-2 mr-2">
            <i class="fas fa-user-shield mr-1"></i> {{ auth()->user()->role->value }}
        </span>
        @if (auth()->user()->company)
            <span class="badge badge-secondary p-2">
                <i class="fas fa-building mr-1"></i> {{ auth()->user()->company->name }}
            </span>
        @else
            <span class="badge badge-dark p-2">
                <i class="fas fa-crown mr-1"></i> System
            </span>
        @endif
      </li>
      <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-sign-out-alt"></i> Log out
            </button>
        </form>
      </li>
      @else
      <li class="nav-item">
        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-sign-in-alt"></i> Log in
        </a>
      </li>
      @endauth
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
      <i class="fas fa-scissors brand-image elevation-3 text-warning ml-3 mt-1"></i>
      <span class="brand-text font-weight-bold ml-2">URL Shortener</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      @auth
      <!-- Sidebar user panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
        <div class="image">
          <div class="img-circle bg-primary d-flex align-items-center justify-content-center text-white font-weight-bold" style="width: 38px; height: 38px; font-size: 1.1rem;">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          </div>
        </div>
        <div class="info">
          <a href="{{ route('dashboard') }}" class="d-block font-weight-bold text-white">{{ auth()->user()->name }}</a>
          <small class="text-warning"><i class="fas fa-tag mr-1"></i>{{ auth()->user()->role->value }}</small>
        </div>
      </div>
      @endauth

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @auth
          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          @if(auth()->user()->role->belongsToCompany())
          <li class="nav-item">
            <a href="{{ route('short-urls.index') }}" class="nav-link {{ request()->routeIs('short-urls.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-link"></i>
              <p>Short URLs</p>
            </a>
          </li>
          @endif
          <li class="nav-item mt-3">
            <a href="#" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();" class="nav-link text-danger">
              <i class="nav-icon fas fa-power-off"></i>
              <p>Log out</p>
            </a>
            <form id="sidebar-logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                @csrf
            </form>
          </li>
          @else
          <li class="nav-item">
            <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
              <i class="nav-icon fas fa-sign-in-alt"></i>
              <p>Log in</p>
            </a>
          </li>
          @endauth
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold text-dark">@yield('title', 'Dashboard')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item active">@yield('title', 'Dashboard')</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <h5><i class="icon fas fa-check-circle"></i> Success!</h5>
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <h5><i class="icon fas fa-ban"></i> Action Failed!</h5>
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @yield('content')
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
      AdminLTE Dashboard Integration
    </div>
    <strong>URL Shortener System &copy; {{ date('Y') }}.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
    function copyToClipboard(text, buttonElement) {
        navigator.clipboard.writeText(text).then(function() {
            var originalText = buttonElement.innerHTML;
            buttonElement.innerHTML = '<i class="fas fa-check"></i> Copied!';
            buttonElement.classList.remove('btn-outline-primary', 'btn-outline-info', 'btn-secondary');
            buttonElement.classList.add('btn-success');
            setTimeout(function() {
                buttonElement.innerHTML = originalText;
                buttonElement.classList.remove('btn-success');
                buttonElement.classList.add('btn-outline-primary');
            }, 2000);
        }).catch(function(err) {
            alert('Failed to copy: ' + text);
        });
    }
</script>

@stack('scripts')
</body>
@endif
</html>
