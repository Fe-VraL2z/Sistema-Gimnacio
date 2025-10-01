<?php
// socios.php
require 'db.php';

$action = $_GET['action'] ?? '';
if ($action == 'add' && $_SERVER['REQUEST_METHOD']=='POST') {
    $nombre = $mysqli->real_escape_string($_POST['nombre']);
    $apellidos = $mysqli->real_escape_string($_POST['apellidos']);
    $telefono = $mysqli->real_escape_string($_POST['telefono']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $mysqli->query("INSERT INTO socios (nombre, apellidos, telefono, email) VALUES ('$nombre','$apellidos','$telefono','$email')");
    header("Location: socios.php");
    exit;
}
if ($action == 'delete') {
    $id = (int)$_GET['id'];
    $mysqli->query("DELETE FROM socios WHERE id_socio=$id");
    header("Location: socios.php");
    exit;
}
if ($action=='edit' && $_SERVER['REQUEST_METHOD']=='POST') {
    $id = (int)$_POST['id'];
    $nombre = $mysqli->real_escape_string($_POST['nombre']);
    $apellidos = $mysqli->real_escape_string($_POST['apellidos']);
    $telefono = $mysqli->real_escape_string($_POST['telefono']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $mysqli->query("UPDATE socios SET nombre='$nombre', apellidos='$apellidos', telefono='$telefono', email='$email' WHERE id_socio=$id");
    header("Location: socios.php");
    exit;
}

// obtener lista
$res = $mysqli->query("SELECT * FROM socios ORDER BY id_socio DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Socios - GymApp</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <h2>Socios</h2>
  <a class="btn" href="index.php">← Menú</a>
  <a class="btn" href="#add">+ Nuevo socio</a>

  <table>
    <tr><th>ID</th><th>Nombre</th><th>Teléfono</th><th>Email</th><th>Activo</th><th>Acciones</th></tr>
    <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id_socio'] ?></td>
        <td><?= htmlspecialchars($row['nombre'].' '.$row['apellidos']) ?></td>
        <td><?= htmlspecialchars($row['telefono']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= $row['activo'] ? 'Sí' : 'No' ?></td>
        <td>
          <a class="btn" href="socios.php?action=editform&id=<?= $row['id_socio'] ?>">Editar</a>
          <a class="btn danger" href="socios.php?action=delete&id=<?= $row['id_socio'] ?>" onclick="return confirm('Borrar socio?')">Borrar</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <hr id="add">
  <h3>Agregar socio</h3>
  <form method="post" action="socios.php?action=add">
    <label>Nombre</label>
    <input name="nombre" required>
    <label>Apellidos</label>
    <input name="apellidos" required>
    <label>Teléfono</label>
    <input name="telefono">
    <label>Email</label>
    <input name="email" type="email">
    <button class="btn">Agregar</button>
  </form>

<?php
// mostrar formulario de edición si solicitado
if (isset($_GET['action']) && $_GET['action']=='editform') {
    $id = (int)$_GET['id'];
    $r = $mysqli->query("SELECT * FROM socios WHERE id_socio=$id")->fetch_assoc();
    if ($r):
?>
  <hr>
  <h3>Editar socio #<?= $id ?></h3>
  <form method="post" action="socios.php?action=edit">
    <input type="hidden" name="id" value="<?= $id ?>">
    <label>Nombre</label>
    <input name="nombre" value="<?= htmlspecialchars($r['nombre']) ?>" required>
    <label>Apellidos</label>
    <input name="apellidos" value="<?= htmlspecialchars($r['apellidos']) ?>" required>
    <label>Teléfono</label>
    <input name="telefono" value="<?= htmlspecialchars($r['telefono']) ?>">
    <label>Email</label>
    <input name="email" type="email" value="<?= htmlspecialchars($r['email']) ?>">
    <button class="btn">Guardar</button>
  </form>
<?php
    endif;
}
?>

</div>
</body>
</html>