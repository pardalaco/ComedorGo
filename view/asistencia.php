<!-- PAGINA PRINCIPAL -->
<?php
require_once "../config/db.php";
require_once "../src/models/Comensal.php";

?>


<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ComedorGo - Alumnos</title>

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
        <?php include './components/sidebar.html'; ?>


        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Asistencia</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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


                <div class="d-flex justify-content-end align-items-center mb-2 gap-2">
                    <label for="fecha" class="mb-0">Seleccionar fecha:</label>
                    <input type="date" id="fecha" class="form-control form-control-sm" style="width: 150px; height: 45px;">
                </div>


                <!-- begin::Tabla -->
                <?php

                $comensales = getComensales();
                // print_r($comensales);

                $index_comensales = 1;
                ?>
                <table class="table table-bordered table-hover mi-tabla">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th class="text-center">
                                <input type="checkbox"
                                    class="form-check-input"
                                    id="selectAllCheckboxes"
                                    onchange="toggleAll(this); saveAllAsistencia(this)" />
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($comensales as $comensal) {

                        ?>
                            <tr class="align-middle">
                                <td>
                                    <?= $index_comensales++ ?>.
                                </td>
                                <td><?= $comensal->getNombre() ?></td>
                                <td><?= $comensal->getApellidos()  ?></td>
                                <td class="text-center">
                                    <input type="checkbox"
                                        class="form-check-input fila"
                                        onchange="updateMaster(); saveAsistencia(this);"
                                        data-nombre="<?= $comensal->getNombre() ?>"
                                        data-apellidos="<?= $comensal->getApellidos() ?>"
                                        data-comensalID="<?= $comensal->getId() ?>" />
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
                <!-- end::Tabla -->


                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <?php include './components/footer.html'; ?>

    </div>
    <!--end::App Wrapper-->
    <?php include './components/scripts.html'; ?>

    <script>
        function saveAsistencia(checkbox) {
            // Funcion para guardar en base de datos la asistencia, hace un post

            let nombre = checkbox.getAttribute("data-nombre");
            let apellidos = checkbox.getAttribute("data-apellidos");
            let id = checkbox.getAttribute("data-comensalID");
            let asiste = checkbox.checked ? 1 : 0;

            console.log("Seleccionado:", nombre, apellidos, id, asiste);

            fetch("../src/models/ajax/guardar_asistencia.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    // body: `nombre=${encodeURIComponent(nombre)}&apellidos=${encodeURIComponent(apellidos)}&asistencia=${asiste}`
                    body: `id=${encodeURIComponent(id)}&asistencia=${asiste}`
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Respuesta del servidor:", data);
                })
                .catch(error => {
                    console.error("Error en la petición:", error);
                });
        }

        function saveAllAsistencia(checkbox) {

            let asiste = checkbox.checked ? 1 : 0;


            fetch("../src/models/ajax/guardar_asistencia.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    // body: `nombre=${encodeURIComponent(nombre)}&apellidos=${encodeURIComponent(apellidos)}&asistencia=${asiste}`
                    body: `saveAllAsistencia=${asiste}`
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Respuesta del servidor:", data);
                })
                .catch(error => {
                    console.error("Error en la petición:", error);
                });
        }

        function toggleAll(master) {
            // obtenemos todas las checkboxes con la clase "fila"
            let checkboxes = document.querySelectorAll(".fila");
            checkboxes.forEach(c => c.checked = master.checked);
        }

        function updateMaster() {
            let master = document.getElementById("selectAllCheckboxes");
            let checkboxes = document.querySelectorAll(".fila");
            // comprobamos si todas están seleccionadas
            master.checked = Array.from(checkboxes).every(c => c.checked);
        }

        // Evento fila thead
        document.querySelectorAll('.mi-tabla thead tr').forEach(row => {
            row.addEventListener('click', function(e) {
                // Evitar que al clicar la propia checkbox se doble el cambio
                if (e.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    toggleAll(checkbox);
                    saveAllAsistencia(checkbox);
                }
            });
        });

        // Evento fila tbody
        document.querySelectorAll('.mi-tabla tbody tr').forEach(row => {
            row.addEventListener('click', function(e) {
                // Evitar que al clicar la propia checkbox se doble el cambio
                if (e.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    updateMaster();
                    saveAsistencia(checkbox);
                }
            });
        });
    </script>



</body>
<!--end::Body-->

</html>