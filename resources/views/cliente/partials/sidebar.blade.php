<div class="sidebar" data-color="blue">
    <!-- Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow" -->
    <div class="logo">
        <a href="#" class="simple-text logo-mini" title="Portal">
            <img src="{{asset('assets/img/logo-md.png')}}">
        </a>
        <!--Nombre de la empresa-->
        <a href="#" class="simple-text logo-normal" title="Portal">Fiabilis</a>
        <div class="navbar-minimize">
            <button id="minimizeSidebar" class="btn btn-simple btn-icon btn-neutral btn-round">
                <i class="now-ui-icons text_align-center visible-on-sidebar-regular"></i>
                <i class="now-ui-icons design_bullet-list-67 visible-on-sidebar-mini"></i>
            </button>
        </div>
    </div>
    <div class="sidebar-wrapper ps-container ps-theme-default">
        <ul class="nav">
            
            <li class="{{ request()->is('cliente/razones-sociales/index') ? 'active' : '' }}">
                <a href="{{ route('cliente.razones-sociales.index') }}">
                    <span class="sidebar-mini-icon"><i class="fa-solid fa-layer-group"></i></span>
                    <p class="sidebar-normal">Razones Sociales</p>
                </a>
            </li>
            <li class="{{ request()->is('cliente/gestiones/index') ? 'active' : '' }}">
                <a href="{{ route('cliente.gestiones.index') }}">
                    <span class="sidebar-mini-icon"><i class="fa-solid fa-briefcase"></i></span>
                    <p class="sidebar-normal">Gestiones</p>
                </a>
            </li>
            <li class="{{ request()->is('cliente/facturas/index') ? 'active' : '' }}">
                <a href="{{ route('cliente.facturas.index') }}">
                    <span class="sidebar-mini-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                    <p class="sidebar-normal">Servicios por cobrar</p>
                </a>
            </li>
            <li class="{{ request()->is('cliente/gestiones-historicas/index') ? 'active' : '' }}">
                <a href="{{ route('cliente.gestiones-historicas.index') }}">
                    <span class="sidebar-mini-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                    <p class="sidebar-normal">gestiones Históricas</p>
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