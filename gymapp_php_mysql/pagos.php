<?php
// pagos.php
require 'db.php';
$action = $_GET['action'] ?? '';
if ($action=='add' && $_SERVER['REQUEST_METHOD']=='POST'){
    $s=(int)$_POST['socio_id'];
    $m=(int)$_POST['membresia_id'];
    $emple=(int)($_POST['id_empleado']??0) ?: "NULL";
    $monto = (float)$_POST['monto'];
    $concepto = $mysqli->real_escape_string($_POST['concepto']);
    $metodo_pago = $mysqli->real_escape_string($_POST['metodo']); // Cambiado a $metodo_pago
    $ref = $mysqli->real_escape_string($_POST['referencia']);
    
    if ($emple === "NULL") {
        // CORREGIDO: cambiar 'metodo' por 'metodo_pago'
        $mysqli->query("INSERT INTO pagos (socio_id,membresia_id,id_empleado,monto,concepto,metodo_pago,referencia) VALUES ($s,$m,NULL,$monto,'$concepto','$metodo_pago','$ref')");
    } else {
        // CORREGIDO: cambiar 'metodo' por 'metodo_pago'
        $mysqli->query("INSERT INTO pagos (socio_id,membresia_id,id_empleado,monto,concepto,metodo_pago,referencia) VALUES ($s,$m,$emple,$monto,'$concepto','$metodo_pago','$ref')");
    }
    header("Location: pagos.php"); exit;
}
if ($action=='delete'){ $id=(int)$_GET['id']; $mysqli->query("DELETE FROM pagos WHERE id_pago=$id"); header("Location: pagos.php"); exit; }

$res = $mysqli->query("SELECT p.*, s.nombre AS socio_nombre, s.apellidos AS socio_ap, m.nombre AS membnombre, e.nombre AS emp_nombre, e.apellidos AS emp_ap FROM pagos p JOIN socios s ON p.socio_id=s.id_socio JOIN membresias m ON p.membresia_id=m.id_membresia LEFT JOIN empleados e ON p.id_empleado=e.id_empleado ORDER BY p.fecha_pago DESC LIMIT 200");
$allSocios = $mysqli->query("SELECT id_socio, nombre, apellidos FROM socios ORDER BY nombre");
$allMemb = $mysqli->query("SELECT id_membresia, nombre, precio FROM membresias ORDER BY nombre");
$allEmp = $mysqli->query("SELECT id_empleado, nombre, apellidos FROM empleados ORDER BY nombre");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Pagos</title><link rel="stylesheet" href="styles.css"></head>
<body>
<div class="container">
  <h2>Pagos</h2><a class="btn" href="index.php">← Menú</a>
  <table>
    <tr><th>ID</th><th>Socio</th><th>Membresía</th><th>Empleado</th><th>Monto</th><th>Fecha</th><th>Método Pago</th><th>Acciones</th></tr>
    <?php while($r=$res->fetch_assoc()): ?>
      <tr>
        <td><?=$r['id_pago']?></td>
        <td><?=htmlspecialchars($r['socio_nombre'].' '.$r['socio_ap'])?></td>
        <td><?=htmlspecialchars($r['membnombre'])?></td>
        <td><?= $r['emp_nombre'] ? htmlspecialchars($r['emp_nombre'].' '.$r['emp_ap']) : '-' ?></td>
        <td>$<?=number_format($r['monto'], 2)?></td>
        <td><?=$r['fecha_pago']?></td>
        <td><?=$r['metodo_pago']?></td> <!-- Agregado para mostrar método de pago -->
        <td><a class="btn danger" href="pagos.php?action=delete&id=<?=$r['id_pago']?>" onclick="return confirm('Eliminar pago?')">Eliminar</a></td>
      </tr>
    <?php endwhile; ?>
  </table>

  <hr>
  <h3>Registrar pago</h3>
  <form method="post" action="pagos.php?action=add">
    <label>Socio</label>
    <select name="socio_id" required>
      <?php while($s = $allSocios->fetch_assoc()): ?>
        <option value="<?=$s['id_socio']?>"><?=htmlspecialchars($s['nombre'].' '.$s['apellidos'])?></option>
      <?php endwhile; ?>
    </select>

    <label>Membresía</label>
    <select name="membresia_id" required>
      <?php while($m = $allMemb->fetch_assoc()): ?>
        <option value="<?=$m['id_membresia']?>"><?=htmlspecialchars($m['nombre'].' - $'.number_format($m['precio'], 2))?></option>
      <?php endwhile; ?>
    </select>

    <label>Empleado (quien registró)</label>
    <select name="id_empleado">
      <option value="">-- Ninguno --</option>
      <?php while($e = $allEmp->fetch_assoc()): ?>
        <option value="<?=$e['id_empleado']?>"><?=htmlspecialchars($e['nombre'].' '.$e['apellidos'])?></option>
      <?php endwhile; ?>
    </select>

    <label>Monto</label><input name="monto" type="number" step="0.01" required>

    <label>Concepto</label>
    <select name="concepto"><option>INSCRIPCION</option><option>RENOVACION</option><option>OTRO</option></select>

    <label>Método pago</label>
    <select name="metodo"><option>EFECTIVO</option><option>TARJETA</option><option>TRANSFERENCIA</option></select>

    <label>Referencia</label><input name="referencia">

    <button class="btn">Registrar pago</button>
  </form>

</div></body></html>