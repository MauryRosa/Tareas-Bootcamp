import React, { useEffect, useState } from "react";

export default function Library() {
  const [library, setLibrary] = useState([]);

  useEffect(() => {
    const data = JSON.parse(localStorage.getItem("library") || "[]");
    setLibrary(data);
  }, []);

  return (
    <div>
      <h1>Mi Biblioteca</h1>
      {library.length === 0 ? (
        <div className="alert alert-info">
          Todavía no tienes canciones en tu biblioteca.
        </div>
      ) : (
        <div className="row mt-3">
          {library.map((t) => (
            <div className="col-md-4 mb-3" key={t.id}>
              <div className="card h-100 shadow-sm">
                <img
                  src={t.cover}
                  alt={t.title}
                  className="card-img-top"
                  style={{ height: "300px", objectFit: "cover" }}
                />
                <div className="card-body text-center">
                  <h5 className="card-title">{t.title}</h5>
                  <p className="card-text text-muted">{t.artist}</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
