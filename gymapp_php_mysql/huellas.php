<?php
// huellas.php
require 'db.php';
$action = $_GET['action'] ?? '';
if ($action=='add' && $_SERVER['REQUEST_METHOD']=='POST'){
    $socio_id=(int)$_POST['socio_id'];
    $dedo = $mysqli->real_escape_string($_POST['dedo']);
    $formato = $mysqli->real_escape_string($_POST['formato']);
    // En un sistema real, aquí se capturaría la huella y se convertiría a un template en binario.
    // Para este ejemplo, simulamos un template vacío.
    $template = ''; // Debería ser un blob, pero no tenemos datos reales.
    $mysqli->query("INSERT INTO huellas (socio_id, dedo, formato, template) VALUES ($socio_id,'$dedo','$formato','$template')");
    header("Location: huellas.php"); exit;
}
if ($action=='delete'){ $id=(int)$_GET['id']; $mysqli->query("DELETE FROM huellas WHERE id_huella=$id"); header("Location: huellas.php"); exit; }

$res = $mysqli->query("SELECT h.*, s.nombre AS socio_nombre, s.apellidos AS socio_ap FROM huellas h JOIN socios s ON h.socio_id=s.id_socio ORDER BY h.id_huella DESC LIMIT 200");
$allSocios = $mysqli->query("SELECT id_socio, nombre, apellidos FROM socios ORDER BY nombre");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Huellas</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h2>Huellas</h2><a class="btn" href="index.php">← Menú</a>
  <table>
    <tr><th>ID</th><th>Socio</th><th>Dedo</th><th>Formato</th><th>Activo</th><th>Acciones</th></tr>
    <?php while($r=$res->fetch_assoc()): ?>
      <tr>
        <td><?=$r['id_huella']?></td>
        <td><?=htmlspecialchars($r['socio_nombre'].' '.$r['socio_ap'])?></td>
        <td><?=$r['dedo']?></td>
        <td><?=$r['formato']?></td>
        <td><?=$r['activo'] ? 'Sí' : 'No'?></td>
        <td><a class="btn danger" href="huellas.php?action=delete&id=<?=$r['id_huella']?>" onclick="return confirm('Eliminar huella?')">Eliminar</a></td>
      </tr>
    <?php endwhile; ?>
  </table>

  <hr>
  <h3>Registrar huella</h3>
  <form method="post" action="huellas.php?action=add">
    <label>Socio</label>
    <select name="socio_id" required>
      <?php while($s = $allSocios->fetch_assoc()): ?>
        <option value="<?=$s['id_socio']?>"><?=htmlspecialchars($s['nombre'].' '.$s['apellidos'])?></option>
      <?php endwhile; ?>
    </select>

    <label>Dedo</label>
    <select name="dedo">
      <option>PULGAR</option>
      <option>INDICE</option>
      <option>MEDIO</option>
      <option>ANULAR</option>
      <option>MENIQUE</option>
    </select>

    <label>Formato</label>
    <input name="formato" value="ISO" required>

    <button class="btn">Registrar</button>
  </form>

</div></body></html>