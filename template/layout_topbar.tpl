<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                {if $nbAlertesStock > 0}
                    <span class="badge badge-danger badge-counter">
                        {$nbAlertesStock}
                    </span>
                {/if}
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Alerte stock bas
                </h6>

                {if $nbAlertesStock > 0}
                    {foreach $alertesStock as $produit}
                        <a class="dropdown-item d-flex align-items-center" href="index.php?action=detail_produit&Id_produit={$produit.Id_produit}">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="font-weight-bold">{$produit.Nom_produit|escape}</div>
                                <span>Stock : {$produit.Stock}</span>
                            </div>
                        </a>
                    {/foreach}
                {else}
                    <div class="dropdown-item text-gray-700">
                        Aucune alerte de stock
                    </div>
                {/if}
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{$utilisateur.Prenom_vendeur} {$utilisateur.Nom_vendeur}</span>
                <img class="img-profile rounded-circle"
                     src="img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="index.php?action=profil">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="index.php?action=logout">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Déconnexion
                </a>
            </div>
        </li>

    </ul>

</nav>
