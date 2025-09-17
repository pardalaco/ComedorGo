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


  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
            <div class="col-sm-6" style="margin-bottom: 15px;">
              <h3 class="mb-0">Dashboard</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
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



            <div class="row">
              <div class="col-sm-6" style="margin-bottom: 15px;">
                <h4 class="mb-0">Datos de hoy</h4>
              </div>
            </div>


            <!--begin::Row-->
            <div class="row">

              <!-- Knob Card -->
              <div class="col-md-4">
                <div class="card text-center">
                  <div class="card-header">
                    <h3 class="card-title">Asistencia</h3>
                  </div>
                  <div class="card-body">
                    <input type="text" class="knob" value="60" data-width="120" data-height="120" data-thickness="0.1" data-fgColor="#0d6efd" data-readOnly="true">
                  </div>
                </div>
              </div>


              <!-- begin::Table -->
              <div class="col-md-4">
                <div class="card ">
                  <div class="card-header">
                    <h3 class="card-title">Menús</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body p-0">
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Task</th>
                          <th>Progress</th>
                          <th style="width: 40px">Label</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="align-middle">
                          <td>1.</td>
                          <td>Update software</td>
                          <td>
                            <div class="progress progress-xs">
                              <div
                                class="progress-bar progress-bar-danger"
                                style="width: 55%" data-bs-toggle="tooltip" title="55%"></div>
                            </div>
                          </td>
                          <td><span class="badge text-bg-danger">55%</span></td>
                        </tr>
                        <tr class="align-middle">
                          <td>2.</td>
                          <td>Clean database</td>
                          <td>
                            <div class="progress progress-xs">
                              <div class="progress-bar text-bg-warning" style="width: 70%" data-bs-toggle="tooltip" title="70%"></div>
                            </div>
                          </td>
                          <td>
                            <span class="badge text-bg-warning" data-bs-toggle="tooltip" title="70%">70%</span>
                          </td>
                        </tr>
                        <tr class="align-middle">
                          <td>3.</td>
                          <td>Cron job running</td>
                          <td>
                            <div class="progress progress-xs progress-striped active">
                              <div class="progress-bar text-bg-primary" style="width: 30%;" data-bs-toggle="tooltip" title="30%"></div>
                            </div>
                          </td>

                          <td>
                            <span class="badge text-bg-primary" data-bs-toggle="tooltip" title="60%">30%</span>
                          </td>
                        </tr>
                        <tr class="align-middle">
                          <td>4.</td>
                          <td>Fix and squish bugs</td>
                          <td>
                            <div class="progress progress-xs progress-striped active">
                              <div class="progress-bar text-bg-success" style="width: 90%" data-bs-toggle="tooltip" title="90%"></div>
                            </div>
                          </td>
                          <td>
                            <span class="badge text-bg-success">90%</span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
              </div>
              <!-- /.card -->



              <!-- begin::Table -->
              <div class="col-md-4">
                <div class="card ">
                  <div class="card-header">
                    <h3 class="card-title">Menús</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body p-0">
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Task</th>
                          <th>Progress</th>
                          <th style="width: 40px">Label</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="align-middle">
                          <td>1.</td>
                          <td>Update software</td>
                          <td>
                            <div class="progress progress-xs">
                              <div
                                class="progress-bar progress-bar-danger"
                                style="width: 55%"></div>
                            </div>
                          </td>
                          <td><span class="badge text-bg-danger">55%</span></td>
                        </tr>
                        <tr class="align-middle">
                          <td>2.</td>
                          <td>Clean database</td>
                          <td>
                            <div class="progress progress-xs">
                              <div class="progress-bar text-bg-warning" style="width: 70%"></div>
                            </div>
                          </td>
                          <td>
                            <span class="badge text-bg-warning">70%</span>
                          </td>
                        </tr>
                        <tr class="align-middle">
                          <td>3.</td>
                          <td>Cron job running</td>
                          <td>
                            <div class="progress progress-xs progress-striped active">
                              <div class="progress-bar text-bg-primary" style="width: 30%"></div>
                            </div>
                          </td>
                          <td>
                            <span class="badge text-bg-primary">30%</span>
                          </td>
                        </tr>
                        <tr class="align-middle">
                          <td>4.</td>
                          <td>Fix and squish bugs</td>
                          <td>
                            <div class="progress progress-xs progress-striped active">
                              <div class="progress-bar text-bg-success" style="width: 90%"></div>
                            </div>
                          </td>
                          <td>
                            <span class="badge text-bg-success">90%</span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
              </div>
              <!-- /.card -->

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
    <?php include './components/footer.html'; ?>

  </div>
  <!--end::App Wrapper-->
  <?php include './components/scripts.html'; ?>



  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const sales_chart_options = {
        series: [{
            name: 'Digital Goods',
            data: [28, 48, 40, 19, 86, 27, 90],
          },
          {
            name: 'Electronics',
            data: [65, 59, 80, 81, 56, 55, 40],
          },
        ],
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
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          type: 'datetime',
          categories: [
            '2023-01-01',
            '2023-02-01',
            '2023-03-01',
            '2023-04-01',
            '2023-05-01',
            '2023-06-01',
            '2023-07-01',
          ],
        },
        tooltip: {
          x: {
            format: 'MMMM yyyy'
          },
        },
      };

      const sales_chart = new ApexCharts(
        document.querySelector('#sales-chart'),
        sales_chart_options
      );
      sales_chart.render();
    });
  </script>


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var pieChartCanvas = document.getElementById('pieChart').getContext('2d');

      var pieData = {
        labels: ['Chrome', 'Edge', 'Firefox', 'Safari', 'Opera', 'IE'],
        datasets: [{
          data: [700, 500, 400, 600, 300, 100],
          backgroundColor: [
            '#0d6efd', // azul
            '#20c997', // verde
            '#ffc107', // amarillo
            '#d63384', // rosa
            '#6f42c1', // morado
            '#adb5bd' // gris
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

</body>
<!--end::Body-->

</html>