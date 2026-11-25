import _ from 'lodash';
import moment from 'moment';

export const getRangeHoursFromStore = (store: any, selectedDate: string): { dataDay: string[]; dataNight: string[] } => {
    let result = { dataDay: [], dataNight: [] };
    if (store && store.delivery_times && store.delivery_times.length > 0 && selectedDate) {
        var day = moment(selectedDate).toDate().getDay();
        var times = store.delivery_times.filter(function (time: any) {
            return day >= parseInt(time.start_day, 0) && day <= parseInt(time.end_day, 0);
        });
        times.forEach((time: any) => {
            let startDate = `${time.start_hour}:${time.start_min}`;
            let endDate = `${time.end_hour}:${time.end_min}`;
            let range = getRangeHours(startDate, endDate, 30);
            range.forEach((_range: string) => {
                try {
                    let v = parseInt(_range.split(' - ').shift()?.split(':').shift() || '0', 0);
                    if (v <= 15) {
                        if (_.findIndex(result.dataDay, (e) => e === _range) === -1) {
                            result.dataDay.push(_range);
                        }
                    } else {
                        if (_.findIndex(result.dataNight, (e) => e === _range) === -1) {
                            result.dataNight.push(_range);
                        }
                    }
                } catch (e) {
                    console.error('Error parseando horario', e);
                }
            });
        });
    }
    return result;
};

export function getRangeHours(start: string, end: string, interval: number): string[] {
    function getMinutes(time): number {
        var a = time.split(':').map(Number);
        return a[0] * 60 + a[1];
    }

    function getTime(m): string {
        var h = m / 60 | 0;
        m %= 60;
        return h + ':' + (m < 10 ? '0' + m : m);
    }

    var r: string[] = [],
        startM = getMinutes(start),
        endM = getMinutes(end);

    while (startM + interval <= (endM + interval)) {
        r.push(getTime(startM));
        startM += interval;
    }
    return r;
}
