@if(\Illuminate\Support\Facades\Auth::user()->role == \App\Admin::$ROLE_STORE)
<li class="{{ Request::is('admin/reservations*') ? 'active' : '' }}">
    <a href="{!! route('admin.reservations.index') !!}"><i class="fa fa-calendar"></i><span>Reservas</span></a>
</li>
@endif

@if(\Illuminate\Support\Facades\Auth::user()->role == \App\Admin::$ROLE_ADMIN)
    <li class="{{ Request::is('admin/brands*') ? 'active' : '' }}">
        <a href="{!! route('admin.brands.index') !!}"><i class="fa fa-building"></i><span>Marcas</span></a>
    </li>

    <li class="{{ Request::is('admin/stores*') ? 'active' : '' }}">
        <a href="{!! route('admin.stores.index') !!}"><i class="fa fa-map-marker"></i><span>Sucursales</span></a>
    </li>

    <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
        <a href="{!! route('admin.users.index') !!}"><i class="fa fa-address-book"></i><span>Clientes</span></a>
    </li>

    <li class="{{ Request::is('admin/admin_users*') ? 'active' : '' }}">
        <a href="{!! route('admin.admin_users.index') !!}"><i class="fa fa-users"></i><span>Usuarios administradores</span></a>
    </li>

    <li class="{{ Request::is('admin/reservations*') ? 'active' : '' }}">
        <a href="{!! route('admin.reservations.index') !!}"><i class="fa fa-calendar"></i><span>Reservas</span></a>
    </li>

    <li class="{{ Request::is('admin/reports*') ? 'active' : '' }}">
        <a href="{!! route('admin.reports.index') !!}"><i class="fa fa-file"></i><span>Reportes</span></a>
    </li>

    <li class="header">Sistema</li>

    {{--<li class="{{ Request::is('admin/settings*') ? 'active' : '' }}">
        <a href="{!! route('admin.settings.index') !!}"><i class="fa fa-gears"></i><span>Configuraciones</span></a>
    </li>--}}

    <li class="{{ Request::is('admin/audit*') ? 'active' : '' }}">
        <a href="{{ route('admin.auditories.index') }}"><i class="fa fa-tasks"></i><span>Log de actividad</span></a>
    </li>

    {{--<li class="{{ Request::is('admin/reservationFlats*') ? 'active' : '' }}">
        <a href="{!! route('admin.reservationFlats.index', ['orderBy'=>'id', 'sortedBy'=>'desc']) !!}"><i class="fa fa-calendar-o"></i><span>Reservas (planas)</span></a>
    </li>--}}
@endif

@if(\Illuminate\Support\Facades\Auth::user()->role == \App\Admin::$ROLE_BRAND)
    <li class="{{ Request::is('admin/stores*') ? 'active' : '' }}">
        <a href="{!! route('admin.stores.index') !!}"><i class="fa fa-map-marker"></i><span>Sucursales</span></a>
    </li>

    <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
        <a href="{!! route('admin.users.index') !!}"><i class="fa fa-address-book"></i><span>Clientes</span></a>
    </li>

    <li class="{{ Request::is('admin/admin_users*') ? 'active' : '' }}">
        <a href="{!! route('admin.admin_users.index') !!}"><i class="fa fa-users"></i><span>Usuarios administradores</span></a>
    </li>

    <li class="{{ Request::is('admin/reservations*') ? 'active' : '' }}">
        <a href="{!! route('admin.reservations.index') !!}"><i class="fa fa-calendar"></i><span>Reservas</span></a>
    </li>

    <li class="{{ Request::is('admin/reports*') ? 'active' : '' }}">
        <a href="{!! route('admin.reports.index') !!}"><i class="fa fa-file"></i><span>Reportes</span></a>
    </li>
@endif