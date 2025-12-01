import { useState } from "react";
import { useNavigate } from "react-router";
import { signInWithEmailAndPassword, createUserWithEmailAndPassword } from "firebase/auth";
import { auth } from "../../repositories/firebase/config";

export const LoginComponent = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [message, setMessage] = useState("");
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    setIsLoading(true);
    setMessage("");
    
    try {
      // Intentar iniciar sesión
      await signInWithEmailAndPassword(auth, email, password);
      setMessage("¡Inicio de sesión exitoso!");
      navigate("/products");
    } catch (err) {
      console.error("Error de Firebase:", err.code, err.message);
      
      // Crear usuario solo si el error es específicamente que el usuario no existe
      if (err.code === "auth/user-not-found" || err.code === "auth/invalid-credential") {
        try {
          await createUserWithEmailAndPassword(auth, email, password);
          setMessage("¡Usuario registrado correctamente!");
          navigate("/products");
        } catch (regErr) {
          console.error("Error al registrar:", regErr.code, regErr.message);
          if (regErr.code === "auth/email-already-in-use") {
            setMessage("Este correo ya está registrado. Intenta con otra contraseña.");
          } else if (regErr.code === "auth/weak-password") {
            setMessage("La contraseña debe tener al menos 6 caracteres.");
          } else if (regErr.code === "auth/invalid-email") {
            setMessage("El formato del correo electrónico no es válido.");
          } else {
            setMessage("Error registrando usuario: " + regErr.message);
          }
        }
      } else if (err.code === "auth/wrong-password") {
        setMessage("Contraseña incorrecta. Intenta nuevamente.");
      } else if (err.code === "auth/invalid-email") {
        setMessage("El formato del correo electrónico no es válido.");
      } else {
        setMessage("Error: " + err.message);
      }
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div
      className="d-flex justify-content-center align-items-center vh-100"
      style={{
        background: "linear-gradient(135deg, #6a11cb 0%, #2575fc 100%)",
      }}
    >
      <div className="card shadow-lg p-5" style={{ minWidth: "380px", borderRadius: "20px" }}>
        <h2 className="text-center mb-4 text-white fw-bold">Iniciar Sesión / Registro</h2>

        {message && (
          <div className={`alert ${message.includes("éxito") ? "alert-success" : "alert-danger"}`}>
            {message}
          </div>
        )}

        <form onSubmit={handleLogin}>
          <div className="mb-3">
            <input
              type="email"
              className="form-control form-control-lg"
              placeholder="Correo electrónico"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              disabled={isLoading}
            />
          </div>
          <div className="mb-3">
            <input
              type="password"
              className="form-control form-control-lg"
              placeholder="Contraseña"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              disabled={isLoading}
              minLength={6}
            />
          </div>
          <button
            type="submit"
            className="btn btn-light btn-lg w-100 fw-bold"
            style={{ color: "#2575fc" }}
            disabled={isLoading}
          >
            {isLoading ? (
              <>
                <span className="spinner-border spinner-border-sm me-2" />
                Procesando...
              </>
            ) : (
              "Ingresar / Registrar"
            )}
          </button>
        </form>

        <p className="text-center mt-3 text-white-50" style={{ fontSize: "0.9rem" }}>
          Ingresa cualquier correo nuevo y contraseña (mínimo 6 caracteres) para registrarte automáticamente
        </p>
      </div>
    </div>
  );
};