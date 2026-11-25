// Este archivo contiene funciones de validación para formularios

export function isRequired(value: any): boolean {
  return value !== undefined && value !== null && value !== '';
}

export function isEmail(value: string): boolean {
  return /^([\w.%+-]+)@([\w-]+\.)+([\w]{2,})$/i.test(value);
}

// Agrega aquí otras funciones de validación según sea necesario
