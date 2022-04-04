<div class="sidebar off-canvas-sidebar" data-color="blue">
    <!-- Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow" -->
    <div class="logo">
        <a href="{{ route('home') }}" class="simple-text logo-mini" title="Fiabilis">
            <img class="img-fluid" src="{{asset('assets/img/logo-md.svg')}}">
        </a>
        <!--Nombre de la empresa-->
        <a href="{{ route('home') }}" class="simple-text logo-normal pt-0" title="Fiabilis">
            <img width="95" class="img-fluid" src="{{asset('assets/img/logo-md.svg')}}">
        </a>
        <div class="navbar-minimize">
            <button id="minimizeSidebar" class="btn btn-simple btn-icon btn-neutral btn-round">
                <i class="now-ui-icons text_align-center visible-on-sidebar-regular"></i>
                <i class="now-ui-icons design_bullet-list-67 visible-on-sidebar-mini"></i>
            </button>
        </div>
    </div>
    <div class="sidebar-wrapper ps-container ps-theme-default">
        <ul class="nav">

            {{-- <li class="{{ request()->is('admin/usuarios/*') ? 'active' : '' }}">
                <a data-toggle="collapse" href="#sidebar-admin" aria-expanded="false" class="collapsed">
                    <span class="sidebar-mini-icon"><i class="now-ui-icons ui-2_settings-90"></i></span>
                    <p class="sidebar-normal">Administración <b class="caret"></b></p>
                </a>
                <div class="clearfix"></div>
                <div class="collapse" id="sidebar-admin">
                    <ul class="nav">

                        <li class="{{ request()->is('admin/empresas/index') ? 'active' : '' }}">
                            <a href="">
                                <span class="sidebar-mini-icon">E</span>
                                <p class="sidebar-normal">Empresas</p>
                            </a>
                        </li>
                        <li class="{{ request()->is('admin/empresas/create') ? 'active' : '' }}">
                            <a href="">
                                <span class="sidebar-mini-icon"><i class="fas fa-plus"></i></span>
                                <p class="sidebar-normal">Crear empresa</p>
                            </a>
                        </li>

                    </ul>
                </div>
            </li> --}}
            
            <li class="{{ request()->is('admin/empresas/index') ? 'active' : '' }}">
                <a href="{{ route('admin.empresas.index') }}">
                    <span class="sidebar-mini-icon"><i class="fa fa-building business_badge"></i></span>
                    <p class="sidebar-normal">Empresas</p>
                </a>
            </li>
            
            <li class="{{ request()->is('admin/empresas/create') ? 'active' : '' }}">
                <a href="{{ route('admin.empresas.create') }}">
                    <span class="sidebar-mini-icon"><i class="fa fa-industry business_badge"></i></span>
                    <p class="sidebar-normal">Crear empresa</p>
                </a>
            </li>

            <li class="{{ request()->is('admin/razones-sociales/index') ? 'active' : '' }}">
                <a href="{{ route('admin.razones-sociales.index') }}">
                    <span class="sidebar-mini-icon"><i class="now-ui-icons business_badge"></i></span>
                    <p class="sidebar-normal">Razones Sociales</p>
                </a>
            </li>
            
            <li class="{{ request()->is('admin/usuarios/index') ? 'active' : '' }}">
                <a href="{{ route('admin.usuarios.index') }}">
                    <span class="sidebar-mini-icon"><i class="fa fa-fw fa-user business_badge"></i></span>
                    <p class="sidebar-normal">Usuarios</p>
                </a>
            </li>
            
            <li class="{{ request()->is('admin/usuarios/create') ? 'active' : '' }}">
                <a href="{{ route('admin.usuarios.create') }}">
                    <span class="sidebar-mini-icon"><i class="fa fa-fw fa-users business_badge"></i></span>
                    <p class="sidebar-normal">Crear usuario</p>
                </a>
            </li>
            
            <li class="{{ request()->is('admin/herramientas/*') ? 'active' : '' }}">
                <a href="{{ route('admin.herramientas.index') }}">
                    <span class="sidebar-mini-icon"><i class="fa fa-fw fa-wrench business_badge"></i></span>
                    <p class="sidebar-normal">Herramientas</p>
                </a>
            </li>

        </ul>
        <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;">
            <div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps-scrollbar-y-rail" style="top: 0px; right: 0px;">
            <div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div>
        </div>
    </div>
</div>