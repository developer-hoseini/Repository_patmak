<nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-2">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mymenu" aria-controls="mymenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mymenu">
            
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard"><i class='fa fa-home'></i> خانه</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/application/list"><i class='fa fa-clipboard-list'></i> سابقه درخواست ها</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/application/price-list"><i class='fa fa-money-bill'></i> جدول نرخ</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class='fa fa-user-circle'></i> پروفایل</a>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false" id="dropdownMenuButton1">
                            <i class='fa fa-bell'></i>
                            پروفایل
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="notifDropdown">

                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="logout" href="/logout"><i class='fa fa-sign-out-alt'></i> خروج</a>
                </li>
            </ul>
            
        </div>
    </div>
</nav>