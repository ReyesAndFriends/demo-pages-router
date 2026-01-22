<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Configuración general del router
|--------------------------------------------------------------------------
| Define rutas base, carpetas de demos, carpetas ignoradas y rutas reservadas.
*/

$BASE_DIR = __DIR__;
$PAGES_DIR = $BASE_DIR . '/pages';

// Carpetas que serán excluidas del listado de demos
$IGNORE_DIRS = [
    'assets',
    'routes',
    'node_modules',
    'vendor',
];

/*
|--------------------------------------------------------------------------
| Procesamiento de la solicitud HTTP
|--------------------------------------------------------------------------
| Obtiene la ruta solicitada y realiza validaciones básicas de seguridad.
*/

$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = trim($uriPath, '/');

// Validación para evitar rutas maliciosas
if (str_contains($request, '..')) {
    http_response_code(400);
    exit('Bad request');
}

/*
|--------------------------------------------------------------------------
| Listado de demos (ruta raíz)
|--------------------------------------------------------------------------
| Si no se solicita ninguna ruta, muestra un listado de todas las demos disponibles.
*/

if ($request === '') {
    $dirs = [];
    if (is_dir($PAGES_DIR)) {
        $allDirs = glob($PAGES_DIR . '/*', GLOB_ONLYDIR);
        foreach ($allDirs as $dirPath) {
            $dir = basename($dirPath);
            if ($dir[0] !== '.' && !in_array($dir, $IGNORE_DIRS, true)) {
                $dirs[] = $dir;
            }
        }
    }

    sort($dirs);

    // UI estilizado para listado de demos
    echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demos disponibles - Reyes&Friends</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="https://cdn.reyesandfriends.cl/assets/favicon.png" type="image/x-icon">
    <style type="text/css">
        body, html { margin:0; padding:0; font-family:'Roboto',sans-serif; line-height:1.5; color:#f0f0f0; background-color:#1b1b1b; }
        .web-container { max-width:600px; margin:50px auto; background-color:#1b1b1b; padding:0; min-height:70vh; }
        .header { padding:20px; text-align:center; position:relative; overflow:hidden; pointer-events:none; }
        .logo { width:180px; height:auto; margin:0 auto; display:block; position:relative; z-index:2; }
        .content { padding:40px; background-color:#212121; border-radius:8px; margin:0 20px; position:relative; z-index:2; box-shadow:0 4px 8px #00000033; }
        .title { color:#ffffff; font-size:28px; font-weight:bold; margin-bottom:30px; border-bottom:2px solid #8B0000; padding-bottom:15px; text-align:center; }
        .center { text-align:center; }
        .paragraph { margin-bottom:25px; color:#e0e0e0; font-size:16px; text-align:center; }
        ul.demo-list { list-style:none; padding:0; margin:0 0 20px 0; }
        ul.demo-list li { margin-bottom:14px; text-align:center; display:flex; align-items:center; justify-content:center; gap:10px; }
        ul.demo-list a { color:#38bdf8; text-decoration:none; font-size:18px; font-weight:500; }
        ul.demo-list a:hover { text-decoration:underline; }
        .demo-thumb { width:144px; height:auto; aspect-ratio:16/9; object-fit:cover; border-radius:5px; box-shadow:0 2px 6px #0003; background:#222; pointer-events:none; }
        .footer { padding:30px 20px 20px; text-align:center; font-size:12px; color:#a0a0a0; border-top:1px solid #333333; margin-top:30px; }
        .john-smith-img { position:fixed; right:5%; transform:translateX(50%); bottom:0; width:120px; max-width:40vw; opacity:0.18; pointer-events:none; z-index:100; }
        @media (min-width:600px) { .john-smith-img { width:260px; max-width:none; bottom:-30px; } }
    </style>
</head>
<body>
    <img src="https://cdn.reyesandfriends.cl/assets/john-smith/error-vector.png" alt="" class="john-smith-img" />
    <div class="web-container">
        <div class="header">
            <img src="https://cdn.reyesandfriends.cl/assets/logo/reyesandfriends-white.svg" alt="Reyes&Friends logo" width="200" height="auto" class="logo">
        </div>
        <div class="content">
            <h1 class="title">Demos disponibles</h1>
            <p class="paragraph">Selecciona una demo para ver su contenido:</p>
            <ul class="demo-list">
HTML;
    foreach ($dirs as $dir) {
        $thumbPath = "/pages/$dir/image.png";
        $absThumbPath = $PAGES_DIR . "/$dir/image.png";
        echo "<li>";
        if (file_exists($absThumbPath)) {
            echo "<img src=\"$thumbPath\" alt=\"thumb $dir\" class=\"demo-thumb\" />";
        }
        echo "<a href=\"/$dir\">/$dir</a></li>";
    }
    echo <<<HTML
            </ul>
        </div>
        <div class="footer">
            <p>© <script>document.write(new Date().getFullYear());</script> Reyes&Friends<br>Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
HTML;
    exit;
}

/*
|--------------------------------------------------------------------------
| Servir demo individual
|--------------------------------------------------------------------------
| Busca y sirve el archivo index.html de la demo solicitada si existe.
*/

// Ruta esperada: /pages/{request}/index.html
$demoPath = $PAGES_DIR . '/' . $request;

// Verifica si existe la carpeta de la demo y el archivo index.html
if (is_dir($demoPath)) {
    $indexHtml = $demoPath . '/index.html';

    if (file_exists($indexHtml)) {
        readfile($indexHtml);
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Manejo de errores 404
|--------------------------------------------------------------------------
| Si la demo no existe, retorna una página de error 404.
*/

http_response_code(404);
echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página No Encontrada - Reyes&Friends</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="https://cdn.reyesandfriends.cl/assets/favicon.png" type="image/x-icon">
    <style type="text/css">
        body, html { margin:0; padding:0; font-family:'Roboto',sans-serif; line-height:1.5; color:#f0f0f0; background-color:#1b1b1b; }
        .web-container { max-width:600px; margin:50px auto; background-color:#1b1b1b; padding:0; min-height:70vh; }
        .header { padding:20px; text-align:center; position:relative; overflow:hidden; pointer-events:none; }
        .logo { width:180px; height:auto; margin:0 auto; display:block; position:relative; z-index:2; }
        .content { padding:40px; background-color:#212121; border-radius:8px; margin:0 20px; position:relative; z-index:2; box-shadow:0 4px 8px #00000033; }
        .title { color:#ffffff; font-size:28px; font-weight:bold; margin-bottom:30px; border-bottom:2px solid #8B0000; padding-bottom:15px; text-align:center; }
        .center { text-align:center; }
        .paragraph { margin-bottom:25px; color:#e0e0e0; font-size:16px; text-align:center; }
        .button { display:inline-block; background-color:#8B0000; color:#ffffff; padding:12px 24px; text-decoration:none; border-radius:4px; font-weight:bold; margin:20px 0; text-align:center; }
        .button:hover { background-color:#5a1616; }
        .footer { padding:30px 20px 20px; text-align:center; font-size:12px; color:#a0a0a0; border-top:1px solid #333333; margin-top:30px; }
        .john-smith-img { position:fixed; right:5%; transform:translateX(50%); bottom:0; width:120px; max-width:40vw; opacity:0.18; pointer-events:none; z-index:100; }
        @media (min-width:600px) { .john-smith-img { width:260px; max-width:none; bottom:-30px; } }
    </style>
</head>
<body>
    <img src="https://cdn.reyesandfriends.cl/assets/john-smith/error-vector.png" alt="" class="john-smith-img" />
    <div class="web-container">
        <div class="header">
            <img src="https://cdn.reyesandfriends.cl/assets/logo/reyesandfriends-white.svg" alt="Reyes&Friends logo" width="200" height="auto" class="logo">
        </div>
        <div class="content">
            <h1 class="title">Error 404 - Página No Encontrada</h1>
            <p class="paragraph">
                La página que buscas no existe o ha sido movida. Por favor, verifica la dirección e inténtalo de nuevo.
            </p>
            <p class="paragraph center">
                <a href="/" class="button button-white-text">Volver al listado de demos</a>
            </p>
            <p class="paragraph">
                Si eres un cliente y necesitas asistencia inmediata, por favor contáctanos a través de nuestro correo de soporte:
                <a href="mailto:contacto@reyesandfriends.cl" style="color: #ffffff; text-decoration: underline;">contacto@reyesandfriends.cl</a>
            </p>
        </div>
        <div class="footer">
            <p>© <script>document.write(new Date().getFullYear());</script> Reyes&Friends<br>Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
HTML;
