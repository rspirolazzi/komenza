import React, { InputHTMLAttributes } from 'react';

interface InputProps extends InputHTMLAttributes<HTMLInputElement> {

}

const Input: React.FC<InputProps> = ({ ...rest }) => (
  <input {...rest} />
);

export default Input;
