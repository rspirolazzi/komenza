import React, { TextareaHTMLAttributes } from 'react';

interface TextAreaProps extends Omit<TextareaHTMLAttributes<HTMLTextAreaElement>, 'onChange'> {
  handleValueChange: (value: string) => void;
}

const TextArea: React.FC<TextAreaProps> = ({ handleValueChange, ...rest }) => (
  <textarea {...rest} onChange={e => handleValueChange(e.target.value)} />
);

export default TextArea;
