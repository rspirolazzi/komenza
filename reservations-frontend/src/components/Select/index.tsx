import React, { SelectHTMLAttributes, ReactNode } from 'react';

interface SelectProps extends Omit<SelectHTMLAttributes<HTMLSelectElement>, 'onChange'> {
  handleValueChange: (value: string) => void;
  children: ReactNode;
  maxDays?: number;
}

const Select: React.FC<SelectProps> = ({ children, handleValueChange, maxDays, ...rest }) => (
  <select {...rest} onChange={e => handleValueChange(e.target.value)}>
    {children}
  </select>
);

export default Select;
