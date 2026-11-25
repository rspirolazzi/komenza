export const onErrorToMessage = ({ response = {} }: { response?: any }): string => {
    const { message } = response.data || { message: 'Ocurrio un error inesperado. Por favor vuelva a intentarlo nuevamente' };
    return message;
};

export const splitReservationTime = (reservation_time: string): string => {
    if (reservation_time) {
        let reservation_time_arr = reservation_time.split(':');
        try {
            reservation_time = `${reservation_time_arr[0]}:${reservation_time_arr[1]}`;
        } catch (e) {
            console.error(e);
        }
    }
    return reservation_time;
};
