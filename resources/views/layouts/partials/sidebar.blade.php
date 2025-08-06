<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
  <div class="sidenav-header">
    <i class="material-symbols-rounded p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav">close</i>
    <a class="navbar-brand m-0 d-flex flex-column align-items-center" href="{{ route('dashboard') }}">
      <img src="{{ asset('assets/img/logo.png') }}" alt="main_logo" style="max-height: 40px; width: auto; border-radius: 8px;">
      <span class="mt-2 font-weight-bold text-white">Your App Name</span>
    </a>
  </div>
  <hr class="horizontal light mt-0 mb-2">

  {{-- 1. เพิ่มช่องค้นหา --}}
  <div class="px-3 py-2">
    <div class="input-group input-group-outline">
        <label class="form-label text-white">Search menu...</label>
        <input type="text" class="form-control text-white" id="menu-search-input">
    </div>
  </div>

  <div class="collapse navbar-collapse w-auto max-height-vh-100" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <x-menu-items />
    </ul>
  </div>
</aside>

@push('scripts')
{{-- 2. เพิ่ม JavaScript สำหรับการค้นหา --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('menu-search-input');
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const menuItems = document.querySelectorAll('#sidenav-collapse-main .nav-item');

                menuItems.forEach(function(item) {
                    const linkTextElement = item.querySelector('.nav-link-text, .sidenav-normal');
                    if (linkTextElement) {
                        const linkText = linkTextElement.textContent.toLowerCase();
                        if (linkText.includes(searchTerm)) {
                            // Show the item and all its parents
                            item.style.display = 'block';
                            let parent = item.closest('.collapse');
                            while(parent) {
                                parent.classList.add('show');
                                let parentLi = parent.closest('.nav-item');
                                if (parentLi) {
                                    parentLi.style.display = 'block';
                                }
                                parent = parent.parentElement.closest('.collapse');
                            }
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });

                // If search is empty, reset all menus
                if (searchTerm === '') {
                    menuItems.forEach(function(item) {
                        item.style.display = 'block';
                        const collapseElement = item.querySelector('.collapse');
                        if (collapseElement) {
                            collapseElement.classList.remove('show');
                        }
                    });
                }
            });
        }
    });
</script>
@endpush
