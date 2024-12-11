<?php
  $nombre = $_GET['nombre'];
  $apellido =$_GET['apellido'];
  $telefono = $_GET['telefono'];

  // Mostrar los datos ingresados
  echo "<h2>Datos ingresados:</h2>";
  echo "Nombre: " . $nombre . "<br>";
  echo "Apellido: " . $apellido . "<br>";
  echo "telefono: " . $telefono . "<br>";
?>
<?php
 $servername = "localhost";
 $username = "root";
 $password = "";
        
  try {
    $conn = new PDO("mysql:host=$servername;dbname=", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexion Exitosa, con PDO Orientada a Objetos, extencion de PHP </br> :";
  } catch(PDOException $e) {
     echo "Conexion Fallida, con PDO Orientada a Objetos, extencion de PHP </br> " . $e->getMessage();
    }

 // Configuración de conexión a la base de datos
 $host = 'localhost';
 $dbname = 'tps2_123';
 $usuario = 'root';
 $contraseña = '';

 try {
    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contraseña);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
      die("¡Error en la conexión a la base de datos!: " . $e->getMessage());
   }

 // Función para insertar datos en la base de datos
 function insertarUsuario($pdo, $nombre, $apellido, $telefono) {
    try {
        $sql = "INSERT INTO aprendiz (nombre, apellido, telefono) VALUES (:nombre, :apellido, :telefono)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);

        if ($stmt->execute()) {
            echo "Registro exitoso.";
        } else {
            echo "Error al registrar los datos.";
        }
    } catch (PDOException $e) {
        echo "Error en la inserción de datos: " . $e->getMessage();
    }
 }

 // Bloque de recepción de datos del formulario
 $nombre = $apellido = $telefono = "";
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['telefono'])) {
        // Asignar y limpiar variables
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $apellido = htmlspecialchars(trim($_POST['apellido']));
        $telefono = htmlspecialchars(trim($_POST['telefono']));

        // Llamada a la función de inserción
        insertarUsuario($pdo, $nombre, $apellido, $telefono);
    } else {
        echo "Error: faltan datos obligatorios. Asegúrate de completar todos los campos.";
    }
  }
?>