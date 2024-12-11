<?php
// Configuración de conexión a la base de datos
$host = 'localhost';    
$dbname = 'ips_vacunate_sas'; // nombre de la base de datos
$usuario = 'root';       
$contraseña = '';        

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contraseña);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error en la conexión a la base de datos!: " . $e->getMessage());
}


// Funciones CRUD

// Crear un cliente
function crearCliente($pdo, $nombre, $apellido, $tipoDocumento, $numeroDocumento, $telefono, $fechaNacimiento) {
    try {
        $sql = "INSERT INTO clientes (nombre, apellido, tipoDocumento, numeroDocumento, telefono, fechaNacimiento) 
                VALUES (:nombre, :apellido, :tipoDocumento, :numeroDocumento, :telefono, :fechaNacimiento)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':tipoDocumento', $tipoDocumento);
        $stmt->bindParam(':numeroDocumento', $numeroDocumento);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);
        $stmt->execute();
        echo "Cliente creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear cliente: " . $e->getMessage();
    }
}

// Leer un cliente
function leerCliente($pdo, $nombre) {
    try {
        $sql = "SELECT * FROM clientes WHERE nombre = :nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($clientes) {
            foreach ($clientes as $cliente) {
                echo "ID: " . $cliente['id'] . "<br>";
                echo "Nombre: " . $cliente['nombre'] . "<br>";
                echo "Apellido: " . $cliente['apellido'] . "<br>";
                echo "Tipo de Documento: " . $cliente['tipoDocumento'] . "<br>";
                echo "Número de Documento: " . $cliente['numeroDocumento'] . "<br>";
                echo "Teléfono: " . $cliente['telefono'] . "<br>";
                echo "Fecha de Nacimiento: " . $cliente['fechaNacimiento'] . "<br><br>";
            }
        } else {
            echo "No se encontraron clientes con el nombre proporcionado.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar cliente: " . $e->getMessage();
    }
}

// Actualizar un cliente
function actualizarCliente($pdo, $nombre, $apellido, $telefono) {
    try {
        $sql = "UPDATE clientes SET apellido = :apellido, telefono = :telefono WHERE nombre = :nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);

        if ($stmt->execute()) {
            echo "Cliente actualizado exitosamente.";
        } else {
            echo "Error al actualizar cliente.";
        }
    } catch (PDOException $e) {
        echo "Error al actualizar cliente: " . $e->getMessage();
    }
}

// Eliminar un cliente
function eliminarCliente($pdo, $nombre) {
    try {
        $sql = "DELETE FROM clientes WHERE nombre = :nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);

        if ($stmt->execute()) {
            echo "Cliente eliminado exitosamente.";
        } else {
            echo "Error al eliminar cliente.";
        }
    } catch (PDOException $e) {
        echo "Error al eliminar cliente: " . $e->getMessage();
    }
}

// Manejo de datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $apellido = htmlspecialchars(trim($_POST['apellido'] ?? ''));
    $tipoDocumento = htmlspecialchars(trim($_POST['tipoDocumento'] ?? ''));
    $numeroDocumento = htmlspecialchars(trim($_POST['numeroDocumento'] ?? ''));
    $telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''));
    $fechaNacimiento = htmlspecialchars(trim($_POST['fechaNacimiento'] ?? ''));

    switch ($accion) {
        case 'create':
            if ($nombre && $apellido && $tipoDocumento && $numeroDocumento && $telefono && $fechaNacimiento) {
                crearCliente($pdo, $nombre, $apellido, $tipoDocumento, $numeroDocumento, $telefono, $fechaNacimiento);
            } else {
                echo "Error: todos los campos son obligatorios para crear un cliente.";
            }
            break;

        case 'read':
            if ($nombre) {
                leerCliente($pdo, $nombre);
            } else {
                echo "Error: ingrese un nombre para buscar.";
            }
            break;

        case 'update':
            if ($nombre && ($apellido || $telefono)) {
                actualizarCliente($pdo, $nombre, $apellido, $telefono);
            } else {
                echo "Error: el nombre y al menos un dato adicional son obligatorios para actualizar.";
            }
            break;

        case 'delete':
            if ($nombre) {
                eliminarCliente($pdo, $nombre);
            } else {
                echo "Error: ingrese un nombre para eliminar.";
            }
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>
