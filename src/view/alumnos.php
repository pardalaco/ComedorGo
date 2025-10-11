<?php
require_once "../config/db.php";
require_once "../models/Comensal.php";

$activePage = 'alumnos'; // Para resaltar la página activa en el sidebar

?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ComedorGo - Alumnes</title>

    <?php include './components/head.html'; ?>

    <!-- Tabla -->
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="../assets/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../assets/css/responsive.bootstrap5.min.css">

</head>
<!--end::Head-->

<!-- jQuery -->
<script src="../assets/js/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/dataTables.bootstrap5.min.js"></script>
<script src="../assets/js/dataTables.responsive.min.js"></script>
<script src="../assets/js/responsive.bootstrap5.min.js"></script>


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
                            <h3 class="mb-0">Alumnes</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Alumnes</li>
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
                <!--begin::Container-->

                <!-- Botones arriba de la tabla -->
                <div class="d-flex justify-content-end mb-2">

                    <button class="btn btn-primary" onclick="location.href='alumno_add.php'">
                        Afegir alumne
                    </button>
                </div>
                <!-- ./Botones arriba de la tabla -->


                <!-- begin::Tabla -->
                <div class="card">
                    <div class="card-body">

                        <?php

                        $comensales = getComensales();
                        // print_r($comensales);

                        // $index_comensales = 1;
                        ?>
                        <table class="table table-bordered table-hover mi-tabla" id="mi-tabla">
                            <thead>
                                <tr>
                                    <!-- <th style="width: 10px">#</th> -->
                                    <th>Nom</th>
                                    <th>Cognom</th>
                                    <th>Menú</th>
                                    <th>Taula</th>
                                    <th>Autobús</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($comensales as $comensal) {

                                ?>
                                    <tr class="align-middle"
                                        data-UserId="<?= $comensal->getId() ?>">
                                        <!-- <td>
                                    <?= $index_comensales++ ?>.
                                </td> -->
                                        <td><?= $comensal->getNombre() ?></td>
                                        <td><?= $comensal->getApellidos()  ?></td>
                                        <td><?= $comensal->getMenuName()  ?></td>
                                        <td><?= $comensal->getMesaName()  ?></td>
                                        <td><?= $comensal->getAutobusName()  ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                        <!-- end::Tabla -->


                        <!--end::Container-->
                    </div>
                </div>
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <?php include './components/footer.html'; ?>

    </div>
    <!--end::App Wrapper-->
    <?php include './components/scripts.html'; ?>

    <script>
        // Evento fila tbody
        document.querySelectorAll('.mi-tabla tbody tr').forEach(row => {
            row.addEventListener('click', function(e) {

                let UserId = this.getAttribute("data-UserId");
                console.log("UserId: ", UserId);

                window.location.href = "alumno_modify.php?userid=" + UserId;
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#mi-tabla').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 25,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                }
            });
        });
    </script>

</body>
<!--end::Body-->

</html>