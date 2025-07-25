<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <i class="material-symbols-rounded p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav">close</i>
    <a class="navbar-brand m-0 d-flex flex-column align-items-center" href="{{ route('dashboard') }}">
      <img src="{{ asset('assets/img/logo.png') }}" alt="main_logo" style="max-height: 40px; width: auto; border-radius: 8px;">
      <span class="mt-2 font-weight-bold text-white">AnJI | NYK</span>
    </a>
  </div>
  <hr class="horizontal light mt-0 mb-2">
  <div class="collapse navbar-collapse w-auto max-height-vh-100" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <x-menu-items />
    </ul>
  </div>
</aside>
