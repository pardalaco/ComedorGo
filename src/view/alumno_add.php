<?php
require_once "../config/db.php";
require_once "../models/Comensal.php";
require_once "../models/Menu.php";
require_once "../models/Mesa.php";
require_once "../models/Autobus.php";

$activePage = 'alumnos'; // Para resaltar la página activa en el sidebar

// Si envían el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $apellidos = $_POST["apellidos"] ?? '';
    $intolerancias = $_POST["intolerancias"] ?? '';
    $menu_id = $_POST["menu"] ?? '';
    $mesa_id = $_POST["mesa"] ?? '';
    $autobus_id = $_POST["autobus"] ?? '';



    try {
        $comensal = new Comensal(null, $nombre, $apellidos, $intolerancias ?: null, $menu_id ?: null, $mesa_id ?: null, $autobus_id ?: null);
        $comensal->save();
        header("Location: alumnos.php");
        exit();
    } catch (PDOException $e) {
        // Código SQLSTATE 23000 indica violación de restricción (duplicado)
        if ($e->getCode() === '23000') {
            echo "<script>alert('Error: el alumno ya está registrado.');</script>";
        } elseif ($e->getCode() === '22001') {
            // Demasiado largo para el campo
            echo "<script>alert('Error: el campo Intolerancias admite un máximo de 300 caracteres.');</script>";
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

$menus = getAllMenus();
$mesas = getAllMesas();
$autobuses = getAllAutobuses();

?>


<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ComedorGo - Alumnes</title>

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
                            <h3 class="mb-0">Afegir Alumne</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item"><a href="alumnos.php">Alumnes</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Afegir Alumne</li>
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
                        <div class="card-title">Formulari Alumne</div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Form-->
                    <form method="post" action="">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!-- Nombre -->
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required />
                            </div>

                            <!-- Apellidos -->
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Cognom</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required />
                            </div>

                            <!-- Intolerancias -->
                            <div class="mb-3">
                                <label for="intolerancias" class="form-label">Intoleràncies</label>
                                <textarea type="text" class="form-control" id="intolerancias" name="intolerancias"></textarea>
                            </div>

                            <!-- Menú -->
                            <div class="mb-3">
                                <label for="menu" class="form-label">Menú</label>
                                <select class="form-select" id="menu" name="menu">
                                    <option value="">-- Selecciona un menú --</option>
                                    <?php
                                    foreach ($menus as $menu) {
                                    ?>
                                        <option value="<?php echo $menu->getId() ?>">
                                            <?php echo $menu->getNombre(); ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Mesa -->
                            <div class="mb-3">
                                <label for="mesa" class="form-label">Taula</label>
                                <select class="form-select" id="mesa" name="mesa">
                                    <option value="">-- Selecciona una taula --</option>
                                    <?php
                                    foreach ($mesas as $mesa) {
                                    ?>
                                        <option value="<?php echo $mesa->getId() ?>">
                                            <?php echo $mesa->getNombre(); ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Autobus -->
                            <div class="mb-3">
                                <label for="autobus" class="form-label">Autobús</label>
                                <select class="form-select" id="autobus" name="autobus">
                                    <option value="">-- Selecciona un autobús --</option>
                                    <?php
                                    foreach ($autobuses as $autobus) {
                                    ?>
                                        <option value="<?php echo $autobus->getId() ?>">
                                            <?php echo $autobus->getNombre(); ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

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