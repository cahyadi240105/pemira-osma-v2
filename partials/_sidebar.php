<?php 
    require_once 'auth/function.php';
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link <?= $current_page == 'index.php' ? 'active' : ''; ?> " href="index.php">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $current_page == 'voting.php' ? 'active' : ''; ?>" href="voting.php">
                <i class="mdi mdi-ballot-outline menu-icon"></i>
                <span class="menu-title">Voting</span>
            </a>
        </li>
        <?php if(isAdmin()): ?>
        <li class="nav-item">
            <a class="nav-link <?= $current_page == 'kandidat.php' ? 'active' : ''; ?>" href="kandidat.php">
                <i class="mdi mdi-account-group-outline menu-icon"></i>
                <span class="menu-title">Kelola Kandidat</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $current_page == 'pengguna.php' ? 'active' : ''; ?>" href="pengguna.php">
                <i class="mdi mdi-account-outline menu-icon"></i>
                <span class="menu-title">Kelola Pengguna</span>
            </a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">
                <i class="ti-power-off menu-icon"></i>
                <span class="menu-title">Logout</span>
            </a>
        </li>
    </ul>
</nav>