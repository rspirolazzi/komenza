import React from 'react';

interface AlertProps {
  message: string;
  type?: 'success' | 'error' | 'info' | 'warning';
}

const Alert: React.FC<AlertProps> = ({ message, type = 'info' }) => (
  <div className={`alert alert-${type}`}>{message}</div>
);

export default Alert;
