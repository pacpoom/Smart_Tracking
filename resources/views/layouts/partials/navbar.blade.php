<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">@yield('title', 'Page')</li>
      </ol>
      <h6 class="font-weight-bolder mb-0">@yield('title', 'Page')</h6>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        {{-- Optional: Search bar can go here --}}
      </div>
      <ul class="navbar-nav justify-content-end">

        {{-- แก้ไข: ย้ายปุ่มย่อ/ขยายเมนูมาไว้ก่อน User Dropdown --}}
        <li class="nav-item ps-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </a>
        </li>

        {{-- User Dropdown --}}
        <li class="nav-item dropdown ps-2 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ auth()->user()->profile_photo_url }}" alt="profile_image" class="avatar avatar-sm rounded-circle">
            <span class="d-sm-inline d-none font-weight-bold ps-1">{{ auth()->user()->name }}</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
            <li class="mb-2">
              <a class="dropdown-item border-radius-md" href="{{ route('profile.edit') }}">
                <div class="d-flex py-1">
                  <div class="my-auto">
                    <i class="material-symbols-rounded avatar avatar-sm bg-gradient-dark me-3">settings</i>
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      <span class="font-weight-bold">Profile</span>
                    </h6>
                  </div>
                </div>
              </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item border-radius-md" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                        <div class="d-flex py-1">
                            <div class="my-auto">
                                <i class="material-symbols-rounded avatar avatar-sm bg-gradient-dark me-3">logout</i>
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="text-sm font-weight-normal mb-1">
                                <span class="font-weight-bold">Sign Out</span>
                                </h6>
                            </div>
                        </div>
                    </a>
                </form>
            </li>
          </ul>
        </li>
        
      </ul>
    </div>
  </div>
</nav>
