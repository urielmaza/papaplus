import React, { useState } from 'react';
import axios from 'axios';

const Login = ({ setToken }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleLogin = async () => {
    try {
      // Realizar la solicitud POST para autenticar al usuario
      const response = await axios.post('http://localhost/backend/index.php/login', { email, password });
      setToken(response.data.token);
    } catch (error) {
      alert('Error al iniciar sesión');
    }
  };

  return (
    <div>
      <h2>Iniciar Sesión</h2>
      <input 
        type="email" 
        value={email} 
        onChange={e => setEmail(e.target.value)} 
        placeholder="Correo electrónico" 
      />
      <input 
        type="password" 
        value={password} 
        onChange={e => setPassword(e.target.value)} 
        placeholder="Contraseña" 
      />
      <button onClick={handleLogin}>Iniciar Sesión</button>
    </div>
  );
};

export default Login;
