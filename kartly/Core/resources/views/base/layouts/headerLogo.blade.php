 <div class="logo pl-20 align-items-center d-flex py-3">
     @if (get_setting('admin_dark_logo') != null)
         <a href="{{ route('admin.dashboard') }}" class="default-logo">
             <img src="{{ asset(getFilePath(get_setting('admin_dark_logo'))) }}">
         </a>
     @else
         <h3 class="default-logo text-white">{{ get_setting('system_name') }}</h3>
     @endif
 </div>
