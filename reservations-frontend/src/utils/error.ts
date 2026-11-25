export interface ApiError {
  message: string;
  response?: { status?: number; data?: { message?: string } };
}

/** Deja del error solo datos serializables: Redux no debe guardar el AxiosError completo. */
export const toApiError = (error: any): ApiError => ({
  message: error?.message || 'Error inesperado',
  ...(error?.response && {
    response: {
      status: error.response.status,
      ...(error.response.data?.message && { data: { message: String(error.response.data.message) } }),
    },
  }),
});
