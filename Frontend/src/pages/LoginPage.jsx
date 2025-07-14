import React, { useState } from 'react';
import axiosInstance from '../api/axiosInstance';
import { useAuth } from '../auth/AuthContext.js';
import { useNavigate } from 'react-router-dom';

const LoginPage = () => {
  const { login } = useAuth();
  const navigate = useNavigate();

  const [user, setUser] = useState('');
  const [pass, setPass] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    try {
      const res = await axiosInstance.post('/login', { correo: user, contrasena: pass });
      console.log(res);
      const token = res.data.token;
      login(token);
      navigate('/tareas');
    } catch {
      setError('Credenciales inválidas');
    }
  };

  return (
    <div className="flex justify-center items-center h-screen bg-login">
      <form
        onSubmit={handleSubmit}
        className="login-container"
      >
        <h2 className="login-title">Iniciar sesión</h2>
        {error && <p className="error-alert">{error}</p>}
        <input
          type="text"
          placeholder="Usuario"
          value={user}
          onChange={(e) => setUser(e.target.value)}
          className="input-form-login"
          required
        />
        <input
          type="password"
          placeholder="Contraseña"
          value={pass}
          onChange={(e) => setPass(e.target.value)}
          className="input-form-login"
          required
        />
        <button
          type="submit"
          className="button-form-login"
        >
          Iniciar Sesión
        </button>
        <p className="toggle-auth" onClick={() => navigate('/register')}>
          ¿No tienes cuenta? Regístrate
        </p>
      </form>
    </div>
  );
};

export default LoginPage;
