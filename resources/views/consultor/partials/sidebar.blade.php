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
            
            <li class="{{ request()->is('consultor/empresas/index') ? 'active' : '' }}">
                <a href="{{ route('consultor.empresas.index') }}">
                    <span class="sidebar-mini-icon"><i class="fa-solid fa-layer-group"></i></span>
                    <p class="sidebar-normal">Empresas</p>
                </a>
            </li>
            <li class="{{ request()->is('consultor/razones-sociales/index') ? 'active' : '' }}">
                <a href="{{ route('consultor.razones-sociales.index') }}">
                    <span class="sidebar-mini-icon"><i class="fa-solid fa-briefcase"></i></span>
                    <p class="sidebar-normal">Razones Sociales</p>
                </a>
            </li>
            <li class="{{ request()->is('consultor/gestiones/index') ? 'active' : '' }}">
                <a href="{{ route('consultor.gestiones.index') }}">
                    <span class="sidebar-mini-icon"><i class="fa-solid fa-briefcase"></i></span>
                    <p class="sidebar-normal">Gestiones</p>
                </a>
            </li>
            <li class="{{ request()->is('consultor/facturas/index') ? 'active' : '' }}">
                <a href="{{ route('consultor.facturas.index') }}">
                    <span class="sidebar-mini-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                    <p class="sidebar-normal">Servicios por Cobrar</p>
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