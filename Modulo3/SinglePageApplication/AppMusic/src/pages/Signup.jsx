import React, { useState } from "react";

export default function Signup() {
  const [form, setForm] = useState({ name: "", email: "", password: "" });
  const [errors, setErrors] = useState({});

  const handleChange = e => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const validate = () => {
    const errs = {};
    if (!form.name.trim()) errs.name = "El nombre es obligatorio";
    if (!form.email.includes("@")) errs.email = "Correo inválido";
    if (form.password.length < 6) errs.password = "Mínimo 6 caracteres";
    return errs;
  };

  const handleSubmit = e => {
    e.preventDefault();
    const errs = validate();
    setErrors(errs);
    if (Object.keys(errs).length === 0) {
      alert("Has ingresado a Kodigo Music Premium");
    }
  };

  return (
    <div className="col-md-6 mx-auto">
      <h1>Premium</h1>
      <form onSubmit={handleSubmit} noValidate>
        <div className="mb-3">
          <label className="form-label">Nombre</label>
          <input name="name" value={form.name} onChange={handleChange} className="form-control" />
          {errors.name && <div className="text-danger">{errors.name}</div>}
        </div>
        <div className="mb-3">
          <label className="form-label">Correo</label>
          <input name="email" type="email" value={form.email} onChange={handleChange} className="form-control" />
          {errors.email && <div className="text-danger">{errors.email}</div>}
        </div>
        <div className="mb-3">
          <label className="form-label">Contraseña</label>
          <input name="password" type="password" value={form.password} onChange={handleChange} className="form-control" />
          {errors.password && <div className="text-danger">{errors.password}</div>}
        </div>
        <button className="btn btn-success">Crear cuenta</button>
      </form>
    </div>
  );
}
