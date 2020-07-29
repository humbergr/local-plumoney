<header>
    <div class="grid-container">
        <div class="grid-x">
            <div class="cell header-actions">
                <div class="notifications">
                    <div class="not-local not-wrapper">
                        <a href="javascript:void(0);">
                            <img src="{{ asset('img/icons/not-local-icon.png') }}" alt="Notifications from Local">
                        </a>
                        <div class="not-alert"></div>
                    </div>
                    <div class="not-plu not-wrapper">
                        <a href="javascript:void(0);">
                            <img src="{{ asset('img/icons/not-plu-icon.png') }}" alt="Notifications from Local">
                        </a>
                        <div class="not-alert"></div>
                    </div>
                </div>
                <div class="user-actions">
                    <div class="avatar-top">
                        <img src="{{ asset('img/icons/avatar-icon.png') }}" alt="Avatar">
                    </div>
                    <div class="user-actions">
                        <ul id="actions-menu">
                          <li>
                            <a href="{{URL::to('user-settings')}}">{{Auth::user()->name}}</a>
                            <ul>
                              <li><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                              @if(Auth::user()->role_id == 1)
                              <li><a href="{{URL::to('/create-user')}}">Invite user</a></li>
                              <li><a href="{{URL::to('/transactions')}}">Transactions</a></li>
                              <li><a href="{{URL::to('/error-transactions')}}">Error Transactions</a></li>
                              @endif
                              <li><a href="{{URL::to('/search')}}">Search Ads</a></li>
                              <li><a href="{{URL::to('/create-antifraud')}}">Create Antifraud</a></li>
                              <li><a href="{{URL::to('/antifraud-forms')}}">Forms List</a></li>
                            </ul>
                          </li>
                        </ul>
                    </div>
              <!--      <a href="javascript:void(0)">
                        {{Auth::user()->name}}
                    </a>  -->
                    <form id="logout-form"
                          action="{{ route('logout') }}"
                          method="POST">
                        @csrf
                        <button type="submit">
                            <img src="{{ asset('img/icons/logout-icon.png') }}" alt="Log Out">
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
