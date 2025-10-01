GymApp (PHP + MySQL) — Instrucciones
====================================
1) Copia todo el contenido de esta carpeta a C:\xampp\htdocs\gymapp
2) Inicia Apache y MySQL en XAMPP.
3) Importa tu base de datos (archivo gymdb_simple_final.sql) en phpMyAdmin.
   - Nombre esperado de la BD: gymdb_simple
4) Abre en el navegador: http://localhost/gymapp/

Archivos:
  - index.php: menú principal
  - db.php: conexión a MySQL (usuario root, contraseña vacía por defecto)
  - socios.php: CRUD de socios
  - empleados.php: CRUD de empleados
  - membresias.php: CRUD de membresías
  - pagos.php: registro y listado de pagos

Notas:
  - Ajusta credenciales en db.php si usas contraseña para MySQL.
  - Este código es educativo; para producción usa prepared statements y autenticación.
