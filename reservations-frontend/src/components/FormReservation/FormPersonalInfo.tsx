import React from 'react';
import Form from '../Form';
import Button from '../Button';
import DatePicker from 'react-datepicker';
import { useDispatch, useSelector } from 'react-redux';
import { useForm, Controller } from 'react-hook-form';
import moment from 'moment';

import { setUser, setRepeatLastReservation } from '@/store/user/slice';
import { Col, Row, FormControl } from 'react-bootstrap';

interface FormPersonalInfoProps {
  onContinue: () => void;
}

interface PersonalInfoFields {
  name: string;
  last_name: string;
  email: string;
  mobile: string;
  birthday: string;
}

const FormPersonalInfo: React.FC<FormPersonalInfoProps> = ({ onContinue }) => {
  const dispatch = useDispatch();
  const user = useSelector((state: any) => state.user?.user);
  const reservations = useSelector((state: any) => state.reservation?.reservations || []);

  const { control, handleSubmit, setValue, formState: { errors, isValid } } = useForm<PersonalInfoFields>({
    mode: 'onChange',
    defaultValues: {
      name: user?.name || '',
      last_name: user?.last_name || '',
      email: user?.email || '',
      mobile: user?.mobile || '',
      birthday: user?.birthday || '',
    },
  });

  const onSubmit = (data: PersonalInfoFields) => {
    dispatch(setRepeatLastReservation(null));
    dispatch(setUser({
      id: user?.id || '',
      ...data,
      birthday: moment(data.birthday).format('YYYY-MM-DD'),
    }));
    onContinue();
  };

  const handleRepeatLastReservation = () => {
    if (reservations.length > 0) {
      const last = reservations[reservations.length - 1];
      dispatch(setRepeatLastReservation(last.data || last));
      onContinue();
    }
  };

  return (
    <Form method="post" onSubmit={handleSubmit(onSubmit)}>
      <Row>
        <Col>
          <label><i className="fa fa-user" aria-hidden="true"></i> Nombre:</label>
          <Controller
            name="name"
            control={control}
            rules={{ required: true }}
            render={({ field }) => (
              <FormControl
                autoComplete="given-name"
                type="text"
                placeholder="Tu nombre"
                {...field}
              />
            )}
          />
          {errors.name && <div className="error-message">Campo requerido</div>}
        </Col>
        <Col>
          <label><i className="fa fa-user" aria-hidden="true"></i> Apellido:</label>
          <Controller
            name="last_name"
            control={control}
            rules={{ required: true }}
            render={({ field }) => (
              <FormControl
                autoComplete="family-name"
                type="text"
                placeholder="Tu apellido"
                {...field}
              />
            )}
          />
          {errors.last_name && <div className="error-message">Campo requerido</div>}
        </Col>
      </Row>
      <Row>
        <Col>
          <label><i className="fa fa-envelope" aria-hidden="true"></i> Email:</label>
          <Controller
            name="email"
            control={control}
            rules={{ required: true, pattern: /^([\w.%+-]+)@([\w-]+\.)+([\w]{2,})$/i }}
            render={({ field }) => (
              <FormControl
                autoComplete="email"
                type="email"
                placeholder="Tu email"
                {...field}
              />
            )}
          />
          {errors.email && <div className="error-message">Email inválido</div>}
        </Col>
        <Col>
          <label><i className="fa fa-envelope" aria-hidden="true"></i> Celular:</label>
          <Controller
            name="mobile"
            control={control}
            rules={{ required: true }}
            render={({ field }) => (
              <FormControl
                autoComplete="tel-national"
                type="number"
                placeholder="Tu celular"
                {...field}
              />
            )}
          />
          {errors.mobile && <div className="error-message">Campo requerido</div>}
        </Col>
      </Row>
      <Row>
        <Col>
          <label><i className="fa fa-calendar" aria-hidden="true"></i> Cumpleaños:</label>
          <Controller
            name="birthday"
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
                selected={field.value ? moment(field.value).toDate() : null}
                onChange={date => field.onChange(date ? moment(date).format('YYYY-MM-DD') : '')}
                openToDate={moment([2000]).toDate()}
                minDate={moment([1950]).toDate()}
                maxDate={moment().toDate()}
              />
            )}
          />
          {errors.birthday && <div className="error-message">Campo requerido</div>}
        </Col>
        <Col>
          <div className="make">
            <Button
              type="button"
              className="repeatReservation"
              disabled={reservations.length === 0}
              onClick={handleRepeatLastReservation}
            >
              Repetir ultima reserva&nbsp;
              <i className="fa fa-redo" aria-hidden="true"></i>
            </Button>
          </div>
        </Col>
      </Row>
      <div className="form-date-w6-agileits">
        <div className="make">
          <Button type="submit" disabled={!isValid}>
            Continuar con mi reserva <i className="fa fa-chevron-right"></i>
          </Button>
        </div>
      </div>
      <div className="clear"></div>
    </Form>
  );
};

export default FormPersonalInfo;
