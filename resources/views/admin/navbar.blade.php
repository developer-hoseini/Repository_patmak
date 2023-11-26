<nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-2" style="border-bottom: 7px solid #979797;">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mymenu" aria-controls="mymenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mymenu">
            
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <button class="btn btn-secondary" disabled>پنل مدیریت</button>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="_self" href="/admin/application"><i class='fa fa-clipboard-list'></i> لیست درخواست ها</a>
                </li>
                @if(\App\Facades\AuthAdminFacade::getAdminTypeId() == 1)
                <li class="nav-item">
                    <a class="nav-link" target="_self" href="/admin/report"><i class='fa fa-clipboard-list'></i> گزارش</a>
                </li>
                @endif
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class='fa fa-user-circle'></i> پروفایل {{ get_admin_name(); }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" target="_self" id="admin-logout" href="/admin/logout"><i class='fa fa-sign-out-alt'></i> خروج</a>
                </li>
            </ul>
            
        </div>
    </div>
</nav>