<!-- PAGINA PRINCIPAL -->
<?php
require_once "../config/db.php";
require_once "../src/Models/Comensal.php";
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
              <h3 class="mb-0">Dashboard</h3>
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

        <!-- Botones arriba de la tabla -->
        <div class="d-flex justify-content-end mb-2">

          <button class="btn btn-primary" onclick="location.href='add_alumno.php'">
            Añadir alumno
          </button>
        </div>
        <!-- ./Botones arriba de la tabla -->


        <!-- begin::Tabla -->
        <?php

        $comensales = getComensales();
        // print_r($comensales);

        $index_comensales = 1;
        ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Nombre</th>
              <th>Apellidos</th>
              <th>Menú</th>
              <th>Mesa</th>
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
                <td></td>
                <td></td>
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