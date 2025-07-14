import React, { useRef } from 'react';

const ModalTarea = ({ isOpen, onClose, onSubmit, initialData, ciudad, clima }) => {
    const textareaRef = useRef(null);
    const [tareaTitulo, setTareaTitulo] = React.useState('');
    const [tareaDescripcion, setTareaDescripcion] = React.useState('');
    React.useEffect(() => {
      setTareaTitulo(initialData?.titulo || '');
      setTareaDescripcion(initialData?.descripcion || '');
    }, [initialData]);
    if (!isOpen) return null;
    console.log(initialData);

  const handleDescripcionChange = (e) => {
    setTareaDescripcion(e.target.value);

    const el = textareaRef.current;
    if (el) {
      el.style.height = 'auto';
      el.style.height = el.scrollHeight + 'px';
    }
  };
  const handleSubmit = async (e) => {
    e.preventDefault();
    onSubmit({
      titulo: tareaTitulo,
      descripcion: tareaDescripcion,
      estado: "pendiente",
      ciudad: ciudad,
      clima_actual: clima,
    });
  };


  return (
    <div className="modal-bg flex justify-center items-center">
      <div className="modal-form-content">
        <h2 className="modal-title">{initialData ? 'Editar Tarea' : 'Crear Tarea'}</h2>
        <form onSubmit={handleSubmit}>
          <div className="modal-form-input-content">
            <input
              type="text"
              placeholder="Titulo de la tarea"
              value={tareaTitulo}
              onChange={(e) => setTareaTitulo(e.target.value)}
              className="input-modal-content"
              required
            />
            <textarea
              ref={textareaRef}
              placeholder="Descripción de tarea"
              value={tareaDescripcion}
              onChange={handleDescripcionChange}
              className="input-modal-content"
              style={{ overflow: 'hidden', resize: 'none' }}
              required
            />
          </div>
          <div className="button-container">
            <button
              type="button"
              onClick={onClose}
              className="btn-close-modal"
            >
              Cancelar
            </button>
            <button type="submit" className="btn-accept-modal">
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ModalTarea;
