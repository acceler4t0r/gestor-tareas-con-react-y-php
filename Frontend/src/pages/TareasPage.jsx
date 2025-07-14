import React, { useState, useEffect } from 'react';
import axiosInstance from '../api/axiosInstance.js';
import ModalTarea  from '../components/ModalTarea.js';
import PencilIcon from '../components/icons/PencilIcon.js';
import TrashIcon from '../components/icons/TrashIcon.js';
import Logout from '../components/icons/Logout.js';

const TareasPage = () => {
  const [tareas, setTareas] = useState([]);
  const [loading, setLoading] = useState(false);
  const [modalOpen, setModalOpen] = useState(false);
  const [editTarea, setEditTarea] = useState(null);
  const [ciudad, setCiudad] = useState('');
  const [clima, setClima] = useState('');
  const [hora, setHora] = useState(new Date().toLocaleTimeString());

  const fetchTareas = async () => {
    setLoading(true);
    try {
      const res = await axiosInstance.get('/tareas');
      setTareas(res.data);
    } catch (err) {
      console.error(err);
    }
    setLoading(false);
  };
  const obtenerCiudadYClima = async () => {
    try {
      const ipRes = await fetch('https://ipapi.co/json/');
      const ipData = await ipRes.json();

      const lat = ipData.latitude;
      const lon = ipData.longitude;
      const city = ipData.city;

      setCiudad(city);

      const apiKey = 'dc6001ce6e8b083dac755bde31e7242b';
      const climaRes = await fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=es`);
      const climaData = await climaRes.json();

      const descripcion = climaData.weather[0].description;
      const temperatura = climaData.main.temp;

      setClima(`${descripcion} (${temperatura}°C)`);
    } catch (error) {
      console.error("Error al obtener ciudad o clima:", error);
    }
  };
  const logout = async () => {
      localStorage.removeItem('token');
      window.location.href = '/login';
  };


  useEffect(() => {
    fetchTareas();
    obtenerCiudadYClima();

    const interval = setInterval(() => {
      setHora(new Date().toLocaleTimeString());
    }, 1000);

    return () => clearInterval(interval);
  }, []);

  const handleAddClick = () => {
    setEditTarea(null);
    setModalOpen(true);
  };

  const handleEditClick = (tarea) => {
    setEditTarea(tarea);
    setModalOpen(true);
  };
  const handleDeleteClick = async (tarea) => {
    const confDelete = window.confirm("¿Deseas eliminar la tarea?");
    if (!confDelete) return;

    try {
      setLoading(true);
      await axiosInstance.delete(`/tareas/${tarea.id}`);  // <-- borrar tarea por id
      await fetchTareas(); // refrescar la lista
    } catch (error) {
      console.error("Error al eliminar tarea:", error);
    } finally {
      setLoading(false);
    }
  };

  const handleModalClose = () => {
    setModalOpen(false);
  };

  const handleModalSubmit = async (data) => {
    try {
      if (editTarea) {
        await axiosInstance.put(`/tareas/${editTarea.id}`, data);
      } else {
        await axiosInstance.post('/tareas', data);
      }
      fetchTareas();
      setModalOpen(false);
    } catch (error) {
      console.error(error);
    }
  };
  return (
    <>
      <div className="header-dashboard">
        <h2 className="app-name">Brangus Tareas</h2>
        <div className="info-header">
          <p><strong>Ciudad:</strong> {ciudad || 'Cargando...'}</p>
          <p><strong>Clima:</strong> {clima || 'Cargando...'}</p>
          <p><strong>Hora:</strong> {hora}</p>
          <button className="logout" onClick={logout}>
              Cerrar sesión
              <Logout className="">
              </Logout>
            </button>
        </div>
      </div>
      <div className="container-tareas">
        <div className="content-tareas">
          <h1 className="tareas-title">Tareas</h1>
          <div className='container-button-add'>
              <button
              onClick={handleAddClick}
              className="btn-agregar-tarea"
              >
              + Agregar Tarea
              </button>
          </div>
        </div>
        {loading && <p>Cargando tareas...</p>}

        {!loading && (
          
          <div className="content-tareas">
            <table className="tabla-tareas">
              <thead className="tabla-head">
                <tr className="tabla-title">
                  <th className="">#</th>
                  <th className="">Titulo</th>
                  <th className="">Descripción</th>
                  <th className="">Estado</th>
                  <th className="">Ciudad</th>
                  <th className="">Fecha creación</th>
                  <th className="">Clima</th>
                  <th className="">Frase</th>
                  <th className="">Acciones</th>
                </tr>
              </thead>
              <tbody className="tabla-body">
                {tareas.map((tarea, idx) => (
                  <tr key={tarea.id} className="text-center">
                    <td className="">{idx + 1}</td>
                    <td className="">{tarea.titulo}</td>
                    <td className="">{tarea.descripcion}</td>
                    <td className="">{tarea.estado}</td>
                    <td className="">{tarea.ciudad}</td>
                    <td className="">{tarea.fecha_creacion}</td>
                    <td className="">{tarea.clima_actual}</td>
                    <td className="">{tarea.frase_motivacional}</td>
                    <td className="">
                      <button
                        onClick={() => handleEditClick(tarea)}
                        className="icon-tabla"
                      >
                        <PencilIcon className="icon">

                        </PencilIcon>
                      </button>
                      <button
                        onClick={() => handleDeleteClick(tarea)}
                        className="icon-tabla"
                      >
                        <TrashIcon className="icon">

                        </TrashIcon>
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        <ModalTarea
          isOpen={modalOpen}
          onClose={handleModalClose}
          onSubmit={handleModalSubmit}
          initialData={editTarea}
          ciudad={ciudad}
          clima={clima}
        />
      </div>
    </>
  );
};

export default TareasPage;
