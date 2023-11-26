<nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-2" style="border-bottom: 7px solid #979797;">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mymenu" aria-controls="mymenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mymenu">
            
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item mx-5">
                    <a class="nav-link h5" target="_self" href="/admin/dashboard"><i class='fa fa-home'></i> خانه </a>
                </li>
                @if(\App\Facades\AuthAdminFacade::getAdminTypeId() == 1)
                    <li class="nav-item mx-5">
                        <a class="nav-link h5" target="_self" href="/admin/request"><i class='fa fa-clipboard-list'></i> لیست درخواست ها</a>
                    </li>
                @endif
                @if(\App\Facades\AuthAdminFacade::getAdminTypeId() == 1)
                    <li class="nav-item mx-5">
                        <a class="nav-link h5" target="_self" href="/admin/admin"><i class='fa fa-file-word'></i> مدیریت کاربران</a>
                    </li>
                @endif
                @if(\App\Facades\AuthAdminFacade::getAdminTypeId() == 1)
                    <li class="nav-item mx-5">
                        <a class="nav-link h5" target="_self" href="/admin/report"><i class='fa fa-file-word'></i> سیستم گزارشگیری</a>
                    </li>
                @endif
                <div class="btn-group dropleft">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Dropleft
                    </button>
                    <div class="dropdown-menu">
                        <!-- Dropdown menu links -->
                    </div>
                </div>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ get_admin_name() }} <i class='fa fa-user-circle h3'></i> </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="_self" id="admin-logout" href="/admin/logout"><i class='fa fa-sign-out-alt h3'></i></a>
                </li>
            </ul>
            
        </div>
    </div>
</nav>