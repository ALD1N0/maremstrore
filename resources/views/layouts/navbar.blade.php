<header class="navbar">
    <div class="navbar-container">
        <h2 class="logo">MAREM STORE</h2>
        <div class="menu-toggle" id="menu-toggle">
            ☰
        </div>
        <nav class="nav-menu" id="nav-menu">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('produk.index') }}" class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                Barang
            </a>
            <a href="{{ route('riwayat') }}" class="nav-link {{ request()->routeIs('riwayat') ? 'active' : '' }}">
                Riwayat
            </a>
            <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                Profile
            </a>
          
        </nav>
    </div>
</header>
<script>
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('nav-menu');
    toggle.addEventListener('click', () => {
        menu.classList.toggle('active');
    });
</script>
