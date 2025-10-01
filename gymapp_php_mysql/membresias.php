<?php
// membresias.php
require 'db.php';
$action = $_GET['action'] ?? '';
if ($action=='add' && $_SERVER['REQUEST_METHOD']=='POST'){
    $n=$mysqli->real_escape_string($_POST['nombre']);
    $d=(int)$_POST['duracion'];
    $p=(float)$_POST['precio'];
    $mysqli->query("INSERT INTO membresias (nombre,duracion_dias,precio) VALUES ('$n',$d,$p)");
    header("Location: membresias.php"); exit;
}
if ($action=='delete'){ $id=(int)$_GET['id']; $mysqli->query("DELETE FROM membresias WHERE id_membresia=$id"); header("Location: membresias.php"); exit;}
if ($action=='edit' && $_SERVER['REQUEST_METHOD']=='POST'){
    $id=(int)$_POST['id']; $n=$mysqli->real_escape_string($_POST['nombre']); $d=(int)$_POST['duracion']; $p=(float)$_POST['precio'];
    $mysqli->query("UPDATE membresias SET nombre='$n', duracion_dias=$d, precio=$p WHERE id_membresia=$id");
    header("Location: membresias.php"); exit;
}
$res = $mysqli->query("SELECT * FROM membresias ORDER BY id_membresia DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Membresías</title><link rel="stylesheet" href="styles.css"></head>
<body><div class="container">
  <h2>Membresías</h2><a class="btn" href="index.php">← Menú</a>
  <table><tr><th>ID</th><th>Nombre</th><th>Días</th><th>Precio</th><th>Acciones</th></tr>
    <?php while($r=$res->fetch_assoc()): ?>
      <tr>
        <td><?=$r['id_membresia']?></td>
        <td><?=htmlspecialchars($r['nombre'])?></td>
        <td><?=$r['duracion_dias']?></td>
        <td><?=$r['precio']?></td>
        <td>
          <a class="btn" href="membresias.php?action=editform&id=<?=$r['id_membresia']?>">Editar</a>
          <a class="btn danger" href="membresias.php?action=delete&id=<?=$r['id_membresia']?>" onclick="return confirm('Borrar?')">Borrar</a>
        </td>
      </tr>
    <?php endwhile;?>
  </table>

  <hr>
  <h3>Agregar membresía</h3>
  <form method="post" action="membresias.php?action=add">
    <label>Nombre</label><input name="nombre" required>
    <label>Duración (días)</label><input name="duracion" type="number" required>
    <label>Precio</label><input name="precio" type="number" step="0.01" required>
    <button class="btn">Agregar</button>
  </form>

<?php if (isset($_GET['action']) && $_GET['action']=='editform') {
    $id=(int)$_GET['id']; $r=$mysqli->query("SELECT * FROM membresias WHERE id_membresia=$id")->fetch_assoc(); if ($r): ?>
      <hr><h3>Editar membresía</h3>
      <form method="post" action="membresias.php?action=edit">
        <input type="hidden" name="id" value="<?=$id?>">
        <label>Nombre</label><input name="nombre" value="<?=htmlspecialchars($r['nombre'])?>" required>
        <label>Duración (días)</label><input name="duracion" type="number" value="<?=$r['duracion_dias']?>" required>
        <label>Precio</label><input name="precio" type="number" step="0.01" value="<?=$r['precio']?>" required>
        <button class="btn">Guardar</button>
      </form>
<?php endif; } ?>

</div></body></html>