import React from 'react';

const Alert = ({ type = 'success', message, onClose }) => {
  const colors = {
    success: 'bg-green-500',
    error: 'bg-red-500',
  };

  if (!message) return null;

  return (
    <div
      className={`${colors[type]} fixed top-5 right-5 p-4 rounded text-white shadow-lg`}
      role="alert"
    >
      <div className="flex justify-between items-center">
        <span>{message}</span>
        <button onClick={onClose} className="ml-4 font-bold">X</button>
      </div>
    </div>
  );
};

export default Alert;
