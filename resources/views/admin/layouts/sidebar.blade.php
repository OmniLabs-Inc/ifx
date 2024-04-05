<aside class="s7__sidebar">
  <button type="button" class="sidebar-close-btn"><i class="las la-times-circle"></i></button>
  <div class="s7__logo">
     <a href="{{route('admin.home')}}" class="long-logo"><img src="{{asset('images/logo/logo.png')}}" alt="logo image"></a>
     <a href="{{route('admin.home')}}" class="short-logo-icon"><img class="cust-short-logo" src="{{asset('images/logo/favicon.png')}}" alt="logo image"></a>
  </div>
  <div class="s7__sidebar-nav-wrapper" data-simplebar>
     <ul class="s7__sidebar-nav" id="s7__sidebar-nav">
        <li>
           <a class="sidebar-link" href="{{route('admin.home')}}">
           <span data-feather="home" class="nav-icon"></span>
           <span class="s7__nav-caption">{{__('Dashboard')}}</span>
           </a>
        </li>

        <li class="s7__menu-title">
            <span>{{__('MANAGE USER')}} </span>
         </li>

          <li>
             <a class="sidebar-link" href="{{route('user.manage')}}">
                <span data-feather="users" class="nav-icon"></span>
             <span class="s7__nav-caption">{{__('All Users')}}</span>
             </a>
          </li>

          <li>
             <a class="sidebar-link" href="{{route('active.user.manage')}}">
                <span data-feather="user-check" class="nav-icon"></span>
             <span class="s7__nav-caption">{{__('Active Users')}}</span>
             </a>
          </li>

          <li>
             <a class="sidebar-link" href="{{route('ban.user.manage')}}">
             <span data-feather="user-x" class="nav-icon"></span>
             <span class="s7__nav-caption">{{__('Inactive Users')}}</span>
             </a>
          </li>

          <li class="s7__menu-title">
            <span>{{__('ALL TRANSACTION')}} </span>
         </li>

         <li>
            <a class="sidebar-link" href="{{route('transaction.log.admin')}}">
            <span data-feather="file-text" class="nav-icon"></span>
            <span class="s7__nav-caption">{{__('Transaction Log')}}</span>
            </a>
         </li>

         <li>
            <a class="sidebar-link" href="{{route('admin.deposit.depositLog')}}">
              <span data-feather="file-text" class="nav-icon"></span>
            <span class="s7__nav-caption">{{__('Deposit Log')}}</span>
            </a>
         </li>

         <li>
            <a class="sidebar-link" href="{{route('withdraw.request.index')}}">
            <span data-feather="loader" class="nav-icon"></span>
            <span class="s7__nav-caption">{{__('Pending Withdraw')}}</span>
            </a>
         </li>

         <li>
            <a class="sidebar-link" href="{{route('withdraw.viewlog.admin')}}">
            <span data-feather="file-text" class="nav-icon"></span>
            <span class="s7__nav-caption">{{__('Withdraw Log')}}</span>
            </a>
         </li>

         @if(can_access('manage_user'))
         <li>
            <a class="sidebar-link" href="{{route('admin.user')}}">
                <span data-feather="users" class="nav-icon"></span>
            <span class="s7__nav-caption" >{{__('Admin Users')}}</span>
            </a>
         </li>
        @endif


        <li class="s7__menu-title">
           <span>{{__('CONTROLS')}} </span>
        </li>

         @if(can_access('settings'))
        <li>
           <a class="sidebar-link" href="{{route('admin.gnl.set')}}">
           <span data-feather="settings" class="nav-icon"></span>
           <span class="s7__nav-caption">{{__('Settings')}} </span>
           </a>
        </li>
         @endif


        @if(can_access('logo'))
        <li>
          <a class="sidebar-link" href="{{route('logo-icon.index')}}">
          <span data-feather="image" class="nav-icon"></span>
          <span class="s7__nav-caption">{{__('Logo & Icon')}}</span>
          </a>
       </li>
        @endif



     </ul>
  </div>
</aside>
