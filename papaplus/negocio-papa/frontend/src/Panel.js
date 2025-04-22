import React, { useState, useEffect } from 'react';
import axios from 'axios';

const Panel = ({ token }) => {
  const [ordenes, setOrdenes] = useState([]);

  useEffect(() => {
    const fetchOrders = async () => {
      try {
        // Realizar la solicitud GET para obtener las órdenes del usuario
        const response = await axios.get('http://localhost/backend/index.php/orders', {
          headers: { Authorization: `Bearer ${token}` },
        });
        setOrdenes(response.data);
      } catch (error) {
        alert('Error al obtener las órdenes');
      }
    };

    fetchOrders();
  }, [token]);

  return (
    <div>
      <h2>Panel de Control</h2>
      <ul>
        {ordenes.map(orden => (
          <li key={orden.id}>Orden ID: {orden.id} - Total: {orden.total}</li>
        ))}
      </ul>
    </div>
  );
};

export default Panel;
