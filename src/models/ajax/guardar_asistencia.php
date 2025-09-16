<?php
// if (isset($_POST['nombre'], $_POST['apellidos'], $_POST['asistencia'])) {
//     $nombre = $_POST['nombre'];
//     $apellidos = $_POST['apellidos'];
//     $asistencia = $_POST['asistencia']; // "1" o "0"

//     // Aquí guardas en tu base de datos
//     // Ejemplo ficticio
//     // $stmt = $pdo->prepare("INSERT INTO asistencias (nombre, apellidos, asistencia) VALUES (?, ?, ?)");
//     // $stmt->execute([$nombre, $apellidos, $asistencia]);

//     echo "Guardado correctamente: $nombre $apellidos ($asistencia)";
// } else {
//     echo "Error: datos incompletos";
// }

require_once '../Comensal.php';


if (isset($_POST['id'], $_POST['asistencia'])) {
    $id = $_POST['id'];
    $asistencia = $_POST['asistencia']; // "1" o "0"

    // Aquí guardas en tu base de datos
    // Ejemplo ficticio
    // $stmt = $pdo->prepare("INSERT INTO asistencias (nombre, apellidos, asistencia) VALUES (?, ?, ?)");
    // $stmt->execute([$nombre, $apellidos, $asistencia]);

    try {
        guardarAsistencia($id, $asistencia);
    } catch (Exception $e) {
        echo "Error al guardar la asistencia: " . $e->getMessage();
        exit;
    }

    echo "Guardado correctamente: $id ($asistencia)";
    exit;
} elseif (isset($_POST['saveAllAsistencia'])) {
    $asistencia = $_POST['saveAllAsistencia']; // "1" o "0"

    // Aquí guardas en tu base de datos
    // Ejemplo ficticio
    // $stmt = $pdo->prepare("INSERT INTO asistencias (nombre, apellidos, asistencia) VALUES (?, ?, ?)");
    // $stmt->execute([$nombre, $apellidos, $asistencia]);

    try {
        guardarTodosAsistencias($asistencia);
    } catch (Exception $e) {
        echo "Error al guardar la asistencia: " . $e->getMessage();
        exit;
    }

    echo "Guardado correctamente para todos: ($asistencia)";
    exit;
} else {
    echo "Error: datos incompletos";
    exit;
}
