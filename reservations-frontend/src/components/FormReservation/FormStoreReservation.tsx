import { editReservation, saveReservation } from '@/store/reservation/thunks';
import { RootState } from '@/store/store';
import moment from 'moment';
import React from 'react';
import { Controller, useForm } from 'react-hook-form';
import { useDispatch, useSelector } from 'react-redux';
import Button from '../Button';
import Form from '../Form';
import Select from '../Select';
import TextArea from '../TextArea';

import { useAlert } from '@/context/AlertContext';
import _ from 'lodash';
import DatePicker from 'react-datepicker';
import { onErrorToMessage, splitReservationTime } from '../../utils/reservation';
import { getRangeHoursFromStore } from '../../utils/store';
import { Col, Row } from 'react-bootstrap';
import { Reservation } from '@/types/reservation';

const isGreaterThanToday = (reservation_date, reservation_time) => {
    let selectedDate = moment(moment(reservation_date).format('YYYY-MM-DD') + 'T' + reservation_time)
    let nowTime = moment(new Date())
    return (selectedDate.diff(nowTime) > 0)
}

interface FormReservationFields {
    reservation_date: string;
    reservation_time: string;
    store_id: string;
    people: string;
    comment: string;
}

interface FormStoreReservationProps {
    isEditable: boolean;
    handleRedirect: (token: string) => void;
    title: string;
    goBack?: () => void;
    reservation?: Reservation;
}

const FormStoreReservation: React.FC<FormStoreReservationProps> = ({ isEditable, handleRedirect, goBack, title, reservation: current }) => {
    const dispatch = useDispatch();
    const user = useSelector((state: RootState) => state.user && 'user' in state.user ? state.user.user : undefined);
    const repeatLastReservation = useSelector((state: RootState) => state.user && 'repeatLastReservation' in state.user ? state.user.repeatLastReservation : undefined);
    const unsavedReservation = useSelector((state: RootState) => state.reservation && 'unsavedReservation' in state.reservation ? state.reservation.unsavedReservation as any : undefined);
    const isFetching = useSelector((state: RootState) => state.reservation && 'isFetching' in state.reservation ? state.reservation.isFetching : false);
    const selectedBrand = useSelector((state: RootState) => state.general && 'selectedBrand' in state.general ? state.general.selectedBrand : undefined);
    const alertModal = useAlert();

    // RHF setup
    const initial = isEditable ? current : unsavedReservation;
    const { control, handleSubmit, setValue, watch, formState: { errors, isValid } } = useForm<FormReservationFields>({
        defaultValues: {
            reservation_date: initial?.reservation_date || '',
            reservation_time: splitReservationTime(initial?.reservation_time || ''),
            store_id: `${initial?.store_id || ''}`,
            people: `${initial?.people || ''}`,
            comment: initial?.comment || '',
        }
    });

    // Sincronizar con repeatLastReservation si cambia
    React.useEffect(() => {
        if (repeatLastReservation && !isEditable) {
            setValue('reservation_date', moment().format());
            setValue('reservation_time', splitReservationTime(repeatLastReservation.reservation_time));
            setValue('store_id', `${repeatLastReservation.store_id}`);
            setValue('people', `${repeatLastReservation.people}`);
            setValue('comment', repeatLastReservation.comment || '');
        }
    }, [repeatLastReservation, isEditable, setValue]);

    // Personas options
    const stores = selectedBrand && (selectedBrand as any).stores ? (selectedBrand as any).stores : [];
    const brandName = selectedBrand && (selectedBrand as any).shortName ? (selectedBrand as any).shortName : '';
    const storeSelected = _.find(stores, (e) => parseInt(e.id, 0) === parseInt(watch('store_id'), 0));
    const max_persons = storeSelected && storeSelected.max_persons ? storeSelected.max_persons : 0;
    let personsOptions = [<option key={0} value={''}></option>];
    for (let i = 1; i <= max_persons; i++) {
        personsOptions.push(<option key={i} value={i}>{i}</option>);
    }
    if (personsOptions.length > 1) {
        personsOptions.push(<option key={max_persons + 1} value={999}>Mas de {max_persons}</option>);
    }

    // Submit handler
    const onSubmit = (formData) => {
        const reservationDate = moment(formData.reservation_date).format('YYYY-MM-DD');
        const time = formData.reservation_time;
        const reservation = moment(reservationDate + ' ' + time).format('YYYY-MM-DD HH:mm:ss');
        const payload = {
            reservation_date: reservationDate,
            reservation_time: formData.reservation_time,
            reservation,
            store_id: formData.store_id,
            people: formData.people,
            comment: formData.comment,
            token: initial?.token,
        };
        const action = isEditable ? editReservation(payload) : saveReservation(payload);
        dispatch(action)
            .unwrap()
            .then((res) => {
                handleRedirect(res.token)
            })
            .catch((error) => alertModal('', onErrorToMessage(error)));
    };

    // RHF + custom Select/DatePicker/TextArea integration
    return (
        <Form method="post" onSubmit={handleSubmit(onSubmit)}>
            <div className="form-date-w6-agileits">
                <h5>{isEditable ? current?.user?.name : user?.name} {title}</h5>
            </div>
            <Row>
                <Col className="form-date-w3-agileits">
                    <label><i className="fa fa-map-marker" aria-hidden="true"></i> Sucursal:</label>
                    <Controller
                        name="store_id"
                        control={control}
                        rules={{ required: true }}
                        render={({ field }) => (
                            <Select
                                {...field}
                                onFocus={() => { }}
                                className="form-control"
                                handleValueChange={(store_id) => {
                                    field.onChange(store_id);
                                    setValue('reservation_time', '');
                                }}
                            >
                                <option value=""></option>
                                {stores.map((item, index) => <option key={index} value={item.id}>{`${brandName} - ${item.address}`}</option>)}
                            </Select>
                        )}
                    />
                    {errors.store_id && <div className="error-message">Campo requerido</div>}
                </Col>

                <Col className="form-date-w3-agileits">
                    <label><i className="fa fa-calendar-alt" aria-hidden="true"></i> Fecha:</label>
                    <Controller
                        name="reservation_date"
                        control={control}
                        rules={{ required: true }}
                        render={({ field }) => (
                            <DatePicker
                                className="form-control"
                                withPortal
                                dateFormat="dd-MM-yyyy"
                                placeholderText="Elegí una fecha"
                                locale="es"
                                showMonthDropdown
                                showYearDropdown
                                dropdownMode="select"
                                selected={field.value ? moment(field.value).toDate() : moment().toDate()}
                                onChange={date => field.onChange(date ? moment(date).format('YYYY-MM-DD') : '')}
                                openToDate={moment().toDate()}
                                minDate={moment().toDate()}
                                maxDate={moment().add(1, 'years').toDate()}
                            />

                        )}
                    />
                    {errors.reservation_date && <div className="error-message">Campo requerido</div>}
                </Col>
            </Row>
            <Row>
                <Col className="form-date-w3-agileits">
                    <label><i className="fa fa-clock" aria-hidden="true"></i> Horario:</label>
                    <Controller
                        name="reservation_time"
                        control={control}
                        rules={{ required: true }}
                        render={({ field }) => (
                            <SelectSchedule
                                onFocus={() => { }}
                                className="form-control"
                                name="reservation_time"
                                selectedDate={watch('reservation_date')}
                                store={storeSelected}
                                handleValueChange={field.onChange}
                                value={field.value}
                            />
                        )}
                    />
                    {errors.reservation_time && <div className="error-message">Campo requerido</div>}
                </Col>

                <Col className="form-date-w3-agileits">
                    <label><i className="fa fa-users" aria-hidden="true"></i> Personas:</label>
                    <Controller
                        name="people"
                        control={control}
                        rules={{ required: true }}
                        render={({ field }) => (
                            <Select
                                {...field}
                                onFocus={() => { }}
                                className="form-control"
                                handleValueChange={(people) => {
                                    if (Number(people) > max_persons) {
                                        field.onChange('');
                                        alertModal('', <p>Para obtener información por eventos o reservas de mas de {max_persons} personas, comunicate con la sucursal{storeSelected?.telephone ? ` al ${storeSelected.telephone}` : ''}</p>);
                                    } else {
                                        field.onChange(people);
                                    }
                                }}
                                value={field.value}
                            >
                                {personsOptions}
                            </Select>
                        )}
                    />
                    {errors.people && <div className="error-message">Campo requerido</div>}
                </Col>
            </Row>
            <Row>
                <Col className="form-date-w6-agileits">
                    <label><i className="fa fa-comment" aria-hidden="true"></i> Aclaraciones:</label>
                    <Controller
                        name="comment"
                        control={control}
                        render={({ field }) => (
                            <TextArea
                                {...field}
                                onFocus={() => { }}
                                className="form-control"
                                handleValueChange={field.onChange}
                            />
                        )}
                    />
                </Col>
            </Row>
            <div className="make">
                <Button type="submit" disabled={!isValid || isFetching}>{isEditable ? 'Guardar cambios' : 'Hacé tu reserva'}{' '}
                    {!isFetching && <i className="fa fa-paper-plane"></i>}
                    {isFetching && <i className="fa fa-spin fa-spinner"></i>}
                </Button>
                {goBack && <a href="#" onClick={(e) => {
                    e.preventDefault();
                    goBack();
                }}><i className="fa fa-chevron-left"></i>&nbsp;Volver a cargar mis datos</a>}
            </div>
        </Form>
    );
};

const SelectSchedule = ({ selectedDate, store, handleValueChange, value, ...rest }) => {
    const hourOptions = getRangeHoursFromStore(store, selectedDate);
    const { dataDay = [], dataNight = [] } = hourOptions;
    return (
        <Select {...rest} handleValueChange={handleValueChange} value={value}>
            <option value="">Elegí el horario</option>
            {dataDay.length > 0 && <optgroup label="Dia">
                {dataDay.map((v, i) => <option key={i}
                    title={!isGreaterThanToday(selectedDate, v) ? '(no disponible)' : ''}
                    disabled={!isGreaterThanToday(selectedDate, v)}
                    value={v}>{v} hs {!isGreaterThanToday(selectedDate, v) ? '(no disponible)' : ''}</option>)}
            </optgroup>}
            {dataNight.length > 0 && <optgroup label="Noche">
                {dataNight.map((v, i) => <option key={i}
                    title={!isGreaterThanToday(selectedDate, v) ? '(no disponible)' : ''}
                    disabled={!isGreaterThanToday(selectedDate, v)}
                    value={v}>{v} hs {!isGreaterThanToday(selectedDate, v) ? '(no disponible)' : ''}</option>)}
            </optgroup>}
        </Select>
    );
};

export default FormStoreReservation;