<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pestañas con Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

    <div class="card">
        <div class="card-header p-2">
            <ul class="nav nav-pills" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="activity-tab" data-bs-toggle="tab" href="#activity" role="tab">Actividad</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="timeline-tab" data-bs-toggle="tab" href="#timeline" role="tab">Timeline</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="settings-tab" data-bs-toggle="tab" href="#settings" role="tab">Ajustes</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Pestaña 1 -->
                <div class="tab-pane fade show active" id="activity" role="tabpanel">
                    <h4>Actividad</h4>
                    <p>Aquí puedes poner la información de la actividad.</p>
                </div>
                <!-- Pestaña 2 -->
                <div class="tab-pane fade" id="timeline" role="tabpanel">
                    <h4>Timeline</h4>
                    <p>Aquí va tu timeline.</p>
                </div>
                <!-- Pestaña 3 -->
                <div class="tab-pane fade" id="settings" role="tabpanel">
                    <h4>Ajustes</h4>
                    <p>Aquí puedes poner un formulario o configuraciones.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>