<?php
include("config/setup.php");
$db = conectar();

// Este script activa usuarios que tengan estado '1' o '0' y los pasa a 'activo'/'inactivo'
// También muestra todos los usuarios para que sepas cuál activar

echo "<h2>Usuarios en la base de datos:</h2>";
echo "<table border='1' cellpadding='8' style='border-collapse:collapse; font-family:sans-serif;'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Perfil</th><th>Estado actual</th><th>Corregir?</th></tr>";

$result = mysqli_query($db, "SELECT u.*, p.nombre AS perfil FROM usuarios u INNER JOIN perfiles p ON u.idperfil = p.idperfil ORDER BY u.id");
while ($r = mysqli_fetch_assoc($result)) {
    $estado_actual = $r['estado'];
    $necesita_correccion = ($estado_actual === '1' || $estado_actual === '0');
    
    echo "<tr>";
    echo "<td>" . $r['id'] . "</td>";
    echo "<td>" . htmlspecialchars($r['nombre'] . ' ' . $r['apellido']) . "</td>";
    echo "<td>" . htmlspecialchars($r['email']) . "</td>";
    echo "<td>" . htmlspecialchars($r['perfil']) . "</td>";
    echo "<td>" . htmlspecialchars($estado_actual) . "</td>";
    echo "<td>" . ($necesita_correccion ? '⚠️ Sí' : '✅ OK') . "</td>";
    echo "</tr>";
}
echo "</table>";

// SI PRESIONAS ESTE BOTÓN, se corrigen TODOS los estados
echo "<br><br>";
echo "<form method='post'>";
echo "<button type='submit' name='corregir' style='padding:12px 24px; background:#b0a78f; color:white; border:none; border-radius:6px; font-size:16px; cursor:pointer;'>";
echo "🔄 CORREGIR TODOS LOS ESTADOS (1→activo, 0→inactivo)</button>";
echo "</form>";

if (isset($_POST['corregir'])) {
    // Corregir usuarios con estado '1' a 'activo'
    mysqli_query($db, "UPDATE usuarios SET estado = 'activo' WHERE estado = '1'");
    // Corregir usuarios con estado '0' a 'inactivo'
    mysqli_query($db, "UPDATE usuarios SET estado = 'inactivo' WHERE estado = '0'");
    echo "<p style='color:green; font-weight:bold;'>✅ Estados corregidos exitosamente.</p>";
    echo "<p>Ahora ve a <a href='iniciosesion.php'>iniciar sesión</a></p>";
}

// También puedes activar un usuario específico por ID
echo "<br><br><hr><h3>Activar usuario específico por ID:</h3>";
echo "<form method='post'>";
echo "ID del usuario: <input type='number' name='id_usuario' required>";
echo "<button type='submit' name='activar_por_id' style='padding:6px 12px; background:#28a745; color:white; border:none; border-radius:4px;'>Activar</button>";
echo "</form>";

if (isset($_POST['activar_por_id'])) {
    $id = intval($_POST['id_usuario']);
    mysqli_query($db, "UPDATE usuarios SET estado = 'activo' WHERE id = '$id'");
    echo "<p style='color:green; font-weight:bold;'>✅ Usuario ID $id activado correctamente.</p>";
    echo "<p>Ahora ve a <a href='iniciosesion.php'>iniciar sesión</a></p>";
}
?>