<!-- PAGINA PRINCIPAL -->
<?php
require_once "../config/db.php";
require_once "../src/models/Mesa.php";

$activePage = 'mesas'; // Para resaltar la página activa en el sidebar
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ComedorGo - Mesas</title>

    <?php include './components/head.html'; ?>

</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">


        <?php
        //include './components/header.html'; 
        ?>
        <?php include './components/header.html'; ?>
        <?php include './components/sidebar.php'; ?>


        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Mesas</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Mesa</li>
                            </ol>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
            <!--begin::App Content-->
            <div class="app-content">

                <!-- Botones arriba de la tabla -->
                <div class="d-flex justify-content-end mb-2">

                    <button class="btn btn-primary" onclick="location.href='mesa_add.php'">
                        Añadir mesa
                    </button>
                </div>
                <!-- ./Botones arriba de la tabla -->

                <?php

                $mesas = getAllMesas();
                foreach ($mesas as $mesa) {

                ?>

                    <!--begin::Mesa-->
                    <div class="card card-primary card-outline mb-4">
                        <!--begin::Header-->
                        <div class="card-header">
                            <div class="card-title" style="margin-right: 10px;"><?= $mesa->getNombre() ?></div>

                            <a href="mesa_modify.php?mesaid=<?= $mesa->getId() ?>" class="text-secondary" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </div>
                        <!--end::Header--> <!--begin::Body-->
                        <div class="card-body">
                            <p class="muted">
                                <?= $mesa->getDescripcion() ?>
                            </p>
                            <hr />
                            <ul class="list-group">
                                <?php

                                $comensales = $mesa->getComensales();
                                foreach ($comensales as $comensal) {

                                ?>
                                    <li class="list-group-item list-group-item-action"> <?php echo $comensal->getNombre() . " " . $comensal->getApellidos() ?></li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Mesa-->
                <?php
                }
                ?>

            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <?php include './components/footer.html'; ?>

    </div>
    <!--end::App Wrapper-->
    <?php include './components/scripts.html'; ?>



</body>
<!--end::Body-->

</html>