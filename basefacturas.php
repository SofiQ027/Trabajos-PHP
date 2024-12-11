<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'ips_vacunate_sas';  //nombre de la base de datos
$usuario = 'root'; 
$contraseña = '';   
try {
    // Establecer la conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contraseña);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error en la conexión a la base de datos!: " . $e->getMessage());
}

// Función para crear una factura
function crearFactura($pdo, $nombreFactura, $nombreCliente, $nombreProveedor, $cantidad, $valor) {
    try {
        // Verificar si ya existe una factura con el mismo nombre
        $sqlVerificar = "SELECT COUNT(*) FROM facturas WHERE nombreFactura = :nombreFactura";
        $stmtVerificar = $pdo->prepare($sqlVerificar);
        $stmtVerificar->bindParam(':nombreFactura', $nombreFactura);
        $stmtVerificar->execute();
        $existe = $stmtVerificar->fetchColumn();

        if ($existe) {
            echo "Error: Ya existe una factura con ese nombre. No se puede crear una nueva.";
            return;
        }

        // Insertar la nueva factura si no existe
        $sql = "INSERT INTO facturas (nombreFactura, nombreCliente, nombreProveedor, cantidad, valor) 
                VALUES (:nombreFactura, :nombreCliente, :nombreProveedor, :cantidad, :valor)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombreFactura', $nombreFactura);
        $stmt->bindParam(':nombreCliente', $nombreCliente);
        $stmt->bindParam(':nombreProveedor', $nombreProveedor);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':valor', $valor);
        $stmt->execute();
        echo "Factura creada exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear factura: " . $e->getMessage();
    }
}

// Función para leer una factura
function leerFactura($pdo, $nombreFactura) {
    try {
        $sql = "SELECT * FROM facturas WHERE nombreFactura LIKE :nombreFactura";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombreFactura', $nombreFactura);
        $stmt->execute();
        $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($facturas) {
            foreach ($facturas as $factura) {
                echo "ID: " . $factura['id'] . "<br>";
                echo "Nombre de la Factura: " . $factura['nombreFactura'] . "<br>";
                echo "Nombre del Cliente: " . $factura['nombreCliente'] . "<br>";
                echo "Nombre del Proveedor: " . $factura['nombreProveedor'] . "<br>";
                echo "Cantidad: " . $factura['cantidad'] . "<br>";
                echo "Valor: " . $factura['valor'] . "<br><br>";
            }
        } else {
            echo "No se encontraron facturas con ese nombre.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar factura: " . $e->getMessage();
    }
}

// Función para actualizar una factura
function actualizarFactura($pdo, $nombreFactura, $nombreCliente, $nombreProveedor, $cantidad, $valor) {
    try {
        $sql = "UPDATE facturas SET nombreCliente = :nombreCliente, 
                nombreProveedor = :nombreProveedor, cantidad = :cantidad, valor = :valor 
                WHERE nombreFactura = :nombreFactura";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombreFactura', $nombreFactura);
        $stmt->bindParam(':nombreCliente', $nombreCliente);
        $stmt->bindParam(':nombreProveedor', $nombreProveedor);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':valor', $valor);
        $stmt->execute();
        echo "Factura actualizada exitosamente.";
    } catch (PDOException $e) {
        echo "Error al actualizar factura: " . $e->getMessage();
    }
}

// Función para eliminar una factura
function eliminarFactura($pdo, $nombreFactura) {
    try {
        $sql = "DELETE FROM facturas WHERE nombreFactura = :nombreFactura";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombreFactura', $nombreFactura);
        $stmt->execute();
        echo "Factura eliminada exitosamente.";
    } catch (PDOException $e) {
        echo "Error al eliminar factura: " . $e->getMessage();
    }
}

// Manejo de la acción del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';
    $nombreFactura = htmlspecialchars(trim($_POST['nombreFactura'] ?? ''));
    $nombreCliente = htmlspecialchars(trim($_POST['nombreCliente'] ?? ''));
    $nombreProveedor = htmlspecialchars(trim($_POST['nombreProveedor'] ?? ''));
    $cantidad = htmlspecialchars(trim($_POST['cantidad'] ?? ''));
    $valor = htmlspecialchars(trim($_POST['valor'] ?? ''));

    switch ($accion) {
        case 'create':
            if ($nombreFactura && $nombreCliente && $nombreProveedor && $cantidad && $valor) {
                crearFactura($pdo, $nombreFactura, $nombreCliente, $nombreProveedor, $cantidad, $valor);
            } else {
                echo "Error: todos los campos son obligatorios para crear una factura.";
            }
            break;

        case 'read':
            if ($nombreFactura) {
                leerFactura($pdo, $nombreFactura);
            } else {
                echo "Error: ingrese el nombre de la factura para buscar.";
            }
            break;

        case 'update':
            if ($nombreFactura && ($nombreCliente || $nombreProveedor || $cantidad || $valor)) {
                actualizarFactura($pdo, $nombreFactura, $nombreCliente, $nombreProveedor, $cantidad, $valor);
            } else {
                echo "Error: ingrese todos los campos para actualizar una factura.";
            }
            break;

        case 'delete':
            if ($nombreFactura) {
                eliminarFactura($pdo, $nombreFactura);
            } else {
                echo "Error: ingrese el nombre de la factura para eliminar.";
            }
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>
