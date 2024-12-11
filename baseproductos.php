<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'ips_vacunate_sas';
$usuario = 'root'; 
$contraseña = '';  

try {
    // Establecer la conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contraseña);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error en la conexión a la base de datos!: " . $e->getMessage());
}

// Función para crear un producto
function crearProducto($pdo, $nombre_producto, $lote, $valor) {
    try {
        $sql = "INSERT INTO productos (nombre_producto, lote, valor) VALUES (:nombre_producto, :lote, :valor)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':lote', $lote);
        $stmt->bindParam(':valor', $valor);
        $stmt->execute();
        echo "Producto creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear producto: " . $e->getMessage();
    }
}

// Función para leer productos
function leerProducto($pdo, $nombre_producto) {
    try {
        $sql = "SELECT * FROM productos WHERE nombre_producto LIKE :nombre_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($productos) {
            foreach ($productos as $producto) {
                echo "ID: " . $producto['id'] . "<br>";
                echo "Nombre del Producto: " . $producto['nombre_producto'] . "<br>";
                echo "Lote: " . $producto['lote'] . "<br>";
                echo "Valor: " . $producto['valor'] . "<br><br>";
            }
        } else {
            echo "No se encontraron productos con el nombre proporcionado.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar producto: " . $e->getMessage();
    }
}

// Función para actualizar un producto
function actualizarProducto($pdo, $nombre_producto, $lote, $valor) {
    try {
        $sql = "UPDATE productos SET lote = :lote, valor = :valor WHERE nombre_producto = :nombre_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':lote', $lote);
        $stmt->bindParam(':valor', $valor);

        if ($stmt->execute()) {
            echo "Producto actualizado exitosamente.";
        } else {
            echo "Error al actualizar producto.";
        }
    } catch (PDOException $e) {
        echo "Error al actualizar producto: " . $e->getMessage();
    }
}

// Función para eliminar un producto
function eliminarProducto($pdo, $nombre_producto) {
    try {
        $sql = "DELETE FROM productos WHERE nombre_producto = :nombre_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_producto', $nombre_producto);

        if ($stmt->execute()) {
            echo "Producto eliminado exitosamente.";
        } else {
            echo "Error al eliminar producto.";
        }
    } catch (PDOException $e) {
        echo "Error al eliminar producto: " . $e->getMessage();
    }
}

// Manejo de la acción desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';
    $nombre_producto = htmlspecialchars(trim($_POST['nombre_producto'] ?? ''));
    $lote = htmlspecialchars(trim($_POST['lote'] ?? ''));
    $valor = htmlspecialchars(trim($_POST['valor'] ?? ''));

    switch ($accion) {
        case 'create':
            if ($nombre_producto && $lote && $valor) {
                crearProducto($pdo, $nombre_producto, $lote, $valor);
            } else {
                echo "Error: todos los campos son obligatorios para crear un producto.";
            }
            break;

        case 'read':
            if ($nombre_producto) {
                leerProducto($pdo, $nombre_producto);
            } else {
                echo "Error: ingrese el nombre del producto para buscar.";
            }
            break;

        case 'update':
            if ($nombre_producto && ($lote || $valor)) {
                actualizarProducto($pdo, $nombre_producto, $lote, $valor);
            } else {
                echo "Error: ingrese el nombre del producto y al menos un dato adicional (lote o valor) para actualizar.";
            }
            break;

        case 'delete':
            if ($nombre_producto) {
                eliminarProducto($pdo, $nombre_producto);
            } else {
                echo "Error: ingrese el nombre del producto para eliminar.";
            }
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>
