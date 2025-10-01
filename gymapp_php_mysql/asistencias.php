<?php
// asistencias.php
require 'db.php';
$action = $_GET['action'] ?? '';
if ($action=='add' && $_SERVER['REQUEST_METHOD']=='POST'){
    $socio_id=(int)$_POST['socio_id'];
    $metodo = $mysqli->real_escape_string($_POST['metodo']);
    $valido = (int)isset($_POST['valido_por_membresia']);
    $mysqli->query("INSERT INTO asistencias (socio_id, metodo, valido_por_membresia) VALUES ($socio_id,'$metodo',$valido)");
    header("Location: asistencias.php"); exit;
}
if ($action=='delete'){ $id=(int)$_GET['id']; $mysqli->query("DELETE FROM asistencias WHERE id_asistencia=$id"); header("Location: asistencias.php"); exit; }

$res = $mysqli->query("SELECT a.*, s.nombre AS socio_nombre, s.apellidos AS socio_ap FROM asistencias a JOIN socios s ON a.socio_id=s.id_socio ORDER BY a.fecha_hora DESC LIMIT 200");
$allSocios = $mysqli->query("SELECT id_socio, nombre, apellidos FROM socios ORDER BY nombre");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Asistencias</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h2>Asistencias</h2><a class="btn" href="index.php">← Menú</a>
  <table>
    <tr><th>ID</th><th>Socio</th><th>Fecha y Hora</th><th>Método</th><th>Válido por membresía</th><th>Acciones</th></tr>
    <?php while($r=$res->fetch_assoc()): ?>
      <tr>
        <td><?=$r['id_asistencia']?></td>
        <td><?=htmlspecialchars($r['socio_nombre'].' '.$r['socio_ap'])?></td>
        <td><?=$r['fecha_hora']?></td>
        <td><?=$r['metodo']?></td>
        <td><?=$r['valido_por_membresia'] ? 'Sí' : 'No'?></td>
        <td><a class="btn danger" href="asistencias.php?action=delete&id=<?=$r['id_asistencia']?>" onclick="return confirm('Eliminar asistencia?')">Eliminar</a></td>
      </tr>
    <?php endwhile; ?>
  </table>

  <hr>
  <h3>Registrar asistencia</h3>
  <form method="post" action="asistencias.php?action=add">
    <label>Socio</label>
    <select name="socio_id" required>
      <?php while($s = $allSocios->fetch_assoc()): ?>
        <option value="<?=$s['id_socio']?>"><?=htmlspecialchars($s['nombre'].' '.$s['apellidos'])?></option>
      <?php endwhile; ?>
    </select>

    <label>Método</label>
    <select name="metodo"><option>QR</option><option>HUELLA</option><option>MANUAL</option></select>

    <label><input type="checkbox" name="valido_por_membresia" value="1" checked> Válido por membresía</label>

    <button class="btn">Registrar</button>
  </form>

</div></body></html>