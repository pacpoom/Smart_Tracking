<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    {{-- แก้ไข: เพิ่ม class d-flex, flex-column, และ align-items-center เพื่อจัดกลาง --}}
    <a class="navbar-brand m-0 d-flex flex-column align-items-center" href="{{ route('dashboard') }}">
      <img src="{{ asset('assets/img/logo.png') }}" class="navbar-brand-img h-100" alt="main_logo" style="max-height: 90px;">
      <span class="mt-2 font-weight-bold text-white">AnJi-NYK</span>
    </a>
  </div>
  <hr class="horizontal light mt-0 mb-2">
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      {{-- ส่วนนี้จะดึงเมนูมาจาก Component ที่เราสร้างไว้ --}}
      <x-menu-items />
    </ul>
  </div>
</aside>
