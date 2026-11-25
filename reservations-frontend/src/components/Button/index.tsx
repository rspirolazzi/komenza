import React from 'react';
import { Button as BTButton, ButtonProps } from 'react-bootstrap';

const Button: React.FC<ButtonProps> = ({ children, ...rest }) => (
  <BTButton {...rest}>{children}</BTButton>
);

export default Button;
