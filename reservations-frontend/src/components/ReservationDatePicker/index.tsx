import React from 'react';

interface ReservationDatePickerProps extends React.InputHTMLAttributes<HTMLInputElement> {
  value: string;
  onChange: (date: string) => void;
}

const ReservationDatePicker: React.FC<ReservationDatePickerProps> = ({ value, onChange, ...rest }) => (
  <input
    type="date"
    value={value}
    onChange={e => onChange(e.target.value)}
    {...rest}
  />
);

export default ReservationDatePicker;
