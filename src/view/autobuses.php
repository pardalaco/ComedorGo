<?php
require_once "../config/db.php";
require_once "../models/Autobus.php";

$activePage = 'autobuses'; // Para resaltar la página activa en el sidebar
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ComedorGo - Autobuses</title>

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
                            <h3 class="mb-0">Autobuses</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Autobus</li>
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

                    <button class="btn btn-primary" onclick="location.href='autobus_add.php'">
                        Añadir autobus
                    </button>
                </div>
                <!-- ./Botones arriba de la tabla -->

                <?php

                $autobuses = getAllAutobuses();
                foreach ($autobuses as $autobus) {

                ?>

                    <!--begin::Autobus-->
                    <div class="card card-warning card-outline mb-4">
                        <!--begin::Header-->
                        <div class="card-header">
                            <div class="card-title" style="margin-right: 10px;"><?= $autobus->getNombre() ?></div>

                            <a href="autobus_modify.php?autobusid=<?= $autobus->getId() ?>" class="text-secondary" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </div>
                        <!--end::Header--> <!--begin::Body-->
                        <div class="card-body">
                            <p class="muted">
                                <?= $autobus->getDescripcion() ?>
                            </p>
                            <hr />
                            <ul class="list-group">
                                <?php

                                $comensales = $autobus->getComensales();
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
                    <!--end::Autobus-->
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