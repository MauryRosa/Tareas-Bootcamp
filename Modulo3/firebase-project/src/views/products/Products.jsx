import { collection, getDocs, addDoc, updateDoc, deleteDoc, doc } from "firebase/firestore";
import { signOut } from "firebase/auth"; // Importar signOut
import { db, auth } from "../../repositories/firebase/config"; // Asegúrate de importar auth
import { useEffect, useState } from "react";
import { useNavigate } from "react-router"; // Importar useNavigate para redirección

export const Products = () => {
  const [products, setProducts] = useState([]);
  const [form, setForm] = useState({ name: "", price: "", stock: "" });
  const [editingId, setEditingId] = useState(null);
  const [message, setMessage] = useState("");
  const navigate = useNavigate(); // Inicializar useNavigate

  // Obtener productos
  const getProducts = async () => {
    try {
      const querySnapshot = await getDocs(collection(db, "products"));
      const productsData = querySnapshot.docs.map((doc) => ({
        id: doc.id,
        ...doc.data(),
      }));
      setProducts(productsData);
    } catch (err) {
      console.error("Error obteniendo productos:", err);
      setMessage("Error cargando productos");
    }
  };

  // Cerrar sesión
  const handleLogout = async () => {
    try {
      await signOut(auth);
      navigate("/login"); // Redirige a la página de login
    } catch (error) {
      console.error('Error al cerrar sesión:', error);
      setMessage("Error al cerrar sesión");
    }
  };

  // Agregar producto
  const addProduct = async (e) => {
    e.preventDefault();
    if (!form.name || !form.price || !form.stock) {
      setMessage("Todos los campos son obligatorios");
      return;
    }
    if (Number(form.price) < 0 || Number(form.stock) < 0) {
      setMessage("Precio y stock deben ser positivos");
      return;
    }

    try {
      await addDoc(collection(db, "products"), {
        name: form.name,
        price: Number(form.price),
        stock: Number(form.stock),
      });
      setForm({ name: "", price: "", stock: "" });
      setMessage("Producto agregado correctamente");
      getProducts();
    } catch (err) {
      console.error("Error agregando producto:", err);
      setMessage("Error agregando producto");
    }
  };

  // Editar producto
  const startEditing = (product) => {
    setEditingId(product.id);
    setForm({
      name: product.name,
      price: product.price,
      stock: product.stock,
    });
  };

  const updateProduct = async (e) => {
    e.preventDefault();
    if (!form.name || !form.price || !form.stock) {
      setMessage("Todos los campos son obligatorios");
      return;
    }

    try {
      const productRef = doc(db, "products", editingId);
      await updateDoc(productRef, {
        name: form.name,
        price: Number(form.price),
        stock: Number(form.stock),
      });
      setForm({ name: "", price: "", stock: "" });
      setEditingId(null);
      setMessage("Producto actualizado correctamente");
      getProducts();
    } catch (err) {
      console.error("Error actualizando producto:", err);
      setMessage("Error actualizando producto");
    }
  };

  // Eliminar producto
  const deleteProduct = async (id) => {
    try {
      const productRef = doc(db, "products", id);
      await deleteDoc(productRef);
      setMessage("Producto eliminado correctamente");
      getProducts();
    } catch (err) {
      console.error("Error eliminando producto:", err);
      setMessage("Error eliminando producto");
    }
  };

  useEffect(() => {
    getProducts();
  }, []);

  return (
    <div className="container mt-5">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2 className="text-center mb-0">Gestión de Productos</h2>
        <button 
          className="btn btn-outline-danger"
          onClick={handleLogout}
        >
          Cerrar Sesión
        </button>
      </div>

      {message && <div className="alert alert-info">{message}</div>}

      {/* Formulario */}
      <form
        onSubmit={editingId ? updateProduct : addProduct}
        className="row g-3 mb-4 justify-content-center"
      >
        <div className="col-md-3">
          <input
            type="text"
            className="form-control"
            placeholder="Nombre"
            value={form.name}
            onChange={(e) => setForm({ ...form, name: e.target.value })}
          />
        </div>
        <div className="col-md-2">
          <input
            type="number"
            className="form-control"
            placeholder="Precio"
            value={form.price}
            onChange={(e) => setForm({ ...form, price: e.target.value })}
          />
        </div>
        <div className="col-md-2">
          <input
            type="number"
            className="form-control"
            placeholder="Stock"
            value={form.stock}
            onChange={(e) => setForm({ ...form, stock: e.target.value })}
          />
        </div>
        <div className="col-md-2 d-flex gap-2">
          <button type="submit" className="btn btn-success flex-grow-1">
            {editingId ? "Actualizar" : "Agregar"}
          </button>
          {editingId && (
            <button
              type="button"
              className="btn btn-secondary flex-grow-1"
              onClick={() => {
                setEditingId(null);
                setForm({ name: "", price: "", stock: "" });
              }}
            >
              Cancelar
            </button>
          )}
        </div>
      </form>

      {/* Lista de productos */}
      <div className="row">
        {products.length > 0 ? (
          products.map((p) => (
            <div key={p.id} className="col-md-4 mb-3">
              <div className="card shadow-sm h-100">
                <div className="card-body">
                  <h5 className="card-title">{p.name}</h5>
                  <p className="card-text">
                    <strong>Precio:</strong> ${p.price} <br />
                    <strong>Stock:</strong> {p.stock}
                  </p>
                  <div className="d-flex justify-content-between">
                    <button
                      className="btn btn-primary btn-sm"
                      onClick={() => startEditing(p)}
                    >
                      Editar
                    </button>
                    <button
                      className="btn btn-danger btn-sm"
                      onClick={() => deleteProduct(p.id)}
                    >
                      Eliminar
                    </button>
                  </div>
                </div>
              </div>
            </div>
          ))
        ) : (
          <p className="text-center">No hay productos registrados</p>
        )}
      </div>
    </div>
  );
};