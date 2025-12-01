import React, { useEffect, useState } from "react";

export default function Home() {
  const tracks = [
    { id: 1, title: "Baile Inolvidable", artist: "Bad Bunny", cover: "/images/Bad-Bunny-Logo.png" },
    { id: 2, title: "TQM", artist: "Fuerza Regida", cover: "/images/imagen2.webp" },
    { id: 3, title: "Sunflower", artist: "Post Malone", cover: "/images/ima3.webp" },
    { id: 4, title: "Hasta Que Dios Diga", artist: "Anuel AA", cover: "/images/img4.jpg" },
    { id: 5, title: "Viva la Vida", artist: "Codplay", cover: "/images/img5.jpg" },
    { id: 6, title: "Locked Out Of heaven", artist: "Bruno Mars", cover: "/images/img6.png" },
  ];

  const [library, setLibrary] = useState([]);

  useEffect(() => {
    const data = JSON.parse(localStorage.getItem("library") || "[]");
    setLibrary(data);
  }, []);

  // Guardar cambios en localStorage
  const updateLibrary = (newLibrary) => {
    setLibrary(newLibrary);
    localStorage.setItem("library", JSON.stringify(newLibrary));
  };

  const toggleTrack = (track) => {
    const exists = library.find((t) => t.id === track.id);
    if (exists) {
      // Eliminar de lla biblioteca
      const newLibrary = library.filter((t) => t.id !== track.id);
      updateLibrary(newLibrary);
    } else {
      // Agregar a la bliblioteca
      const newLibrary = [...library, track];
      updateLibrary(newLibrary);
    }
  };

  return (
    <div>
      <h1 className="mb-4">Descubre Música</h1>
      <div className="row">
        {tracks.map((t) => {
          const exists = library.find((song) => song.id === t.id);
          return (
            <div className="col-md-4 mb-3" key={t.id}>
              <div className="card h-100 shadow-sm">
                <img
                  src={t.cover}
                  alt={t.title}
                  className="card-img-top"
                  style={{ height: "400px", objectFit: "cover" }}
                />
                <div className="card-body text-center">
                  <h5 className="card-title">{t.title}</h5>
                  <p className="card-text text-muted">{t.artist}</p>
                  <button
                    className={`btn ${exists ? "btn-danger" : "btn-success"} btn-sm`}
                    onClick={() => toggleTrack(t)}
                  >
                    {exists ? "Eliminar de Biblioteca" : "Guardar en Biblioteca"}
                  </button>
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
