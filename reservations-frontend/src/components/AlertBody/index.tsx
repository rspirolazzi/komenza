import React from 'react';

interface AlertBodyProps {
  children: React.ReactNode;
}

const AlertBody: React.FC<AlertBodyProps> = ({ children }) => (
  <div className="alert-body">{children}</div>
);

export default AlertBody;
