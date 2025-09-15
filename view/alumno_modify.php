<!-- PAGINA PRINCIPAL -->
<?php
require_once "../config/db.php";
require_once "../src/models/Comensal.php";


if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];
    $UserId = intval($userid);
}

$comensal = getComensalById($UserId);

// Si envían el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $apellidos = $_POST["apellidos"] ?? '';
    $menu_id = $_POST["menu"] ?? '';
    $mesa_id = $_POST["mesa"] ?? '';

    try {
        $comensal->setNombre($nombre);
        $comensal->setApellidos($apellidos);
        $comensal->setMenuId($menu_id ?: null);
        $comensal->setMesaId($mesa_id ?: null);
        $comensal->save();



        header("Location: alumnos.php");
        exit();
    } catch (PDOException $e) {
        // Código SQLSTATE 23000 indica violación de restricción (duplicado)
        if ($e->getCode() === '23000') {
            echo "<script>alert('Error: el alumno ya está registrado.');</script>";
        } else {
            $mensaje = addslashes($e->getMessage());
            echo "<script>alert('Error al guardar el comensal: $mensaje');</script>";
        }
        // Volver a la página anterior
        echo "<script>window.history.back();</script>";
        exit();
    } catch (Exception $e) {
        $mensaje = addslashes($e->getMessage());
        echo "<script>alert('Error inesperado: $mensaje');</script>";
        echo "<script>window.history.back();</script>";
        exit();
    }
}
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
                            <h3 class="mb-0">Añadir Alumno</h3>
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



                <?php
                // Supongamos que tienes las opciones de menú y mesa en arrays.
                // En un caso real podrías obtenerlos de la base de datos.
                $menus = [
                    1 => "Vegetariano",
                    2 => "Normal",
                    3 => "Sin gluten",
                    4 => "Infantil"
                ];

                $mesas = [
                    1 => "Mesa 1",
                    2 => "Mesa 2",
                    3 => "Mesa 3",
                    4 => "Mesa 4"
                ];

                ?>

                <!--begin::Quick Example-->
                <div class="card card-primary card-outline mb-4">
                    <!--begin::Header-->
                    <div class="card-header">
                        <div class="card-title">Formulario Alumno</div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Form-->
                    <form method="post" action="">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!-- Nombre -->
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text"
                                    class="form-control"
                                    id="nombre"
                                    name="nombre"
                                    value="<?= $comensal->getNombre() ?>"
                                    required />
                            </div>

                            <!-- Apellidos -->
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text"
                                    class="form-control"
                                    id="apellidos"
                                    name="apellidos"
                                    value="<?= $comensal->getApellidos() ?>"
                                    required />
                            </div>

                            <!-- Menú -->
                            <div class="mb-3">
                                <label for="menu" class="form-label">Menú</label>
                                <select class="form-select" id="menu" name="menu">
                                    <option value="">-- Selecciona un menú --</option>
                                    <option value="1">Vegetariano</option>
                                    <option value="2">Normal</option>
                                    <option value="3">Sin gluten</option>
                                    <option value="4">Infantil</option>
                                </select>
                            </div>

                            <!-- Mesa -->
                            <div class="mb-3">
                                <label for="mesa" class="form-label">Mesa</label>
                                <select class="form-select" id="mesa" name="mesa">
                                    <option value="">-- Selecciona una mesa --</option>
                                    <option value="1">Mesa 1</option>
                                    <option value="2">Mesa 2</option>
                                    <option value="3">Mesa 3</option>
                                    <option value="4">Mesa 4</option>
                                </select>
                            </div>
                        </div>
                        <!--end::Body-->
                        <!--begin::Footer-->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Modificar</button>
                        </div>
                        <!--end::Footer-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Quick Example-->


            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <?php include './components/footer.html'; ?>

    </div>
    <!--end::App Wrapper-->
    <?php include './components/scripts.html'; ?>

    <script>
        function printTable() {
            // obtenemos el contenido de la tabla
            var tabla = document.getElementById("tabla-comensales").outerHTML;

            // abrimos una nueva ventana solo con la tabla
            var ventana = window.open("", "", "width=800,height=600");
            ventana.document.write(`
      <html>
        <head>
          <title>Imprimir tabla</title>
          <style>
            table {
              border-collapse: collapse;
              width: 100%;
            }
            table, th, td {
              border: 1px solid black;
              padding: 8px;
            }
          </style>
        </head>
        <body>
          ${tabla}
        </body>
      </html>
    `);
            ventana.document.close();
            ventana.print();
        }
    </script>


</body>
<!--end::Body-->

</html>