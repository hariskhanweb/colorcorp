<div class="lg:w-1/4 w-full">
  <ul class="user_pages">
    <li class="lg:text-base text-sm mb-1">                        
      <a href="{{ route('account') }}" class="lg:text-lg text-base block hover:bg-white bg-gray-100 py-2 px-4 {!! (Request::is('account') ? 'active' : '') !!}" href="{{ route('account') }}">{{ __('My Account') }} <i class="fa fa-angle-right"></i>
      </a>
    </li>
    <li class="lg:text-base text-sm mb-1">
      <a href="{{ route('password.reset') }}" class="lg:text-lg text-base block hover:bg-white bg-gray-100 py-2 px-4 {!! (Request::url() == route('password.reset') ? 'active' : '') !!}">{{ __('Change password') }} <i class="fa fa-angle-right"></i>
      </a>
    </li>
    <li class="lg:text-base text-sm mb-1">
      <a href="{{ route('my.order') }}" class="lg:text-lg text-base block hover:bg-white bg-gray-100 py-2 px-4 {!! (Request::url() == route('my.order') ? 'active' : '') !!}">{{ __('My orders') }} <i class="fa fa-angle-right"></i>
      </a>
    </li>
    <!--<li class="lg:text-base text-sm mb-1">
        <a href="#" class="lg:text-lg text-base block hover:bg-white bg-gray-100 py-2 px-4">{{ __('Wishlists') }}
         <i class="fa fa-angle-right"></i></a>
    </li>-->
    <li class="lg:text-base text-sm mb-1">
      <a href="{{ route('account.addresses') }}" class="lg:text-lg text-base block hover:bg-white bg-gray-100 py-2 px-4
         {!! (Request::is('account/addresses') || Request::is('account/addresses*') ? 'active' : '') !!}">{{ __('Addresses') }} <i class="fa fa-angle-right"></i>
      </a>
    </li>
    <li class="lg:text-base text-sm mb-1">
      <a href="{{ route('logout') }}" class="lg:text-lg text-base block hover:bg-white bg-gray-100 py-2 px-4">{{ __('Logout') }} <i class="fa fa-angle-right"></i>
      </a>
    </li>
  </ul>
</div>

 