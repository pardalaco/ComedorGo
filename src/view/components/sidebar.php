<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="index.php" class="brand-link">
            <!--begin::Brand Image-->
            <img
                src="../img/logo.png"
                alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">MenjadorGo</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                aria-label="Main navigation"
                data-accordion="false"
                id="navigation">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?= ($activePage == 'index') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-graph-up"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="asistencia.php" class="nav-link <?= ($activePage == 'asistencia') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-check2-circle"></i>
                        <p>Assistència</p>
                    </a>
                </li>
                <li class="nav-item <?= ($activePage == 'alumnos' || $activePage == 'menus' || $activePage == 'mesas' || $activePage == 'autobuses') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= ($activePage == 'alumnos' || $activePage == 'menus' || $activePage == 'mesas' || $activePage == 'autobuses') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>
                            Gestió
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="alumnos.php" class="nav-link <?= ($activePage == 'alumnos') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-person"></i>
                                <p>Alumnes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="menus.php" class="nav-link <?= ($activePage == 'menus') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-egg-fill"></i>
                                <p>Menús</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="mesas.php" class="nav-link <?= ($activePage == 'mesas') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-border-all"></i>
                                <p>Mesas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="autobuses.php" class="nav-link <?= ($activePage == 'autobuses') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-car-front-fill"></i>
                                <p>Autobusos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="../templates/AdminLTE/dist/index.html" class="nav-link <?= ($activePage == 'asistencia') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-compass"></i>
                        <p>AdminLTE</p>
                    </a>
                </li>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->