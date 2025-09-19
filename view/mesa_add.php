<!-- PAGINA PRINCIPAL -->
<?php
require_once "../config/db.php";
require_once "../src/models/Mesa.php";

$activePage = 'mesas'; // Para resaltar la página activa en el sidebar

// Si envían el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $descripcion = $_POST["descripcion"] ?? '';

    try {
        $mesa = new Mesa(null, $nombre, $descripcion);
        $mesa->save();
        header("Location: mesas.php");
        exit();
    } catch (PDOException $e) {
        // Código SQLSTATE 23000 indica violación de restricción (duplicado)
        if ($e->getCode() === '23000') {
            echo "<script>alert('Error: la mesa ya está registrado.');</script>";
        } else {
            $mensaje = addslashes($e->getMessage());
            echo "<script>alert('Error al guardar la mesa: $mensaje');</script>";
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
                            <h3 class="mb-0">Añadir Mesa</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item"><a href="mesas.php">Mesas</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Añadir Mesa</li>
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
                        <div class="card-title">Formulario Mesa</div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Form-->
                    <form method="post" action="">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!-- Nombre -->
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required />
                            </div>

                            <!-- Apellidos -->
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea type="text" class="form-control" id="descripcion" name="descripcion"></textarea>
                            </div>

                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Enviar</button>
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



</body>
<!--end::Body-->

</html>