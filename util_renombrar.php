<?php
// 1) Directorio donde están los archivos
$directorio = __DIR__ . '/temp'; // cámbialo por tu ruta

// 2) Carácter (o cadena) a buscar
$buscar = ')'; // ej. un espacio

// 3) Carácter (o cadena) que sustituye al buscado
$reemplazar = '';

// --- 4) Recorrer y renombrar ---
if (!is_dir($directorio)) {
    die("No existe el directorio: $directorio\n");
}

$elementos = scandir($directorio);

foreach ($elementos as $nombre) {
    if ($nombre === '.' || $nombre === '..') continue;

    $rutaActual = $directorio . DIRECTORY_SEPARATOR . $nombre;

    // (Opcional) sólo archivos; elimina esta línea si quieres renombrar carpetas también
    if (is_dir($rutaActual)) continue;

    // Si el nombre no contiene el carácter a buscar, pasar
    if (strpos($nombre, $buscar) === false) continue;

    $nuevoNombre = str_replace($buscar, $reemplazar, $nombre);
    $rutaNueva   = $directorio . DIRECTORY_SEPARATOR . $nuevoNombre;

    // Evitar colisión simple
    if (file_exists($rutaNueva)) {
        echo "Omitido (ya existe): $nuevoNombre\n";
        continue;
    }

    if (@rename($rutaActual, $rutaNueva)) {
        echo "Renombrado: $nombre -> $nuevoNombre\n";
    } else {
        echo "Error al renombrar: $nombre\n";
    }
}


// Imprimir en pantalla
// Carpeta a listar (ajústala a tu ruta)
$directorio = __DIR__ . '\temp';
// Validación básica
if (!is_dir($directorio)) {
    http_response_code(500);
    echo "No existe el directorio: $directorio";
    exit;
}

// Leer contenidos
$entradas = scandir($directorio);
$archivos = [];

foreach ($entradas as $nombre) {
    if ($nombre === '.' || $nombre === '..') continue;

   $ruta = $directorio . DIRECTORY_SEPARATOR . $nombre;
    //$ruta = $nombre;

    // Solo archivos (no carpetas). Elimina esta línea si quieres listarlo todo.
    if (!is_file($ruta)) continue;

    // Nombre completo (ruta absoluta)
    $completo = realpath($ruta) ?: $ruta;
    $archivos[] = $completo;
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Listado de archivos</title>
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    tbody tr:nth-child(odd) { background: #f9f9f9; }
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 24px; }
    h1 { font-size: 18px; margin-bottom: 12px; }
  </style>
</head>
<body>
  <h1>Archivos en: <?= htmlspecialchars($directorio, ENT_QUOTES, 'UTF-8') ?></h1>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Nombre completo</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!$archivos): ?>
        <tr><td colspan="2">No hay archivos en esta carpeta.</td></tr>
      <?php else: ?>
        <?php foreach ($archivos as $i => $full): ?>
          <tr>

            <td><?= htmlspecialchars($full, ENT_QUOTES, 'UTF-8') ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Nota: Si prefieres solo el nombre con extensión (sin ruta), usa basename($full). -->
</body>
</html>

