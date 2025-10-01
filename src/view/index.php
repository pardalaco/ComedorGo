<?php
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../models/DatosDia.php');

$activePage = 'index'; // Para resaltar la página activa en el sidebar

$datosHoy = new DatosDia(date('Y-m-d'));

function getColor($parte, $total)
{

  // Determinar color según porcentaje
  if ($parte == 0) {
    return 'bg-danger';
  } elseif ($parte == $total) {
    return 'bg-success';
  } else {
    return 'bg-warning';
  }
}

?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>ComedorGo - Alumnos</title>

  <?php include __DIR__ . '/components/head.html'; ?>

  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


  <!-- PDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">


    <?php
    //include './components/header.html'; 
    ?>
    <?php include __DIR__ . '/components/header.html'; ?>
    <?php include __DIR__ . '/components/sidebar.php'; ?>


    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6" style="margin-bottom: 15px;">
              <h3 class="mb-0">Dashboard</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </div>

            <!-- begin::Datos generales -->
            <div class="row">
              <div class="col-sm-6" style="margin-bottom: 15px;">
                <h4 class="mb-0">Datos Generales</h4>
              </div>
            </div>


            <!--begin::Row-->
            <div class="row" style="margin-bottom: 15px;">

              <!-- begin::Grafica -->
              <div class="col-md-8">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">Histórico de asistencia</h5>
                  </div>
                  <div class="card-body">
                    <div id="sales-chart"></div>
                  </div>
                </div>
              </div>
              <!-- end::Grafica -->


              <!-- begin::Menus -->
              <div class="col-md-4">

                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Menús</h3>
                  </div>
                  <!-- /.card-header -->

                  <div class="card-body">
                    <canvas id="pieChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- end::Menus -->
              </div>

            </div>
            <!-- end::Row -->
            <!-- end::Datos generales -->

            <!-- Datos a imprimir -->
            <div id="pdf-menus">

              <div class="row mb-3">
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Datos de hoy</h4>
                    <button class="btn btn-primary" onclick="descargarPDF()">Descargar PDF</button>
                  </div>
                </div>
              </div>



              <!--begin::Row-->
              <div class="row">

                <!-- Knob Card -->
                <div class="col-md-4">
                  <div class="card text-center">
                    <div class="card-header">
                      <h3 class="card-title">Asistencia</h3>
                      <?= $datosHoy->getAsistentes() . " de " . $datosHoy->getComensalesTotales() . " comensales" ?>
                    </div>
                    <div class="card-body">
                      <input type="text" class="knob" value="<?= round($datosHoy->getAsistentes() / $datosHoy->getComensalesTotales() * 100); ?>" data-width="120" data-height="120" data-thickness="0.1" data-fgColor="#0d6efd" data-readOnly="true">
                    </div>
                  </div>
                </div>




                <!-- begin::Table Menús-->
                <?php
                $menusNormales = $datosHoy->getMenusNormales();
                $menusRegimenes = $datosHoy->getMenusRegimenes();
                $menusAsistentes = $datosHoy->getAsistentesMenus();
                $index_menus = 1;

                $totalMenusRegimenes = 0;
                foreach ($datosHoy->getMenusRegimenesTotales() as $menu) {
                  $totalMenusRegimenes += $menu;
                }
                $totalAsistentesRegimenes = 0;
                foreach ($menusRegimenes as $menu) {
                  $totalAsistentesRegimenes += $menusAsistentes[$menu->getId()];
                }

                ?>

                <div class="col-md-4">
                  <div class="card ">
                    <div class="card-header">
                      <h3 class="card-title">Menús</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <table class="table table-sm" style="margin-bottom: 20px;">
                        <thead>
                          <tr>
                            <th style="width: 10px">#</th>
                            <th>Tipos de menús</th>
                            <th>Porcentajes</th>
                            <th>Asistentes</th>
                          </tr>
                        </thead>
                        <tbody>

                          <?php
                          foreach ($menusNormales as $menu) {

                            $color = getColor($menusAsistentes[$menu->getId()], $datosHoy->getMenusTotales()[$menu->getId()]);

                          ?>
                            <tr class="align-middle">
                              <td><?= $index_menus++; ?></td>
                              <td><?= $menu->getNombre(); ?></td>
                              <td>
                                <div class="progress progress-xs">
                                  <div class="progress-bar <?= $color ?>"
                                    style="width: <?= $porcentaje; ?>%"
                                    title="<?= $porcentaje; ?>%"
                                    data-bs-toggle="tooltip"></div>
                                </div>
                              </td>
                              <td class="text-center">
                                <span class="badge <?= $color ?>" data-bs-toggle="tooltip">
                                  <?= $menusAsistentes[$menu->getId()] . "/" . $datosHoy->getMenusTotales()[$menu->getId()]; ?>
                                </span>
                              </td>
                            </tr>
                          <?php
                          }
                          ?>
                          <?php
                          // Menús rspeciales

                          $color = getColor($totalAsistentesRegimenes, $totalMenusRegimenes);

                          ?>
                          <tr class="align-middle">
                            <td><?= $index_menus++; ?></td>
                            <td><span class="badge <?= $color ?>" data-bs-toggle="tooltip">Regimenes </span></td>
                            <td>
                              <div class="progress progress-xs">
                                <div class="progress-bar <?= $color ?>"
                                  style="width: <?= $porcentaje; ?>%"
                                  title="<?= $porcentaje; ?>%"
                                  data-bs-toggle="tooltip"></div>
                              </div>
                            </td>
                            <td class="text-center">
                              <span class="badge <?= $color ?>" data-bs-toggle="tooltip">
                                <?= $totalAsistentesRegimenes . "/" . $totalMenusRegimenes ?>
                              </span>
                            </td>
                          </tr>

                        </tbody>
                      </table>
                      <?php $index_menus = 1; ?>
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th style="width: 10px">#</th>
                            <th>Menús Regimenes</th>
                            <th>Porcentajes</th>
                            <th>Asistentes</th>
                          </tr>
                        </thead>
                        <tbody>

                          <?php
                          foreach ($menusRegimenes as $menu) {

                            $color = getColor($menusAsistentes[$menu->getId()], $datosHoy->getMenusTotales()[$menu->getId()]);

                          ?>
                            <tr class="align-middle">
                              <td><?= $index_menus++; ?></td>
                              <td><?= $menu->getNombre(); ?></td>
                              <td>
                                <div class="progress progress-xs">
                                  <div class="progress-bar <?= $color ?>"
                                    style="width: <?= $porcentaje; ?>%"
                                    title="<?= $porcentaje; ?>%"
                                    data-bs-toggle="tooltip"></div>
                                </div>
                              </td>
                              <td class="text-center">
                                <span class="badge <?= $color ?>" data-bs-toggle="tooltip">
                                  <?= $menusAsistentes[$menu->getId()] . "/" . $datosHoy->getMenusTotales()[$menu->getId()]; ?>
                                </span>
                              </td>
                            </tr>
                          <?php
                          }
                          ?>

                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
                </div>
                <!-- /.card -->



                <!-- begin::Table Mesas-->
                <?php
                $mesas = $datosHoy->getMesas();
                $mesasAsistentes = $datosHoy->getAsistentesMesas();
                $index_mesas = 1;
                ?>

                <div class="col-md-4">
                  <div class="card ">
                    <div class="card-header">
                      <h3 class="card-title">Mesas</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th style="width: 10px">#</th>
                            <th>Mesas</th>
                            <th>Porcentajes</th>
                            <th>Asistentes</th>
                          </tr>
                        </thead>
                        <tbody>

                          <?php
                          foreach ($mesas as $menu) {

                            $color = getColor($mesasAsistentes[$menu->getId()], $datosHoy->getMesasTotales()[$menu->getId()]);

                          ?>
                            <tr class="align-middle">
                              <td><?= $index_mesas++; ?></td>
                              <td><?= $menu->getNombre(); ?></td>
                              <td>
                                <div class="progress progress-xs">
                                  <div class="progress-bar <?= $color ?>"
                                    style="width: <?= $porcentaje; ?>%"
                                    title="<?= $porcentaje; ?>%"
                                    data-bs-toggle="tooltip"></div>
                                </div>
                              </td>
                              <td class="text-center">
                                <span class="badge <?= $color ?>" data-bs-toggle="tooltip">
                                  <?= $mesasAsistentes[$menu->getId()] . "/" . $datosHoy->getMesasTotales()[$menu->getId()]; ?>
                                </span>
                              </td>
                            </tr>
                          <?php
                          }
                          ?>

                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
                </div>
                <!-- /.card -->

              </div>
              <!-- end::Datos a imprimir -->

            </div>
            <!-- end::Row -->

            <!-- Datos a imprimir -->
            <div>

              <div class="row mb-3">
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Mesas asistentes</h4>
                    <button class="btn btn-primary" onclick="descargarPDFMesas()">Descargar PDF</button>
                  </div>
                </div>
              </div>



              <!--begin::Row-->
              <div class="row">




                <!-- begin::Table Mesa Alumnos -->
                <?php
                $mesas = $datosHoy->getMesas();
                foreach ($mesas as $mesa) {
                  $asistentesMesa = $datosHoy->getAsistentesMesas()[$mesa->getId()];


                  $color = getColor($asistentesMesa, count($mesa->getComensales()));

                ?>

                  <div class="col-md-6">
                    <div class="card mesa-asistentes">
                      <div class="card-header row">
                        <h3 class="card-title col-11"><?= $mesa->getNombre() ?></h3>
                        <span class="badge <?= $color ?> col-1" data-bs-toggle="tooltip">
                          <?= $asistentesMesa . "/" . count($mesa->getComensales())  ?>
                        </span>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body p-0">
                        <table class="table table-sm table-hover" style="margin-bottom: 20px;">
                          <thead>
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Alumne</th>
                              <th>Menú</th>
                              <th>Autobus</th>
                            </tr>
                          </thead>
                          <tbody>

                            <?php
                            $comensales = $mesa->getComensales();
                            $index_menus = 1;
                            foreach ($comensales as $comensal) {
                              if (in_array($comensal->getId(), $datosHoy->getAsistentesIDs())) {
                            ?>
                                <tr class="align-middle">
                                  <td><?= $index_menus++; ?></td>
                                  <td><?= $comensal->getNombre() . " " . $comensal->getApellidos();  ?></td>
                                  <td><?= $comensal->getMenuName(); ?></td>
                                  <td><?= $comensal->getAutobusName(); ?></td>

                                </tr>
                            <?php
                              }
                            }
                            ?>
                            <?php
                            foreach ($comensales as $comensal) {

                              if (!in_array($comensal->getId(), $datosHoy->getAsistentesIDs())) {
                            ?>
                                <tr class="align-middle table-secondary"> <!-- fila con fondo gris -->
                                  <td><?= $index_menus++; ?></td>
                                  <td><?= $comensal->getNombre() . " " . $comensal->getApellidos();  ?></td>
                                  <td><?= $comensal->getMenuName(); ?></td>
                                  <td><?= $comensal->getAutobusName(); ?></td>
                                </tr>
                            <?php
                              }
                            }
                            ?>


                          </tbody>
                        </table>

                      </div>
                      <!-- /.card-body -->
                    </div>
                  </div>
                  <!-- /.card -->

                <?php
                }
                ?>


              </div>
              <!-- end::Datos a imprimir -->


            </div>
            <!-- end::Row -->

          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content Header-->
      <!--begin::App Content-->
      <div class="app-content">


      </div>
      <!--end::App Content-->
    </main>
    <!--end::App Main-->
    <?php include __DIR__ . '/components/footer.html'; ?>

  </div>
  <!--end::App Wrapper-->
  <?php include __DIR__ . '/components/scripts.html'; ?>


  <!-- Gráfica datos generales -->
  <script>
    // Datos de ejemplo
    document.addEventListener("DOMContentLoaded", function() {

      <?php
      $historial = $datosHoy->getHistorialAsistencias();
      $fechas = array_keys($historial);
      $asistentes = array_values($historial);
      ?>

      const sales_chart_options = {
        series: [{
          name: 'Asistentes',
          data: [<?= implode(", ", $asistentes) ?>],
        }, ],
        chart: {
          height: 300,
          type: 'area',
          toolbar: {
            show: false
          },
        },
        legend: {
          show: true
        },
        colors: ['#0d6efd'],
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          type: 'datetime',
          categories: [
            <?php
            foreach ($fechas as $fecha) {
              echo "'" . date('Y-m-d', strtotime($fecha)) . "', ";
            }
            ?>
          ],
        },
        yaxis: {
          min: 0,
          max: <?= $datosHoy->getComensalesTotales() ?>,
          tickAmount: 5 // opcional, fuerza 5 divisiones
        },
        tooltip: {
          x: {
            format: 'dd/MM/yyyy'
          },
        },
      };

      // Render the chart
      const sales_chart = new ApexCharts(
        document.querySelector('#sales-chart'),
        sales_chart_options
      );
      sales_chart.render();
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Registrar el plugin de datalabels
      Chart.register(ChartDataLabels);

      var pieChartCanvas = document.getElementById('pieChart').getContext('2d');

      var pieData = {
        labels: [
          <?php
          $menus = $datosHoy->getMenusNormales();
          $labels = [];
          $data = [];
          $colors = [];
          $i = 0;

          $colorsPalette = [
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF',
            '#FF9F40',
            '#C9CBCF',
            '#8BC34A',
            '#E91E63',
            '#3F51B5'
          ];

          foreach ($menus as $menu) {
            $labels[] = "'" . $menu->getNombre() . "'";
            $data[] = $datosHoy->getMenusTotales()[$menu->getId()];
            $colors[] = "'" . $colorsPalette[$i++ % count($colorsPalette)] . "'";
          }
          $labels[] = "'Regimenes'";
          $data[] = $totalMenusRegimenes;
          $colors[] = "'" . $colorsPalette[$i++ % count($colorsPalette)] . "'";

          echo implode(", ", $labels);
          ?>
        ],
        datasets: [{
          data: [
            <?php
            echo implode(", ", $data);
            ?>
          ],
          backgroundColor: [
            <?php
            echo implode(", ", $colors);
            ?>
          ],
        }]
      };

      var pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'bottom'
          },
          datalabels: {
            formatter: (value, context) => {
              const label = context.chart.data.labels[context.dataIndex];
              return `${label}: ${value}`;
            },
            color: '#fff',
            font: {
              weight: 'bold'
            },
            padding: 6
          }
        }
      };

      new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions
      });
    });
  </script>

  <!-- Porcentajed de asistencia -->
  <!-- jQuery y jQuery Knob -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-knob/dist/jquery.knob.min.js"></script>
  <script>
    $(function() {
      $(".knob").knob({
        'format': function(value) {
          return value + '%';
        }
      });
    });
  </script>


  <script>
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  </script>

  <script>
    function descargarPDF() {
      const contenedor = document.getElementById('pdf-menus');

      // Seleccionamos el botón dentro del contenedor y lo ocultamos
      const boton = contenedor.querySelector('button');
      if (boton) boton.style.display = 'none';

      html2canvas(contenedor).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const {
          jsPDF
        } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');

        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save('<?= date("Y-m-d") ?>_menus_asistencia.pdf');

        // Volvemos a mostrar el botón
        if (boton) boton.style.display = 'block';
      });
    }
  </script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

  <script>
    function descargarPDFMesas() {
      // 1. Obtén todas las tarjetas de mesa
      const mesas = document.querySelectorAll('.mesa-asistentes');

      if (mesas.length === 0) {
        alert("No hay mesas para descargar.");
        return;
      }

      // 2. Inicializa jsPDF
      const {
        jsPDF
      } = window.jspdf;
      const doc = new jsPDF();
      let firstPage = true;

      // 3. Itera sobre cada mesa
      const promises = Array.from(mesas).map(mesa => {
        return html2canvas(mesa, {
          scale: 2, // Aumenta la escala para mejor calidad de imagen
          logging: true,
          useCORS: true
        }).then(canvas => {
          const imgData = canvas.toDataURL('image/png');
          const imgWidth = 210; // Ancho A4 en mm
          const pageHeight = 295; // Alto A4 en mm
          const imgHeight = (canvas.height * imgWidth) / canvas.width;
          let heightLeft = imgHeight;

          if (!firstPage) {
            doc.addPage();
          }
          doc.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
          heightLeft -= pageHeight;

          while (heightLeft >= 0) {
            doc.addPage();
            doc.addImage(imgData, 'PNG', 0, -(imgHeight - heightLeft), imgWidth, imgHeight);
            heightLeft -= pageHeight;
          }
          firstPage = false;
        });
      });

      // 4. Espera a que todas las conversiones de canvas terminen y guarda el PDF
      Promise.all(promises).then(() => {
        doc.save('mesas_asistentes.pdf');
      }).catch(error => {
        console.error("Error al generar el PDF:", error);
        alert("Hubo un error al generar el PDF.");
      });
    }
  </script>

</body>
<!--end::Body-->

</html>