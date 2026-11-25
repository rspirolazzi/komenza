import React from 'react';

interface DatePickerProps extends React.InputHTMLAttributes<HTMLInputElement> {
  value: string;
  onChange: (date: string) => void;
}

const DatePicker: React.FC<DatePickerProps> = ({ value, onChange, ...rest }) => (
  <input
    type="date"
    value={value}
    onChange={e => onChange(e.target.value)}
    {...rest}
  />
);

export default DatePicker;
