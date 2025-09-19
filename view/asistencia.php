<!-- PAGINA PRINCIPAL -->
<?php
require_once "../config/db.php";
require_once "../src/models/Comensal.php";

$activePage = 'asistencia'; // Para resaltar la página activa en el sidebar

// $dateSelected = date('Y-m-d');
$dateSelected = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');


$comensales = getComensales();
// print_r($comensales);

$asistencias = getAsistenciasFecha($dateSelected);
// echo "<script>console.log('Asistencias para $dateSelected: " . json_encode($asistencias) . "');</script>";
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
                            <h3 class="mb-0">Asistencia</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Asistencia</li>
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
                    <form method="GET" id="formFecha">
                        <input type="date"
                            id="fecha"
                            name="fecha"
                            class="form-control form-control-sm"
                            style="width: 150px; height: 45px;"
                            max="<?= date('Y-m-d') ?>"
                            value="<?= $dateSelected ?>"
                            onchange="document.getElementById('formFecha').submit();">
                    </form>
                </div>




                <!-- begin::Tabla -->
                <?php



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
                                    onchange="toggleAll(this); saveAllAsistencia(this)"
                                    <?php if (count($comensales) == count($asistencias)) echo 'checked'; ?>
                                    <?php if ($dateSelected != date('Y-m-d')) echo 'disabled'; ?> />

                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($comensales as $comensal) { ?>
                            <tr class="align-middle">
                                <td><?= $index_comensales++ ?>.</td>
                                <td><?= $comensal->getNombre() ?></td>
                                <td><?= $comensal->getApellidos() ?></td>
                                <td class="text-center">
                                    <input type="checkbox"
                                        class="form-check-input fila"
                                        onchange="updateMaster(); saveAsistencia(this);"
                                        data-nombre="<?= $comensal->getNombre() ?>"
                                        data-apellidos="<?= $comensal->getApellidos() ?>"
                                        data-comensalID="<?= $comensal->getId() ?>"
                                        <?php if (in_array($comensal->getId(), $asistencias)) echo 'checked'; ?>
                                        <?php if ($dateSelected != date('Y-m-d')) echo 'disabled'; ?> />
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
                const checkbox = this.querySelector('input[type="checkbox"]');
                // Evitar acción si clic en el propio checkbox o si está deshabilitado
                if (e.target.type === 'checkbox' || checkbox.disabled) return;

                checkbox.checked = !checkbox.checked;
                toggleAll(checkbox);
                saveAllAsistencia(checkbox);
            });
        });


        // Evento fila tbody
        document.querySelectorAll('.mi-tabla tbody tr').forEach(row => {
            row.addEventListener('click', function(e) {
                const checkbox = this.querySelector('input[type="checkbox"]');
                // Evitar acción si clic en el propio checkbox o si está deshabilitado
                if (e.target.type === 'checkbox' || checkbox.disabled) return;

                checkbox.checked = !checkbox.checked;
                updateMaster();
                saveAsistencia(checkbox);
            });
        });
    </script>



</body>
<!--end::Body-->

</html>