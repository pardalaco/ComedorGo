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

    <!-- Tabla -->
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">


</head>
<!--end::Head-->

<!-- jQuery (AdminLTE ya lo usa, pero si no lo tienes, añádelo) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>


<!-- Imprimir tabla -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>



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

                <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
                    <!-- Botón a la izquierda -->
                    <button type="button" class="btn btn-primary" onclick="descargarPDF()">Descargar PDF</button>

                    <!-- Selector de fecha a la derecha -->
                    <div class="d-flex align-items-center gap-2">
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
                </div>


                <!-- begin::Tabla -->
                <div class="card">
                    <div class="card-body">


                        <?php
                        $index_comensales = 1;
                        ?>
                        <table class="table table-bordered table-hover mi-tabla" id="mi-tabla">
                            <thead>
                                <tr>
                                    <!-- <th style="width: 10px">#</th> -->
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Autobus</th>
                                    <th class="text-center" id="checkboxAll">
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
                                        <!-- <td><?= $index_comensales++ ?>.</td> -->
                                        <td><?= $comensal->getNombre() ?></td>
                                        <td><?= $comensal->getApellidos() ?></td>
                                        <td><?= $comensal->getAutobusName() ?></td>
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
                    </div>
                </div>

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
        // document.querySelectorAll('.mi-tabla thead tr').forEach(row => {
        //     row.addEventListener('click', function(e) {
        //         const checkbox = this.querySelector('input[type="checkbox"]');
        //         // Evitar acción si clic en el propio checkbox o si está deshabilitado
        //         if (e.target.type === 'checkbox' || checkbox.disabled) return;

        //         checkbox.checked = !checkbox.checked;
        //         toggleAll(checkbox);
        //         saveAllAsistencia(checkbox);
        //     });
        // });
        const thCheckbox = document.getElementById('checkboxAll');

        thCheckbox.addEventListener('click', function(e) {
            const checkbox = this.querySelector('input[type="checkbox"]');
            if (!checkbox || checkbox.disabled) return;

            // Evitar cambiar si el clic es directamente sobre el input (para que el checkbox funcione normal)
            if (e.target === checkbox) return;

            checkbox.checked = !checkbox.checked;
            toggleAll(checkbox);
            saveAllAsistencia(checkbox);
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

    <script>
        $(document).ready(function() {
            $('#mi-tabla').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                }
            });
        });
    </script>

    <!-- Imprimir Tabla -->
    <script>
        async function descargarPDF() {
            const {
                jsPDF
            } = window.jspdf;

            const tabla = document.getElementById("mi-tabla");

            const canvas = await html2canvas(tabla);
            const imgData = canvas.toDataURL("image/png");

            const pdf = new jsPDF("p", "mm", "a4");
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
            pdf.save("tabla.pdf");
        }
    </script>


</body>
<!--end::Body-->

</html>