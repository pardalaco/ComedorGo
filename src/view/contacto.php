<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>MenjadorGo - Llicencia</title>

  <?php include './components/head.html'; ?>


  <!-- ApexCharts -->
  <!-- ApexCharts -->
  <script src="../assets/js/apexcharts.min.js"></script>

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

            <div class="content-wrapper">
              <!-- Capçalera de pàgina -->
              <section class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1>Xarxes i Desenvolupador</h1>
                    </div>
                  </div>
                </div>
              </section>

              <!-- Contingut principal -->
              <section class="content">
                <div class="container-fluid">
                  <div class="card text-center">
                    <div class="card-body">
                      <h2>Hola, sóc <strong>Daniel Rovira</strong></h2>
                      <p>Sóc el desenvolupador d’esta pàgina. Pots seguir-me o contactar amb mi a través dels meus perfils professionals:</p>

                      <a href="https://github.com/pardalaco" target="_blank" class="btn btn-dark m-2">
                        <i class="bi bi-github"></i> GitHub
                      </a>

                      <a href="https://www.linkedin.com/in/daniel-rovira-mart%C3%ADnez/" target="_blank" class="btn btn-primary m-2">
                        <i class="bi bi-linkedin"></i> LinkedIn
                      </a>
                    </div>
                  </div>
                </div>
              </section>
            </div>



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



</body>
<!--end::Body-->

</html>