<?php
require_once "../config/db.php";
require_once "../models/Menu.php";

$activePage = 'menus'; // Para resaltar la página activa en el sidebar

if (isset($_GET['menuid'])) {
    $menuid = $_GET['menuid'];
    $MenuId = intval($menuid);
}

$menu = getMenuById($MenuId);

// Si envían el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_POST['accion'] === 'modificar') {
        $nombre = $_POST["nombre"] ?? '';
        $descripcion = $_POST["descripcion"] ?? '';
        $especial = isset($_POST["especial"]) ? 1 : 0;

        try {
            $menu->setNombre($nombre);
            $menu->setDescripcion($descripcion);
            $menu->setEspecial($especial);
            $menu->save();
            header("Location: menus.php");
            exit();
        } catch (PDOException $e) {
            // Código SQLSTATE 23000 indica violación de restricción (duplicado)
            if ($e->getCode() === '23000') {
                echo "<script>alert('Error: la menu ya está registrado.');</script>";
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
    } elseif ($_POST['accion'] === 'eliminar') {
        // Código para eliminar el alumno
        $menu->delete(); // Asumiendo que tienes un método delete() en tu modelo
        header("Location: menus.php");
        exit();
    }
}
?>


<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ComedorGo - Menus</title>

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
                            <h3 class="mb-0">Modificar Menú</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item"><a href="menus.php">Menús</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Modificar Menú</li>
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




                <!--begin::Quick Example-->
                <div class="card card-primary card-outline mb-4">
                    <!--begin::Header-->
                    <div class="card-header">
                        <div class="card-title">Formulario Menu</div>
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
                                    value="<?= $menu->getNombre() ?>"
                                    required />
                            </div>

                            <!-- Apellidos -->
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea type="text"
                                    class="form-control"
                                    id="descripcion"
                                    name="descripcion"><?= $menu->getDescripcion() ?></textarea>
                            </div>

                            <!-- Especial -->
                            <div class="mb-3">
                                <label for="especial" class="form-label">Especial</label>
                                <!-- Checkbox -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="especial" name="especial" <?php if ($menu->isEspecial()) echo 'checked'; ?>>
                                    <label class="form-check-label" for="especial">
                                        Marcar si es un menú especial
                                    </label>
                                </div>
                            </div>

                            <!--begin::Footer-->
                            <div class="card-footer">
                                <!-- Botón modificar -->
                                <button type="submit" name="accion" value="modificar" class="btn btn-primary" style="margin-right: 10px">
                                    Modificar
                                </button>

                                <!-- Botón eliminar con confirmación -->
                                <button type="submit" name="accion" value="eliminar" class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de que quieres eliminar este alumno?');">
                                    Eliminar
                                </button>
                            </div>
                            <!--end::Footer-->

                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Quick Example-->


            </div>
            <!--end::App Content-->

            <?php
            // echo "<pre>";
            // print_r($menu);
            // echo "</pre>";
            ?>

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