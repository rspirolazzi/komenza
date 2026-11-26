// Horarios de reserva de una sucursal para una fecha, con el mismo criterio que la web
export interface DeliveryTime {
  start_day: string;
  start_hour: string;
  start_min: string;
  end_day: string;
  end_hour: string;
  end_min: string;
}

const INTERVAL_MINUTES = 30;

const toMinutes = (hour: string, minute: string) => Number(hour) * 60 + Number(minute);

const pad = (value: number) => String(value).padStart(2, '0');

// "YYYY-MM-DD" se interpretaría como UTC y en Argentina daría el día anterior
const getWeekDay = (date: string) => {
  const [year, month, day] = date.split('-').map(Number);
  return new Date(year, month - 1, day).getDay();
};

export function getAvailableHours(
  store: { delivery_times?: DeliveryTime[] } | undefined,
  date: string | undefined
): string[] {
  if (!store?.delivery_times || !date) return [];

  const weekDay = getWeekDay(date);
  const slots = new Set<number>();

  store.delivery_times
    .filter((time) => weekDay >= Number(time.start_day) && weekDay <= Number(time.end_day))
    .forEach((time) => {
      const end = toMinutes(time.end_hour, time.end_min);
      for (let slot = toMinutes(time.start_hour, time.start_min); slot <= end; slot += INTERVAL_MINUTES) {
        slots.add(slot);
      }
    });

  return Array.from(slots)
    .sort((a, b) => a - b)
    .map((slot) => `${pad(Math.floor(slot / 60))}:${pad(slot % 60)}`);
}
