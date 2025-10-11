<?php
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../models/DatosDia.php');

$activePage = 'index'; // Para resaltar la página activa en el sidebar

$data = date('Y-m-d');
$dataReverse = date('d-m-Y');

$datosHoy = new DatosDia($data);

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

function getPorcentaje($parte, $total)
{
  if ($total == 0) return 100;
  return round(($parte / $total) * 100, 2);
}

?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>MenjadorGo</title>

  <?php include __DIR__ . '/components/head.html'; ?>

  <!-- ApexCharts -->
  <script src="../assets/js/apexcharts.min.js"></script>

  <!-- PDF -->
  <script src="../assets/js/jspdf.umd.min.js"></script>
  <script src="../assets/js/html2canvas.min.js"></script>


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

            <!-- Datos generales -->
            <div>


              <!-- begin::Datos generales -->
              <div class="row">
                <div class="col-sm-6" style="margin-bottom: 15px;">
                  <h4 class="mb-0">Dades Generals</h4>
                </div>
              </div>


              <!--begin::Row-->
              <div class="row" style="margin-bottom: 15px;">

                <!-- begin::Grafica -->
                <div class="col-md-8">
                  <div class="card">
                    <div class="card-header">
                      <h5 class="card-title">Històric d'asistencia</h5>
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

            </div>


            <!-- Datos hoy -->
            <!-- Datos a imprimir -->
            <div id="pdf-menus">

              <div class="row mb-3">
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Dades - <?= $dataReverse ?></h4>
                    <button class="btn btn-primary" onclick="descargarPDF()">Descarrega PDF</button>
                  </div>
                </div>
              </div>



              <!--begin::Row-->
              <div class="row">

                <!-- Knob Card -->
                <div class="col-md-4">
                  <div class="card text-center">
                    <div class="card-header">
                      <h3 class="card-title">Assistència</h3>
                      <?= $datosHoy->getAsistentes() . " de " . $datosHoy->getComensalesTotales() . " comensales" ?>
                    </div>
                    <div class="card-body">
                      <?php
                      $porcentajeAsistencia = getPorcentaje($datosHoy->getAsistentes(), $datosHoy->getComensalesTotales());
                      ?>
                      <input type="text" class="knob" value="<?= $porcentajeAsistencia; ?>" data-width="120" data-height="120" data-thickness="0.1" data-fgColor="#0d6efd" data-readOnly="true">
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
                            <th>Tipus de menús</th>
                            <th>Percentatge</th>
                            <th>Assistència</th>
                          </tr>
                        </thead>
                        <tbody>

                          <?php
                          foreach ($menusNormales as $menu) {

                            $color = getColor($menusAsistentes[$menu->getId()], $datosHoy->getMenusTotales()[$menu->getId()]);
                            $porcentaje = getPorcentaje($menusAsistentes[$menu->getId()], $datosHoy->getMenusTotales()[$menu->getId()]);
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
                          $porcentaje = getPorcentaje($totalAsistentesRegimenes, $totalMenusRegimenes);
                          ?>
                          <tr class="align-middle">
                            <td><?= $index_menus++; ?></td>
                            <td><span class="badge <?= $color ?>" data-bs-toggle="tooltip">Règims </span></td>
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
                            <th>Menús Règims</th>
                            <th>Percentatge</th>
                            <th>Assistents</th>
                          </tr>
                        </thead>
                        <tbody>

                          <?php
                          foreach ($menusRegimenes as $menu) {

                            $color = getColor($menusAsistentes[$menu->getId()], $datosHoy->getMenusTotales()[$menu->getId()]);
                            $porcentaje = getPorcentaje($menusAsistentes[$menu->getId()], $datosHoy->getMenusTotales()[$menu->getId()]);
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
                      <h3 class="card-title">Taules</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th style="width: 10px">#</th>
                            <th>Taula</th>
                            <th>Percentatge</th>
                            <th>Assistents</th>
                          </tr>
                        </thead>
                        <tbody>

                          <?php
                          foreach ($mesas as $mesa) {
                            $asistentesMesa = $datosHoy->getMesasTotales()[$mesa->getId()] ?? 0;

                            $color = getColor($mesasAsistentes[$mesa->getId()], $asistentesMesa);
                            $porcentaje = getPorcentaje($mesasAsistentes[$mesa->getId()], $asistentesMesa);

                          ?>
                            <tr class="align-middle">
                              <td><?= $index_mesas++; ?></td>
                              <td><?= $mesa->getNombre(); ?></td>
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
                                  <?php
                                  echo $mesasAsistentes[$mesa->getId()] . "/" . $asistentesMesa; ?>
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
            <!-- ./Datos hoy -->

            <!-- Mesas Asistentes -->
            <div>

              <div class="row mb-3">
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Taules assistents - <?= $dataReverse ?></h4>
                    <button class="btn btn-primary" onclick="descargarPDFMesas()">Descarrega PDF</button>
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

                  <div class="col-xl-6" style="margin-bottom: 10px;">
                    <div class="card mesa-asistentes">
                      <div class="card-header">
                        <div class="row">
                          <h3 class="card-title col-11"><?= $mesa->getNombre() ?></h3>
                          <span class="badge <?= $color ?> col-1" data-bs-toggle="tooltip">
                            <?= $asistentesMesa . "/" . count($mesa->getComensales())  ?>
                          </span>
                        </div>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body p-0">
                        <table class="table table-sm table-hover" style="margin-bottom: 20px;">
                          <thead>
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Alumne</th>
                              <th>Menú</th>
                              <th>Intoleràncies</th>
                              <th>Autobús</th>
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
                                  <td><?= $comensal->getIntolerancias(); ?></td>
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
                                  <td><?= $comensal->getIntolerancias(); ?></td>
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
            <!-- Mesas asistentes -->



            <!-- Mesas Cocinero -->
            <div id="pdf-mesasCocinero">


              <div class="row mb-3">
                <div class="col-12">
                  <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Taules Cuiner - <?= $dataReverse ?></h4>
                    <button class="btn btn-primary" onclick="descargarPDFMesasCocinero()">Descarrega PDF</button>
                  </div>
                </div>
              </div>


              <div class="card">

                <div class="card-body p-0">

                  <table class="table table-sm table-hover">
                    <thead>
                      <tr>
                        <th style="min-width:100px;">Taula</th>
                        <th style="min-width:50px;">Normals</th>
                        <th style="min-width:50px;">Especials</th>
                        <th style="min-width:50px;">Règim</th>
                        <th style="min-width:150px;">Recompte de règim</th>
                        <th style="min-width:200px;">Alumnes</th>
                        <th style="min-width:150px;">Menús</th>
                        <th>Intoleràncies</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $mesas = $datosHoy->getMesas();

                      $totalNormal = 0;
                      $totalEspeciales = 0;
                      $totalRegimen = 0;
                      $totalMenusRegimenMesclat = 0;
                      $totalMenusRegimenTrituratPoc = 0;
                      $totalMenusRegimenTrituratMolt = 0;

                      foreach ($mesas as $mesa) {
                        $rowspan = count($mesa->getComensalesAsistenciaHoy()) ? count($mesa->getComensalesAsistenciaHoy()) + 1 : 1;
                        $totalNormal += count($mesa->getComensalesMenusNormaeles());
                        $totalEspeciales += count($mesa->getComensalesMenusEspeciales());
                        $totalRegimen += count($mesa->getComensalesMenusRegimen());
                      ?>
                        <!-- Fila principal de la mesa -->
                        <tr>
                          <td rowspan="<?= $rowspan ?>"><?= $mesa->getNombre() ?></td>
                          <td rowspan="<?= $rowspan ?>"><?= count($mesa->getComensalesMenusNormaeles()) ?></td>
                          <td rowspan="<?= $rowspan ?>"><?= count($mesa->getComensalesMenusEspeciales()) ?></td>
                          <td rowspan="<?= $rowspan ?>"><?= count($mesa->getComensalesMenusRegimen()) ?></td>
                          <td rowspan="<?= $rowspan ?>">
                            <!-- Aquí no metemos más <tr>, solo mostramos recuentos -->
                            <?php if (count($mesa->getComensalesMenusRegimenMesclat()) > 0) {
                              $totalMenusRegimenMesclat += count($mesa->getComensalesMenusRegimenMesclat());
                            ?>
                              Mesclat: <?= count($mesa->getComensalesMenusRegimenMesclat()) ?><br>
                            <?php } ?>
                            <?php if (count($mesa->getComensalesMenusRegimenTrituratPoc()) > 0) {
                              $totalMenusRegimenTrituratPoc += count($mesa->getComensalesMenusRegimenTrituratPoc());
                            ?>
                              Triturat Poc: <?= count($mesa->getComensalesMenusRegimenTrituratPoc()) ?><br>
                            <?php } ?>
                            <?php if (count($mesa->getComensalesMenusRegimenTrituratMolt()) > 0) {
                              $totalMenusRegimenTrituratMolt += count($mesa->getComensalesMenusRegimenTrituratMolt());
                            ?>
                              Triturat Molt: <?= count($mesa->getComensalesMenusRegimenTrituratMolt()) ?><br>
                            <?php } ?>
                          </td>

                          <?php
                          // Si no hay comensales hoy, añadimos una fila vacía para mantener la estructura
                          if (count($mesa->getComensalesAsistenciaHoy()) == 0) {
                          ?>
                            <td colspan="4"></td>
                          <?php
                          }
                          ?>
                        </tr>

                        <!-- Filas de los comensales -->
                        <?php foreach ($mesa->getComensalesAsistenciaHoy() as $comensal): ?>
                          <tr>
                            <td><?= $comensal->getNombre() . " " . $comensal->getApellidos() ?></td>
                            <td><?= $comensal->getMenuName() ?></td>
                            <td><?= $comensal->getIntolerancias() ?></td>
                          </tr>
                        <?php endforeach; ?>

                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <th>Total</th>
                      <th><?= $totalNormal ?></th>
                      <th><?= $totalEspeciales ?></th>
                      <th><?= $totalRegimen ?></th>
                      <td>
                        <!-- Aquí no metemos más <tr>, solo mostramos recuentos -->
                        <?php if ($totalMenusRegimenMesclat > 0) { ?>
                          Mesclat: <?= $totalMenusRegimenMesclat ?><br>
                        <?php } ?>
                        <?php if ($totalMenusRegimenTrituratPoc > 0) { ?>
                          Triturat Poc: <?= $totalMenusRegimenTrituratPoc ?><br>
                        <?php } ?>
                        <?php if ($totalMenusRegimenTrituratMolt > 0) { ?>
                          Triturat Molt: <?= $totalMenusRegimenTrituratMolt ?><br>
                        <?php } ?>
                      </td>
                      <th></th>
                      <th></th>
                      <th></th>
                    </tfoot>
                  </table>

                </div>

              </div>

            </div>
            <!-- Mesas Cocinero -->

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


  <!-- Grafico de tarta -->
  <script src="../assets/js/chart.umd.min.js"></script>
  <script src="../assets/js/chartjs-plugin-datalabels.min.js"></script>
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
          $datas = [];
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
            $datas[] = $datosHoy->getMenusTotales()[$menu->getId()];
            $colors[] = "'" . $colorsPalette[$i++ % count($colorsPalette)] . "'";
          }
          $labels[] = "'Regimenes'";
          $datas[] = $totalMenusRegimenes;
          $colors[] = "'" . $colorsPalette[$i++ % count($colorsPalette)] . "'";

          echo implode(", ", $labels);
          ?>
        ],
        datasets: [{
          data: [
            <?php
            echo implode(", ", $datas);
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
  <script src="../assets/js/jquery-3.6.0.min.js"></script>
  <script src="../assets/js/jquery.knob.min.js"></script>
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

  <!-- Descargar datos hoy -->
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
        const pdf = new jsPDF('l', 'mm', 'a4');

        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save('<?= $data; ?>_menus_asistencia.pdf');

        // Volvemos a mostrar el botón
        if (boton) boton.style.display = 'block';
      });
    }
  </script>

  <!-- Descargar Mesas Cocinero -->
  <script>
    function descargarPDFMesasCocinero() {
      const contenedor = document.getElementById('pdf-mesasCocinero');
      const boton = contenedor.querySelector('button');
      if (boton) boton.style.display = 'none';

      html2canvas(contenedor, {
        scale: 2
      }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const {
          jsPDF
        } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');

        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = pdf.internal.pageSize.getHeight();
        const imgProps = pdf.getImageProperties(imgData);
        const imgWidth = pdfWidth;
        const imgHeight = (imgProps.height * pdfWidth) / imgProps.width;

        let heightLeft = imgHeight;
        let position = 0;

        // Añadimos la primera página
        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pdfHeight;

        // Mientras quede altura, añadimos páginas nuevas
        while (heightLeft > 0) {
          position = heightLeft - imgHeight;
          pdf.addPage();
          pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
          heightLeft -= pdfHeight;
        }

        pdf.save('<?= $data; ?>_taules_cuiner.pdf');

        if (boton) boton.style.display = 'block';
      });
    }
  </script>

  <!-- Descargar tablas -->
  <script src="../assets/js/jspdf.umd.min.js"></script>
  <script src="../assets/js/html2canvas.min.js"></script>


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
        doc.save('<?= $data; ?>_taules_asistents.pdf');
      }).catch(error => {
        console.error("Error al generar el PDF:", error);
        alert("Hubo un error al generar el PDF.");
      });
    }
  </script>

</body>
<!--end::Body-->

</html>