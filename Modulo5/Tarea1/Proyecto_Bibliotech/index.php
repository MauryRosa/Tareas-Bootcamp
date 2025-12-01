<?php
session_start();
require_once 'Biblioteca.php';

// Inicializar biblioteca
$biblioteca = new Biblioteca();
$gestorDatos = GestorDatos::getInstancia();

// Cargar datos existentes
$gestorDatos->cargarBiblioteca($biblioteca);

// Variable para controlar el modo edición
$modoEdicion = false;
$libroEditar = null;

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Crear libro
    if (isset($_POST['crear_libro'])) {
        $id = uniqid();
        $libro = new Libro(
            $id,
            $_POST['titulo'],
            $_POST['autor'],
            $_POST['categoria'],
            $_POST['isbn'],
            $_POST['anio_publicacion'],
            $_POST['editorial']
        );
        $biblioteca->agregarLibro($libro);
        $gestorDatos->guardarBiblioteca($biblioteca);
        header('Location: index.php');
        exit;
    }
    
    // Editar libro
    if (isset($_POST['editar_libro'])) {
        $datos = [
            'titulo' => $_POST['titulo'],
            'autor' => $_POST['autor'],
            'categoria' => $_POST['categoria'],
            'isbn' => $_POST['isbn'],
            'anio_publicacion' => $_POST['anio_publicacion'],
            'editorial' => $_POST['editorial']
        ];
        $biblioteca->editarLibro($_POST['libro_id'], $datos);
        $gestorDatos->guardarBiblioteca($biblioteca);
        header('Location: index.php');
        exit;
    }
    
    // Buscar libros
    if (isset($_POST['buscar'])) {
        $termino = $_POST['termino_busqueda'];
        $criterio = $_POST['criterio_busqueda'];
        
        switch ($criterio) {
            case 'titulo':
                $resultados_busqueda = $biblioteca->buscarPorTitulo($termino);
                break;
            case 'autor':
                $resultados_busqueda = $biblioteca->buscarPorAutor($termino);
                break;
            case 'categoria':
                $resultados_busqueda = $biblioteca->buscarPorCategoria($termino);
                break;
            case 'isbn':
                $libro = $biblioteca->buscarPorIsbn($termino);
                $resultados_busqueda = $libro ? [$libro] : [];
                break;
            default:
                $resultados_busqueda = [];
        }
    }
}

// Procesar acciones GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    // Modo edición
    if (isset($_GET['editar'])) {
        $modoEdicion = true;
        // Buscar el libro a editar
        foreach ($biblioteca->getLibros() as $libro) {
            if ($libro->getId() == $_GET['editar']) {
                $libroEditar = $libro;
                break;
            }
        }
    }
    
    // Eliminar libro
    if (isset($_GET['eliminar'])) {
        $biblioteca->eliminarLibro($_GET['eliminar']);
        $gestorDatos->guardarBiblioteca($biblioteca);
        header('Location: index.php');
        exit;
    }
    
    // Prestar libro
    if (isset($_GET['prestar'])) {
        $biblioteca->prestarLibro($_GET['prestar']);
        $gestorDatos->guardarBiblioteca($biblioteca);
        header('Location: index.php');
        exit;
    }
    
    // Devolver libro
    if (isset($_GET['devolver'])) {
        $biblioteca->devolverLibro($_GET['devolver']);
        $gestorDatos->guardarBiblioteca($biblioteca);
        header('Location: index.php');
        exit;
    }
}

// Obtener estadísticas
$total_libros = $biblioteca->getTotalLibros();
$libros_disponibles = $biblioteca->getLibrosDisponibles();
$libros_prestados = $biblioteca->getLibrosPrestados();
$libros_por_categoria = $biblioteca->getLibrosPorCategoria();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliotecaTech | Sistema de Gestión</title>
    
    <!-- Librerías CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href=".//css/styles.css">
</head>
<body>
    <div class="app-container">
        <!-- Navbar Profesional -->
        <nav class="navbar navbar-expand-lg navbar-profesional">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-book"></i>
                    BibliotecaTech
                </a>
            </div>
        </nav>

        <!-- Contenido Principal -->
        <main class="main-content">
            <div class="container-fluid">
                <!-- Estadísticas -->
                <div class="stats-grid fade-in">
                    <div class="stat-card">
                        <div class="stat-content">
                            <div class="stat-info">
                                <div class="stat-number"><?php echo $total_libros; ?></div>
                                <div class="stat-label">Total de Libros</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-content">
                            <div class="stat-info">
                                <div class="stat-number text-success"><?php echo $libros_disponibles; ?></div>
                                <div class="stat-label">Disponibles</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-content">
                            <div class="stat-info">
                                <div class="stat-number text-warning"><?php echo $libros_prestados; ?></div>
                                <div class="stat-label">Prestados</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-hand-holding"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-content">
                            <div class="stat-info">
                                <div class="stat-number"><?php echo count($libros_por_categoria); ?></div>
                                <div class="stat-label">Categorías</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Búsqueda -->
                <div class="search-section fade-in">
                    <h3 class="section-title">
                        <i class="fas fa-search"></i>
                        Buscar Libros
                    </h3>
                    <form method="POST" class="row g-3 form-profesional">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="termino_busqueda" 
                                   placeholder="Ingrese título, autor, categoría o ISBN..." required>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" name="criterio_busqueda">
                                <option value="titulo">Título</option>
                                <option value="autor">Autor</option>
                                <option value="categoria">Categoría</option>
                                <option value="isbn">ISBN</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="buscar" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Resultados de Búsqueda -->
                <?php if (isset($resultados_busqueda) && !empty($resultados_busqueda)): ?>
                <div class="card-profesional fade-in">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-search"></i>
                            Resultados de Búsqueda (<?php echo count($resultados_busqueda); ?>)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-profesional">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Autor</th>
                                        <th>Categoría</th>
                                        <th>ISBN</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($resultados_busqueda as $libro): ?>
                                    <tr class="slide-in">
                                        <td class="fw-bold"><?php echo $libro->getTitulo(); ?></td>
                                        <td><?php echo $libro->getAutor(); ?></td>
                                        <td>
                                            <span class="badge bg-light text-dark"><?php echo $libro->getCategoria(); ?></span>
                                        </td>
                                        <td class="text-secondary"><?php echo $libro->getIsbn(); ?></td>
                                        <td>
                                            <?php if ($libro->getDisponible()): ?>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Disponible
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock"></i> Prestado
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="acciones-tabla">
                                                <a href="?editar=<?php echo $libro->getId(); ?>" class="btn btn-outline btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($libro->getDisponible()): ?>
                                                    <a href="?prestar=<?php echo $libro->getId(); ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-hand-holding"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="?devolver=<?php echo $libro->getId(); ?>" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-undo"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="?eliminar=<?php echo $libro->getId(); ?>" class="btn btn-danger btn-sm" 
                                                   onclick="return confirm('¿Está seguro de eliminar este libro?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php elseif (isset($resultados_busqueda) && empty($resultados_busqueda)): ?>
                <div class="card-profesional fade-in">
                    <div class="estado-vacio">
                        <i class="fas fa-search"></i>
                        <h5>No se encontraron resultados</h5>
                        <p>Intenta con otros términos de búsqueda</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Contenido Principal -->
                <div class="grid-principal">
                    <!-- Formulario -->
                    <div class="fade-in">
                        <div class="card-profesional">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas <?php echo $modoEdicion ? 'fa-edit' : 'fa-plus-circle'; ?>"></i>
                                    <?php echo $modoEdicion ? 'Editar Libro' : 'Agregar Nuevo Libro'; ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php 
                                if ($modoEdicion && $libroEditar) {
                                    echo FormHandler::mostrarFormularioLibro($libroEditar);
                                } else {
                                    echo FormHandler::mostrarFormularioLibro();
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Catálogo -->
                    <div class="fade-in">
                        <div class="card-profesional">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-books"></i>
                                    Catálogo de Libros
                                    <span class="badge bg-primary"><?php echo $total_libros; ?></span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if ($total_libros > 0): ?>
                                <div class="table-responsive">
                                    <table class="table-profesional">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Autor</th>
                                                <th>Categoría</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($biblioteca->getLibros() as $libro): ?>
                                            <tr>
                                                <td class="fw-bold"><?php echo $libro->getTitulo(); ?></td>
                                                <td class="text-secondary"><?php echo $libro->getAutor(); ?></td>
                                                <td>
                                                    <span class="badge bg-light text-dark"><?php echo $libro->getCategoria(); ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($libro->getDisponible()): ?>
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check"></i> Disponible
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-clock"></i> Prestado
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="acciones-tabla">
                                                        <a href="?editar=<?php echo $libro->getId(); ?>" class="btn btn-outline btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <?php if ($libro->getDisponible()): ?>
                                                            <a href="?prestar=<?php echo $libro->getId(); ?>" class="btn btn-success btn-sm">
                                                                <i class="fas fa-hand-holding"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="?devolver=<?php echo $libro->getId(); ?>" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-undo"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="?eliminar=<?php echo $libro->getId(); ?>" class="btn btn-danger btn-sm" 
                                                           onclick="return confirm('¿Está seguro de eliminar este libro?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <div class="estado-vacio">
                                    <i class="fas fa-book-open"></i>
                                    <h5>No hay libros en el catálogo</h5>
                                    <p>Comienza agregando tu primer libro</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>