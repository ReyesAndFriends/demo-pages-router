# demo-pages-router

**demo-pages-router** es un mini router en PHP diseñado para listar y servir múltiples sitios web estáticos (demos) compilados en una sola URL. Permite organizar y acceder fácilmente a diferentes proyectos web desde una única interfaz, facilitando la navegación y pruebas de demos en entornos de desarrollo o presentación.

## Características

- Listado automático de carpetas de demos.
- Ignora carpetas y rutas reservadas configurables.
- Soporte para rutas personalizadas con lógica propia.
- Servir archivos `index.html` de cada demo.
- Manejo básico de errores y seguridad.

## Estructura del Proyecto

```
demo-pages-router/
├── index.php
├── pages/
│   ├── demo1/
│   │   └── index.html
│   ├── demo2/
│   │   └── index.html
│   └── ...
├── routes/
│   ├── api.php
│   ├── admin.php
│   └── health.php
├── assets/
├── vendor/
└── README.md
```

- **index.php**: Script principal del router.
- **pages/**: Carpeta donde se ubican las demos (cada subcarpeta representa una demo).
- **routes/**: Rutas reservadas con lógica PHP personalizada.
- **assets/**, **vendor/**: Carpetas ignoradas por el listado de demos.

## Instalación

1. Clona este repositorio en tu servidor local o entorno de desarrollo.
2. Coloca tus demos en la carpeta `pages/`, cada una en su propia subcarpeta con un archivo `index.html`.
3. (Opcional) Agrega rutas personalizadas en la carpeta `routes/`.

## Uso

- Accede a la raíz del proyecto en tu navegador para ver el listado de demos disponibles.
- Haz clic en cualquier demo para visualizar su contenido.

## Configuración

Puedes modificar los arrays `$IGNORE_DIRS` y `$RESERVED_ROUTES` en `index.php` para personalizar carpetas ignoradas y rutas reservadas.

## Seguridad

Incluye una validación básica para evitar rutas maliciosas. Se recomienda usarlo únicamente en entornos de desarrollo o controlados.

## Licencia

MIT License.
