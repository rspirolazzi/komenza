import React, { FormHTMLAttributes, ReactNode } from 'react';

interface FormProps extends FormHTMLAttributes<HTMLFormElement> {
  children: ReactNode;
}

const Form: React.FC<FormProps> = ({ children, ...props }) => (
  <form {...props}>{children}</form>
);

export default Form;

interface FormErrorsProps {
  formErrors: Record<string, string>;
}

export const FormErrors: React.FC<FormErrorsProps> = ({ formErrors }) => (
  <div className='formErrors'>
    {Object.keys(formErrors).map((fieldName, i) =>
      formErrors[fieldName].length > 0 ? (
        <p key={i}>{fieldName} {formErrors[fieldName]}</p>
      ) : null
    )}
  </div>
);
