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
    <title>Bibliotech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-book me-2"></i>
                Bibliotech
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-primary"><?php echo $total_libros; ?></div>
                    <div class="stats-label">Total de Libros</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-success"><?php echo $libros_disponibles; ?></div>
                    <div class="stats-label">Disponibles</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-danger"><?php echo $libros_prestados; ?></div>
                    <div class="stats-label">Prestados</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number text-warning"><?php echo count($libros_por_categoria); ?></div>
                    <div class="stats-label">Categorías</div>
                </div>
            </div>
        </div>

        <!-- Búsqueda -->
        <div class="search-section">
            <h4 class="mb-3"><i class="fas fa-search me-2"></i>Buscar Libros</h4>
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="termino_busqueda" placeholder="Ingrese término de búsqueda..." required>
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
                    <button type="submit" name="buscar" class="btn btn-primary-custom w-100">
                        <i class="fas fa-search me-1"></i>Buscar
                    </button>
                </div>
            </form>
        </div>

        <!-- Resultados de Búsqueda -->
        <?php if (isset($resultados_busqueda) && !empty($resultados_busqueda)): ?>
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">Resultados de Búsqueda (<?php echo count($resultados_busqueda); ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
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
                            <tr>
                                <td><?php echo $libro->getTitulo(); ?></td>
                                <td><?php echo $libro->getAutor(); ?></td>
                                <td><?php echo $libro->getCategoria(); ?></td>
                                <td><?php echo $libro->getIsbn(); ?></td>
                                <td>
                                    <span class="badge <?php echo $libro->getDisponible() ? 'badge-disponible' : 'badge-prestado'; ?>">
                                        <?php echo $libro->getDisponible() ? 'Disponible' : 'Prestado'; ?>
                                    </span>
                                </td>
                                <td class="book-actions">
                                    <a href="?editar=<?php echo $libro->getId(); ?>" class="btn btn-sm btn-edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <?php if ($libro->getDisponible()): ?>
                                        <a href="?prestar=<?php echo $libro->getId(); ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-hand-holding"></i> Prestar
                                        </a>
                                    <?php else: ?>
                                        <a href="?devolver=<?php echo $libro->getId(); ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-undo"></i> Devolver
                                        </a>
                                    <?php endif; ?>
                                    <a href="?eliminar=<?php echo $libro->getId(); ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('¿Está seguro de eliminar este libro?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php elseif (isset($resultados_busqueda) && empty($resultados_busqueda)): ?>
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>No se encontraron resultados para la búsqueda.
        </div>
        <?php endif; ?>

        <!-- Formulario para Agregar/Editar Libro -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas <?php echo $modoEdicion ? 'fa-edit' : 'fa-plus-circle'; ?> me-2"></i>
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

            <!-- Lista de Libros -->
            <div class="col-lg-6">
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-book me-2"></i>
                            Catálogo de Libros (<?php echo $total_libros; ?>)
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($total_libros > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-custom">
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
                                        <td><?php echo $libro->getTitulo(); ?></td>
                                        <td><?php echo $libro->getAutor(); ?></td>
                                        <td><?php echo $libro->getCategoria(); ?></td>
                                        <td>
                                            <span class="badge <?php echo $libro->getDisponible() ? 'badge-disponible' : 'badge-prestado'; ?>">
                                                <?php echo $libro->getDisponible() ? 'Disponible' : 'Prestado'; ?>
                                            </span>
                                        </td>
                                        <td class="book-actions">
                                            <a href="?editar=<?php echo $libro->getId(); ?>" class="btn btn-sm btn-edit" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($libro->getDisponible()): ?>
                                                <a href="?prestar=<?php echo $libro->getId(); ?>" class="btn btn-sm btn-success" title="Prestar">
                                                    <i class="fas fa-hand-holding"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="?devolver=<?php echo $libro->getId(); ?>" class="btn btn-sm btn-warning" title="Devolver">
                                                    <i class="fas fa-undo"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="?eliminar=<?php echo $libro->getId(); ?>" class="btn btn-sm btn-danger" 
                                               onclick="return confirm('¿Está seguro de eliminar este libro?')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay libros en el catálogo</h5>
                            <p class="text-muted">Comienza agregando tu primer libro usando el formulario.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>