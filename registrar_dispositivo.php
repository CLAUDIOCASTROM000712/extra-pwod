<?php
// Encabezado para respuesta en JSON
header('Content-Type: application/json');

// Conexión a MySQL
$host = 'localhost';
$db = 'simulacion'; // 🔁 Cambia por el nombre real de tu base
$user = 'root';
$pass = 'claudiosql'; // 🔁 Cambia si tienes contraseña

$conexion = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

// Obtener JSON desde el cuerpo de la solicitud
$input = file_get_contents('php://input');
$dispositivo = json_decode($input, true);

if (!$dispositivo || !isset($dispositivo['identifica'])) {
    http_response_code(400);
    echo json_encode(["error" => "Formato de datos inválido"]);
    exit;
}

// Extraer datos individuales
$nombre = $dispositivo['identifica']['nombre'];
$ubicacion = $dispositivo['identifica']['ubicacion'];
$jsonDispositivo = json_encode($dispositivo, JSON_UNESCAPED_UNICODE);

// Insertar en la base de datos
$stmt = $conexion->prepare("INSERT INTO dispositivos (nombre, ubicacion, datos_json) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $ubicacion, $jsonDispositivo);

// Ejecutar
if ($stmt->execute()) {
    echo json_encode(["status" => "Dispositivo insertado correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al insertar: " . $stmt->error]);
}

// Cierre
$stmt->close();
$conexion->close();
?>

