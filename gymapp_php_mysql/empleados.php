<?php
// empleados.php
require 'db.php';
$action = $_GET['action'] ?? '';
if ($action=='add' && $_SERVER['REQUEST_METHOD']=='POST'){
    $n=$mysqli->real_escape_string($_POST['nombre']);
    $a=$mysqli->real_escape_string($_POST['apellidos']);
    $t=$mysqli->real_escape_string($_POST['telefono']);
    $e=$mysqli->real_escape_string($_POST['email']);
    $r=$mysqli->real_escape_string($_POST['rol']);
    $mysqli->query("INSERT INTO empleados (nombre,apellidos,telefono,email,rol) VALUES ('$n','$a','$t','$e','$r')");
    header("Location: empleados.php"); exit;
}
if ($action=='delete'){ $id=(int)$_GET['id']; $mysqli->query("DELETE FROM empleados WHERE id_empleado=$id"); header("Location: empleados.php"); exit; }
if ($action=='edit' && $_SERVER['REQUEST_METHOD']=='POST'){
    $id=(int)$_POST['id']; $n=$mysqli->real_escape_string($_POST['nombre']);
    $a=$mysqli->real_escape_string($_POST['apellidos']); $t=$mysqli->real_escape_string($_POST['telefono']);
    $e=$mysqli->real_escape_string($_POST['email']); $r=$mysqli->real_escape_string($_POST['rol']);
    $mysqli->query("UPDATE empleados SET nombre='$n',apellidos='$a',telefono='$t',email='$e',rol='$r' WHERE id_empleado=$id");
    header("Location: empleados.php"); exit;
}
$res = $mysqli->query("SELECT * FROM empleados ORDER BY id_empleado DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Empleados</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h2>Empleados</h2>
  <a class="btn" href="index.php">← Menú</a>
  <table>
    <tr><th>ID</th><th>Nombre</th><th>Rol</th><th>Tel</th><th>Email</th><th>Acciones</th></tr>
    <?php while($r=$res->fetch_assoc()): ?>
      <tr>
        <td><?=$r['id_empleado']?></td>
        <td><?=htmlspecialchars($r['nombre'].' '.$r['apellidos'])?></td>
        <td><?=$r['rol']?></td>
        <td><?=htmlspecialchars($r['telefono'])?></td>
        <td><?=htmlspecialchars($r['email'])?></td>
        <td>
          <a class="btn" href="empleados.php?action=editform&id=<?=$r['id_empleado']?>">Editar</a>
          <a class="btn danger" href="empleados.php?action=delete&id=<?=$r['id_empleado']?>" onclick="return confirm('Borrar?')">Borrar</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <hr>
  <h3>Agregar empleado</h3>
  <form method="post" action="empleados.php?action=add">
    <label>Nombre</label><input name="nombre" required>
    <label>Apellidos</label><input name="apellidos" required>
    <label>Teléfono</label><input name="telefono">
    <label>Email</label><input name="email" type="email">
    <label>Rol</label>
    <select name="rol"><option>RECEPCION</option><option>ENTRENADOR</option><option>ADMIN</option><option>OTRO</option></select>
    <button class="btn">Agregar</button>
  </form>

<?php if (isset($_GET['action']) && $_GET['action']=='editform'): 
   $id=(int)$_GET['id']; $r=$mysqli->query("SELECT * FROM empleados WHERE id_empleado=$id")->fetch_assoc();
   if ($r):
?>
  <hr><h3>Editar empleado</h3>
  <form method="post" action="empleados.php?action=edit">
    <input type="hidden" name="id" value="<?=$id?>">
    <label>Nombre</label><input name="nombre" value="<?=htmlspecialchars($r['nombre'])?>" required>
    <label>Apellidos</label><input name="apellidos" value="<?=htmlspecialchars($r['apellidos'])?>" required>
    <label>Teléfono</label><input name="telefono" value="<?=htmlspecialchars($r['telefono'])?>">
    <label>Email</label><input name="email" type="email" value="<?=htmlspecialchars($r['email'])?>">
    <label>Rol</label>
    <select name="rol">
      <?php foreach(['RECEPCION','ENTRENADOR','ADMIN','OTRO'] as $rol): ?>
        <option <?= $r['rol']==$rol ? 'selected':'' ?>><?= $rol ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn">Guardar</button>
  </form>
<?php endif; endif; ?>

</div>
</body>
</html>