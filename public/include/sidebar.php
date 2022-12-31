<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <?php if (isset($_SESSION['user_id'])) {?>
        <div class="sidebar-brand-text mx-3"><?= $userDetails['firstname']; ?></div>
        <?php } elseif (isset($_SESSION['admin_id'])) {?>
        <div class="sidebar-brand-text mx-3"><?= $adminDetails['firstname']; ?></div>
        <?php }?>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <?php
    if (isset($_SESSION['user_id'])) {
        ?>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php if ($page == 'dashboard') {?> active<?php } ?>">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item <?php if ($page == 'transaction') {?> active<?php } ?> ">
        <a class="nav-link" href="transactions.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>My Transactions</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <li class="nav-item <?php if ($page == 'save') {?> active<?php } ?>">
        <a class="nav-link" href="save_money.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Save Money</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <li class="nav-item <?php if ($page == 'transfer') {?> active<?php } ?>">
        <a class="nav-link" href="transfer_money.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Transfer Money</span></a>
    </li>



    <?php
    }

    if (isset($_SESSION['admin_id'])) { ?>
    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php if ($page == 'dashboard') {?> active<?php } ?>">
        <a class="nav-link" href="adminDashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <li class="nav-item <?php if ($page == 'details') {?> active<?php } ?>">
        <a class="nav-link" href="fetchUsers.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Registered Users</span></a>
    </li>
    <?php } ?>

</ul>
<!-- End of Sidebar -->