<div class="page-main-header">
    <div class="main-header-right row m-0">
        <div class="main-header-left">
            <div class="logo-wrapper">
                <a href="javascript:void(0)" class="text-success font-weight-bold h4 mb-0"><b>SMART POS</b></a>
            </div>
            <div class="dark-logo-wrapper">
                <a href="javascript:void(0)" class="text-light font-weight-bold h4 mb-0"><b>SMART POS</b></a>
            </div>
            <div class="toggle-sidebar">
                <i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle"></i>
            </div>
        </div>

        <div class="nav-right col pull-right right-menu p-0">
            <ul class="nav-menus">
                <li class="onhover-dropdown p-0">
                    <div class="media align-items-center">
                        <img class="align-self-center rounded-circle me-2"
                            src="{{ asset('assets/images/user/user.png') }}" alt="user avatar" width="40"
                            height="40">
                        <div class="media-body d-none d-sm-block">
                            <span class="f-14 fw-bold text-dark">{{ Auth::user()->nama ?? 'User' }}</span>
                        </div>
                    </div>
                    <ul class="profile-dropdown onhover-show-div p-2">
                        <li><a href="javascript:void(0)"><i class="fa fa-user"></i> Profil</a></li>
                        <li><a href="javascript:void(0)"><i class="fa fa-cog"></i> Pengaturan</a></li>
                        <li><a href="javascript:void(0)" id="btn_logout"><i class="fa fa-sign-out"></i>
                                Keluar</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
    </div>
</div>
