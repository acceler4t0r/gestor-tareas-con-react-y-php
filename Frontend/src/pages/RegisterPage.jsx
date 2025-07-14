import React, { useState } from 'react';
import axiosInstance from '../api/axiosInstance';
import { useNavigate } from 'react-router-dom';

const RegisterPage = () => {
  const navigate = useNavigate();

  const [correo, setCorreo] = useState('');
  const [contrasena, setContrasena] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const handleSubmitRegister = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    try {
      await axiosInstance.post('/usuario', { correo, contrasena });
      setSuccess('Usuario registrado correctamente');
      setTimeout(() => navigate('/login'), 1500);
    } catch (err) {
      setError(err.response?.data?.error || 'Error al registrar');
    }
  };

  return (
    <div className="flex justify-center items-center h-screen bg-login">
      <form onSubmit={handleSubmitRegister} className="login-container">
        <h2 className="login-title">Registro de Usuario</h2>
        {error && <p className="error-alert">{error}</p>}
        {success && <p className="success-alert">{success}</p>}
        <input
          type="email"
          placeholder="Correo"
          value={correo}
          onChange={(e) => setCorreo(e.target.value)}
          className="input-form-login"
          required
        />
        <input
          type="password"
          placeholder="Contraseña"
          value={contrasena}
          onChange={(e) => setContrasena(e.target.value)}
          className="input-form-login"
          required
        />
        <button type="submit" className="button-form-login">
          Registrarse
        </button>
        <p className="toggle-auth" onClick={() => navigate('/login')}>
          ¿Ya tienes cuenta? Inicia sesión
        </p>
      </form>
    </div>
  );
};

export default RegisterPage;
