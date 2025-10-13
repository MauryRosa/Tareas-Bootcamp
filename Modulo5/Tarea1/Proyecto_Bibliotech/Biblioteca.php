<?php

class Libro {
    private $id;
    private $titulo;
    private $autor;
    private $categoria;
    private $isbn;
    private $anio_publicacion;
    private $editorial;
    private $disponible;
    private $fecha_prestamo;
    private $fecha_devolucion;

    public function __construct($id, $titulo, $autor, $categoria, $isbn, $anio_publicacion, $editorial) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->categoria = $categoria;
        $this->isbn = $isbn;
        $this->anio_publicacion = $anio_publicacion;
        $this->editorial = $editorial;
        $this->disponible = true;
        $this->fecha_prestamo = null;
        $this->fecha_devolucion = null;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitulo() { return $this->titulo; }
    public function getAutor() { return $this->autor; }
    public function getCategoria() { return $this->categoria; }
    public function getIsbn() { return $this->isbn; }
    public function getAnioPublicacion() { return $this->anio_publicacion; }
    public function getEditorial() { return $this->editorial; }
    public function getDisponible() { return $this->disponible; }
    public function getFechaPrestamo() { return $this->fecha_prestamo; }
    public function getFechaDevolucion() { return $this->fecha_devolucion; }

    // Setters
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setAutor($autor) { $this->autor = $autor; }
    public function setCategoria($categoria) { $this->categoria = $categoria; }
    public function setIsbn($isbn) { $this->isbn = $isbn; }
    public function setAnioPublicacion($anio_publicacion) { $this->anio_publicacion = $anio_publicacion; }
    public function setEditorial($editorial) { $this->editorial = $editorial; }

    // Métodos para préstamos
    public function prestar() {
        if ($this->disponible) {
            $this->disponible = false;
            $this->fecha_prestamo = date('Y-m-d');
            $this->fecha_devolucion = date('Y-m-d', strtotime('+15 days'));
            return true;
        }
        return false;
    }

    public function devolver() {
        $this->disponible = true;
        $this->fecha_prestamo = null;
        $this->fecha_devolucion = null;
    }

    public function estaDisponible() {
        return $this->disponible;
    }
}

class Biblioteca {
    private $libros;
    private $categorias;
    private $autores;

    public function __construct() {
        $this->libros = [];
        $this->categorias = ['Ficción', 'Ciencia', 'Historia', 'Biografía', 'Tecnología', 'Arte', 'Filosofía'];
        $this->autores = [];
    }

    // Métodos para gestión de libros
    public function agregarLibro($libro) {
        $this->libros[] = $libro;
        // Agregar autor si no existe
        if (!in_array($libro->getAutor(), $this->autores)) {
            $this->autores[] = $libro->getAutor();
        }
    }

    public function eliminarLibro($id) {
        foreach ($this->libros as $index => $libro) {
            if ($libro->getId() == $id) {
                unset($this->libros[$index]);
                $this->libros = array_values($this->libros); //Para renderizar bien el array
                return true;
            }
        }
        return false;
    }

    public function editarLibro($id, $datos) {
        foreach ($this->libros as $libro) {
            if ($libro->getId() == $id) {
                $libro->setTitulo($datos['titulo']);
                $libro->setAutor($datos['autor']);
                $libro->setCategoria($datos['categoria']);
                $libro->setIsbn($datos['isbn']);
                $libro->setAnioPublicacion($datos['anio_publicacion']);
                $libro->setEditorial($datos['editorial']);
                return true;
            }
        }
        return false;
    }

    // Métodos de búsqueda
    public function buscarPorTitulo($titulo) {
        $resultados = [];
        foreach ($this->libros as $libro) {
            if (stripos($libro->getTitulo(), $titulo) !== false) {
                $resultados[] = $libro;
            }
        }
        return $resultados;
    }

    public function buscarPorAutor($autor) {
        $resultados = [];
        foreach ($this->libros as $libro) {
            if (stripos($libro->getAutor(), $autor) !== false) {
                $resultados[] = $libro;
            }
        }
        return $resultados;
    }

    public function buscarPorCategoria($categoria) {
        $resultados = [];
        foreach ($this->libros as $libro) {
            if (stripos($libro->getCategoria(), $categoria) !== false) {
                $resultados[] = $libro;
            }
        }
        return $resultados;
    }

    public function buscarPorIsbn($isbn) {
        foreach ($this->libros as $libro) {
            if ($libro->getIsbn() == $isbn) {
                return $libro;
            }
        }
        return null;
    }

    // Métodos para préstamos
    public function prestarLibro($id) {
        foreach ($this->libros as $libro) {
            if ($libro->getId() == $id) {
                return $libro->prestar();
            }
        }
        return false;
    }

    public function devolverLibro($id) {
        foreach ($this->libros as $libro) {
            if ($libro->getId() == $id) {
                $libro->devolver();
                return true;
            }
        }
        return false;
    }

    // Getters
    public function getLibros() { return $this->libros; }
    public function getCategorias() { return $this->categorias; }
    public function getAutores() { return $this->autores; }

    // Métodos de estadísticas
    public function getTotalLibros() {
        return count($this->libros);
    }

    public function getLibrosDisponibles() {
        $disponibles = 0;
        foreach ($this->libros as $libro) {
            if ($libro->getDisponible()) {
                $disponibles++;
            }
        }
        return $disponibles;
    }

    public function getLibrosPrestados() {
        return $this->getTotalLibros() - $this->getLibrosDisponibles();
    }

    public function getLibrosPorCategoria() {
        $categorias = [];
        foreach ($this->libros as $libro) {
            $categoria = $libro->getCategoria();
            if (!isset($categorias[$categoria])) {
                $categorias[$categoria] = 0;
            }
            $categorias[$categoria]++;
        }
        return $categorias;
    }
}

// Clase para manejar la persistencia de datos
class GestorDatos {
    private static $instancia;

    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    public function guardarBiblioteca($biblioteca) {
        $datos = [
            'libros' => [],
            'autores' => $biblioteca->getAutores()
        ];

        foreach ($biblioteca->getLibros() as $libro) {
            $datos['libros'][] = [
                'id' => $libro->getId(),
                'titulo' => $libro->getTitulo(),
                'autor' => $libro->getAutor(),
                'categoria' => $libro->getCategoria(),
                'isbn' => $libro->getIsbn(),
                'anio_publicacion' => $libro->getAnioPublicacion(),
                'editorial' => $libro->getEditorial(),
                'disponible' => $libro->getDisponible(),
                'fecha_prestamo' => $libro->getFechaPrestamo(),
                'fecha_devolucion' => $libro->getFechaDevolucion()
            ];
        }

        $_SESSION['biblioteca_data'] = $datos;
    }

    public function cargarBiblioteca($biblioteca) {
        if (!isset($_SESSION['biblioteca_data'])) {
            return false;
        }

        $datos = $_SESSION['biblioteca_data'];
        
        foreach ($datos['libros'] as $libroData) {
            $libro = new Libro(
                $libroData['id'],
                $libroData['titulo'],
                $libroData['autor'],
                $libroData['categoria'],
                $libroData['isbn'],
                $libroData['anio_publicacion'],
                $libroData['editorial']
            );
            
            // Restaurar estado de préstamo
            if (!$libroData['disponible']) {
                $libro->prestar();
            }
            
            $biblioteca->agregarLibro($libro);
        }

        return true;
    }
}

//Clase para manejar formularios y poder edditar libros
class FormHandler {
    public static function mostrarFormularioLibro($libro = null) {
        $esEdicion = $libro !== null;
        $id = $esEdicion ? $libro->getId() : '';
        $titulo = $esEdicion ? $libro->getTitulo() : '';
        $autor = $esEdicion ? $libro->getAutor() : '';
        $categoria = $esEdicion ? $libro->getCategoria() : '';
        $isbn = $esEdicion ? $libro->getIsbn() : '';
        $anio_publicacion = $esEdicion ? $libro->getAnioPublicacion() : '';
        $editorial = $esEdicion ? $libro->getEditorial() : '';
        
        $accion = $esEdicion ? 'editar_libro' : 'crear_libro';
        $textoBoton = $esEdicion ? 'Actualizar Libro' : 'Guardar Libro';
        $icono = $esEdicion ? 'fa-edit' : 'fa-save';
        
        return "
        <form method='POST'>
            <input type='hidden' name='{$accion}' value='1'>
            " . ($esEdicion ? "<input type='hidden' name='libro_id' value='{$id}'>" : "") . "
            
            <div class='mb-3'>
                <label class='form-label'>Título</label>
                <input type='text' class='form-control' name='titulo' value='{$titulo}' required>
            </div>
            <div class='mb-3'>
                <label class='form-label'>Autor</label>
                <input type='text' class='form-control' name='autor' value='{$autor}' required>
            </div>
            <div class='mb-3'>
                <label class='form-label'>Categoría</label>
                <select class='form-select' name='categoria' required>
                    <option value=''>Seleccione una categoría</option>
                    <option value='Ficción' " . ($categoria == 'Ficción' ? 'selected' : '') . ">Ficción</option>
                    <option value='Ciencia' " . ($categoria == 'Ciencia' ? 'selected' : '') . ">Ciencia</option>
                    <option value='Historia' " . ($categoria == 'Historia' ? 'selected' : '') . ">Historia</option>
                    <option value='Biografía' " . ($categoria == 'Biografía' ? 'selected' : '') . ">Biografía</option>
                    <option value='Tecnología' " . ($categoria == 'Tecnología' ? 'selected' : '') . ">Tecnología</option>
                    <option value='Arte' " . ($categoria == 'Arte' ? 'selected' : '') . ">Arte</option>
                    <option value='Filosofía' " . ($categoria == 'Filosofía' ? 'selected' : '') . ">Filosofía</option>
                </select>
            </div>
            <div class='row'>
                <div class='col-md-6 mb-3'>
                    <label class='form-label'>ISBN</label>
                    <input type='text' class='form-control' name='isbn' value='{$isbn}' required>
                </div>
                <div class='col-md-6 mb-3'>
                    <label class='form-label'>Año Publicación</label>
                    <input type='number' class='form-control' name='anio_publicacion' value='{$anio_publicacion}' min='1000' max='" . date('Y') . "' required>
                </div>
            </div>
            <div class='mb-3'>
                <label class='form-label'>Editorial</label>
                <input type='text' class='form-control' name='editorial' value='{$editorial}' required>
            </div>
            <div class='d-flex gap-2'>
                " . ($esEdicion ? "<a href='index.php' class='btn btn-secondary'>Cancelar</a>" : "") . "
                <button type='submit' class='btn btn-primary-custom flex-grow-1'>
                    <i class='fas {$icono} me-2'></i>{$textoBoton}
                </button>
            </div>
        </form>
        ";
    }
}
?>
