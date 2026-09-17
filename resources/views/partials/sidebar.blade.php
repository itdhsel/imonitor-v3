<!-- SIDEBAR -->
<div class="sidebar p-4 position-fixed">
    <h4 class="mb-4 pb-3 border-bottom border-secondary fw-bold text-white tracking-wide">
        🏥 iMonitor v3
    </h4>
    <ul class="nav flex-column gap-2 mt-4">
        <li class="nav-item">
            <a class="nav-link rounded px-3 py-2 {{ request()->routeIs('monitor.*') ? 'active' : '' }}" href="{{ route('monitor.index') }}">
                📊 Patient Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded px-3 py-2 {{ request()->routeIs('counselling.*') ? 'active' : '' }}" href="{{ route('counselling.index') }}">
                💊 Counselling Request
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded px-3 py-2 {{ request()->routeIs('collection.*') ? 'active' : '' }}" href="{{ route('collection.index') }}">
                📦 Discharge Collection
            </a>
        </li>
    </ul>
</div>