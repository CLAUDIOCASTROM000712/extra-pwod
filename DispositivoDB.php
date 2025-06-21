<?php
// Conexión a MySQL
$host = 'localhost';
$db = 'simulacion';
$user = 'root';
$pass = 'claudiosql'; // o tu contraseña

$conexion = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Objeto complejo en PHP
$dispositivo = [
    "identifica" => [
        "identificador" => 1,
        "nombre" => "Represa Uno",
        "ubicacion" => "En la compuerta de la Represa Uno",
        "coordenadas" => "19.7060° N, 101.1950° W",
        "idestatus" => 1,
        "estatus" => "Operacion Normal",
        "potencia" => [ "nominal" => 7.4, "minimo" => 6.2, "maximo" => 8.6, "um" => "KW" ],
        "voltaje" => [ "nominal" => 240, "minimo" => 230, "maximo" => 250, "um" => "Volts" ],
        "corriente" => [ "nominal" => 30, "minimo" => 25, "maximo" => 35, "um" => "Amperes" ],
        "caudal" => [ "nominal" => 1, "minimo" => 0.10, "maximo" => 1.20, "um" => "m3/minuto" ],
        "fechaRegistro" => gmdate("D, d M Y H:i:s T")
    ],
    "opera" => [
        "voltaje" => 240,
        "corriente" => 30,
        "caudal" => 1,
        "estatus" => 1
    ],
    "estado" => 1
];

// Convertir a JSON
$jsonDispositivo = json_encode($dispositivo, JSON_UNESCAPED_UNICODE);

// Datos simples
$nombre = $dispositivo['identifica']['nombre'];
$ubicacion = $dispositivo['identifica']['ubicacion'];

// Preparar la consulta
$stmt = $conexion->prepare("INSERT INTO dispositivos (nombre, ubicacion, datos_json) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $ubicacion, $jsonDispositivo);

// Ejecutar
if ($stmt->execute()) {
    echo "Dispositivo insertado correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar conexión
$stmt->close();
$conexion->close();
?>

