<!doctype html>
<html lang="en">

<head>
    <title>Tableau de bord</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/theme/fonts/font-awesome-4.7.0/css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?= base_url('assets/theme/css/style.css') ?>">
</head>

<body>

    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar">
            <h1><a href="index.html" class="logo">Tr.</a></h1>
            <ul class="list-unstyled components mb-5">
                <!--<li class="active">
                    <a ><span class="fa fa-tachometer"></span> Tableau de bord</a>
                </li>-->
                
                <li>
                  <a href="#submenu-suivi" data-bs-toggle="collapse"><span class="fa fa-clock-o"></span> Suivi</a>
                  <ul id="submenu-suivi" class="list-unstyled components collapse">
                      <li class="pl-5">
                        <a href="#"> Progression</a>
                      </li>
                      <li class="pl-5">
                        <a href="#"> Temps réel</a>
                      </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><span class="fa fa-cogs"></span> Administration</a>
                </li>
                <li>
                    <a href="#"><span class="fa fa-user"></span> Profil</a>
                </li>
                <li>
                    <a href="#"><span class="fa fa-sign-out"></span> Déconnexion</a>
                </li>
            </ul>

            
        </nav>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <!--<button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle Menu</span>
                    </button>-->
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                      <div class="col-md-2">
                        <i class="fa fa-user-circle-o mr-2"></i><span id="ctrl-statut" class="small"></span>
                    </div>
                      <div id="ctrl-buttons" class="col-md-4  offset-6 text-right">
                        <button type="button" id="btn-ctrl-debut" class="btn btn-sm btn-primary"><i class="fa fa-play pr-2"></i>Début</button>
                        <button type="button" id="btn-ctrl-pause" class="btn btn-sm btn-warning"><i class="fa fa-pause-circle pr-2"></i>Pause</button>
                        <button type="button" id="btn-ctrl-reprise" class="btn btn-sm btn-success"><i class="fa fa-play-circle pr-2"></i>Reprendre</button>
                        <button type="button" id="btn-ctrl-fin" class="btn btn-sm btn-danger"><i class="fa fa-stop-circle pr-2"></i>Fin de shift</button>
                      </div>
                    </div>
                </div>
            </nav>

            <h2 class="mb-4">Sidebar #07</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                deserunt mollit anim id est laborum.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                deserunt mollit anim id est laborum.</p>
        </div>
    </div>

    <script src="<?= base_url('assets/theme/js/jquery.min.js')?>"></script>
    <script src="<?= base_url('assets/theme/js/popper.js')?>"></script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
    <script src="<?= base_url('assets/theme/js/main.js')?>"></script>

</body>

</html>