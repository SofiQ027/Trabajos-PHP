<?php 

$nombre = $_GET['name'];
$apellido =$_GET['apellido'];
$age =$_GET['age'];
$document =$_GET['document'];
$genero =$_GET['genero'];
$color =$_GET['color'];
$mecato = isset($_POST['mecato']) ? $_POST['mecato'] : [];$comentario= $_GET['comentario'];
$telefono= $_GET['telefono'];

echo "<p>Su nombre es: $nombre</p>";
echo "<p>Su apellido es: $apellido</p>";
echo "<p>Tu edad es: $age</p>";
echo "<p>su numero de documento es: $document</p>";
echo "<p>Tu genero es: $genero</p>";
echo "<p>Tu telefono es: $telefono</p>";
echo "<p>Tu mecato preferido es:</p>";

echo "<ul>";
    foreach ($mecato as $rol){
        echo "<li>$rol</li>";
    }
echo "<p>Tu color elegido es: $color</p>";
echo "</ul>";

echo "<p>mensaje enviado: </p>";
echo "<p>$comentario </p>";

?>


