<?php
// socios_membresias.php
require 'db.php';
$action = $_GET['action'] ?? '';
if ($action=='add' && $_SERVER['REQUEST_METHOD']=='POST'){
    $socio_id=(int)$_POST['socio_id'];
    $membresia_id=(int)$_POST['membresia_id'];
    $fecha_inicio = $mysqli->real_escape_string($_POST['fecha_inicio']);
    $fecha_fin = $mysqli->real_escape_string($_POST['fecha_fin']);
    $estado = $mysqli->real_escape_string($_POST['estado']);
    $mysqli->query("INSERT INTO socios_membresias (socio_id, membresia_id, fecha_inicio, fecha_fin, estado) VALUES ($socio_id,$membresia_id,'$fecha_inicio','$fecha_fin','$estado')");
    header("Location: socios_membresias.php"); exit;
}
if ($action=='delete'){ $id=(int)$_GET['id']; $mysqli->query("DELETE FROM socios_membresias WHERE id_socio_membresia=$id"); header("Location: socios_membresias.php"); exit; }
if ($action=='edit' && $_SERVER['REQUEST_METHOD']=='POST'){
    $id=(int)$_POST['id'];
    $socio_id=(int)$_POST['socio_id'];
    $membresia_id=(int)$_POST['membresia_id'];
    $fecha_inicio = $mysqli->real_escape_string($_POST['fecha_inicio']);
    $fecha_fin = $mysqli->real_escape_string($_POST['fecha_fin']);
    $estado = $mysqli->real_escape_string($_POST['estado']);
    $mysqli->query("UPDATE socios_membresias SET socio_id=$socio_id, membresia_id=$membresia_id, fecha_inicio='$fecha_inicio', fecha_fin='$fecha_fin', estado='$estado' WHERE id_socio_membresia=$id");
    header("Location: socios_membresias.php"); exit;
}

$res = $mysqli->query("SELECT sm.*, s.nombre AS socio_nombre, s.apellidos AS socio_ap, m.nombre AS memb_nombre FROM socios_membresias sm JOIN socios s ON sm.socio_id=s.id_socio JOIN membresias m ON sm.membresia_id=m.id_membresia ORDER BY sm.fecha_inicio DESC LIMIT 200");
$allSocios = $mysqli->query("SELECT id_socio, nombre, apellidos FROM socios ORDER BY nombre");
$allMemb = $mysqli->query("SELECT id_membresia, nombre FROM membresias ORDER BY nombre");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Socios-Membresías</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h2>Socios-Membresías</h2><a class="btn" href="index.php">← Menú</a>
  <table>
    <tr><th>ID</th><th>Socio</th><th>Membresía</th><th>Inicio</th><th>Fin</th><th>Estado</th><th>Acciones</th></tr>
    <?php while($r=$res->fetch_assoc()): ?>
      <tr>
        <td><?=$r['id_socio_membresia']?></td>
        <td><?=htmlspecialchars($r['socio_nombre'].' '.$r['socio_ap'])?></td>
        <td><?=htmlspecialchars($r['memb_nombre'])?></td>
        <td><?=$r['fecha_inicio']?></td>
        <td><?=$r['fecha_fin']?></td>
        <td><?=$r['estado']?></td>
        <td>
          <a class="btn" href="socios_membresias.php?action=editform&id=<?=$r['id_socio_membresia']?>">Editar</a>
          <a class="btn danger" href="socios_membresias.php?action=delete&id=<?=$r['id_socio_membresia']?>" onclick="return confirm('Eliminar?')">Eliminar</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <hr>
  <h3>Agregar relación Socio-Membresía</h3>
  <form method="post" action="socios_membresias.php?action=add">
    <label>Socio</label>
    <select name="socio_id" required>
      <?php while($s = $allSocios->fetch_assoc()): ?>
        <option value="<?=$s['id_socio']?>"><?=htmlspecialchars($s['nombre'].' '.$s['apellidos'])?></option>
      <?php endwhile; ?>
    </select>

    <label>Membresía</label>
    <select name="membresia_id" required>
      <?php while($m = $allMemb->fetch_assoc()): ?>
        <option value="<?=$m['id_membresia']?>"><?=htmlspecialchars($m['nombre'])?></option>
      <?php endwhile; ?>
    </select>

    <label>Fecha inicio</label>
    <input name="fecha_inicio" type="date" required>

    <label>Fecha fin</label>
    <input name="fecha_fin" type="date" required>

    <label>Estado</label>
    <select name="estado">
      <option>ACTIVA</option>
      <option>VENCIDA</option>
      <option>CANCELADA</option>
    </select>

    <button class="btn">Agregar</button>
  </form>

<?php if (isset($_GET['action']) && $_GET['action']=='editform') {
    $id=(int)$_GET['id']; $r=$mysqli->query("SELECT * FROM socios_membresias WHERE id_socio_membresia=$id")->fetch_assoc(); if ($r): ?>
      <hr><h3>Editar relación Socio-Membresía</h3>
      <form method="post" action="socios_membresias.php?action=edit">
        <input type="hidden" name="id" value="<?=$id?>">
        <label>Socio</label>
        <select name="socio_id" required>
          <?php $allSocios2 = $mysqli->query("SELECT id_socio, nombre, apellidos FROM socios ORDER BY nombre"); ?>
          <?php while($s = $allSocios2->fetch_assoc()): ?>
            <option value="<?=$s['id_socio']?>" <?= $r['socio_id']==$s['id_socio'] ? 'selected':'' ?>><?=htmlspecialchars($s['nombre'].' '.$s['apellidos'])?></option>
          <?php endwhile; ?>
        </select>

        <label>Membresía</label>
        <select name="membresia_id" required>
          <?php $allMemb2 = $mysqli->query("SELECT id_membresia, nombre FROM membresias ORDER BY nombre"); ?>
          <?php while($m = $allMemb2->fetch_assoc()): ?>
            <option value="<?=$m['id_membresia']?>" <?= $r['membresia_id']==$m['id_membresia'] ? 'selected':'' ?>><?=htmlspecialchars($m['nombre'])?></option>
          <?php endwhile; ?>
        </select>

        <label>Fecha inicio</label>
        <input name="fecha_inicio" type="date" value="<?=$r['fecha_inicio']?>" required>

        <label>Fecha fin</label>
        <input name="fecha_fin" type="date" value="<?=$r['fecha_fin']?>" required>

        <label>Estado</label>
        <select name="estado">
          <?php foreach(['ACTIVA','VENCIDA','CANCELADA'] as $est): ?>
            <option <?= $r['estado']==$est ? 'selected':'' ?>><?= $est ?></option>
          <?php endforeach; ?>
        </select>

        <button class="btn">Guardar</button>
      </form>
<?php endif; } ?>

</div></body></html>