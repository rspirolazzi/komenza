import { ReservationPayload } from '@/services/ReservationService';
import DateTimePicker from '@react-native-community/datetimepicker';
import { Picker } from '@react-native-picker/picker';
import React, { useEffect, useMemo, useState } from 'react';
import { Controller, useForm } from 'react-hook-form';
import { Button, Platform, StyleSheet, Text, TextInput, View } from 'react-native';
import { useDispatch, useSelector } from 'react-redux';
import { PersistPartial } from 'redux-persist/es/persistReducer';
import { getAvailableHours } from '../domain/usecases/getAvailableHours';
import StoresService from '../services/StoresService';
import { RootState } from '../store';
import { repeatLastReservation } from '../store/userSlice';

// Agrega el campo brand al formulario para que el payload sea compatible con el backend migrado
export type ReservationFormData = ReservationPayload

export default function ReservationForm({ onSubmit, initialValues }: { onSubmit: (data: ReservationFormData) => void, initialValues?: Partial<ReservationFormData> }) {
  const dispatch = useDispatch();
  // Corrige el tipado para acceder a user dentro del estado persistido
  const lastReservation = useSelector((state: RootState & PersistPartial) => (state as any).user.repeatLastReservation);
  const [step, setStep] = React.useState(1);
  const { control, handleSubmit, formState: { errors, isSubmitting }, watch, getValues, setValue, reset } = useForm<ReservationFormData>({
    defaultValues: initialValues && Object.keys(initialValues).length > 0
      ? initialValues
      : (lastReservation && Object.keys(lastReservation).length > 0 ? lastReservation : undefined),
  });
  const [stores, setStores] = useState<any[]>([]);
  const [showDatePicker, setShowDatePicker] = useState(false);

  // Obtener el brand_id seleccionado desde redux (general.selectedBrand)
  const selectedBrand = useSelector((state: any) => state.general.selectedBrand);

  useEffect(() => {
    // Filtra las sucursales por brand_id seleccionado
    const brandId = selectedBrand?.id;
    
    StoresService.getStores()
    .then((allStores) => {
        
        const filtered = allStores.filter((s: any) => s.brand_id == brandId);
        setStores(filtered);
      })
      .catch(console.error);
  }, [selectedBrand?.id]);

  React.useEffect(() => {
    if (initialValues && Object.keys(initialValues).length > 0) {
      reset(initialValues);
    } else if (lastReservation && Object.keys(lastReservation).length > 0) {
      reset(lastReservation);
    }
  }, [initialValues, lastReservation, reset]);

  // Cuando se envía el formulario, guarda los datos como última reserva
  const handleFormSubmit = (data: ReservationFormData) => {
    dispatch(repeatLastReservation(data));
    onSubmit(data);
  };

  const handleNext = () => {
    setStep(2);
  };
  const handleBack = () => {
    setStep(1);
  };

  const storeId = watch('store_id');
  const reservation_date = watch('reservation_date');
  const selectedStore = useMemo(() => stores.find(s => s.id == storeId), [stores, storeId]);
  const availableHours = useMemo(() => getAvailableHours(selectedStore, reservation_date), [selectedStore, reservation_date]);

  return (
    <View style={styles.form}>
      {step === 1 && (
        <>
          <Text style={styles.stepTitle}>Datos personales</Text>
          <Controller
            control={control}
            name="name"
            rules={{ required: 'Nombre requerido' }}
            render={({ field: { onChange, value } }) => (
              <TextInput
                style={styles.input}
                placeholder="Nombre"
                value={value}
                onChangeText={onChange}
              />
            )}
          />
          {errors.name && <Text style={styles.error}>{errors.name.message}</Text>}

          <Controller
            control={control}
            name="last_name"
            rules={{ required: 'Apellido requerido' }}
            render={({ field: { onChange, value } }) => (
              <TextInput
                style={styles.input}
                placeholder="Apellido"
                value={value}
                onChangeText={onChange}
              />
            )}
          />
          {errors.last_name && <Text style={styles.error}>{errors.last_name.message}</Text>}

          <Controller
            control={control}
            name="email"
            rules={{ required: 'Email requerido', pattern: { value: /.+@.+\..+/, message: 'Email inválido' } }}
            render={({ field: { onChange, value } }) => (
              <TextInput
                style={styles.input}
                placeholder="Email"
                value={value}
                onChangeText={onChange}
                keyboardType="email-address"
                autoCapitalize="none"
              />
            )}
          />
          {errors.email && <Text style={styles.error}>{errors.email.message}</Text>}

          <Controller
            control={control}
            name="mobile"
            rules={{ required: 'Teléfono requerido' }}
            render={({ field: { onChange, value } }) => (
              <TextInput
                style={styles.input}
                placeholder="Teléfono"
                value={value}
                onChangeText={onChange}
                keyboardType="phone-pad"
              />
            )}
          />
          {errors.mobile && <Text style={styles.error}>{errors.mobile.message}</Text>}

          <Controller
            control={control}
            name="birthday"
            rules={{ required: 'Cumpleaños requerido' }}
            render={({ field: { onChange, value } }) => (
              <TextInput
                style={styles.input}
                placeholder="Cumpleaños (YYYY-MM-DD)"
                value={value}
                onChangeText={onChange}
              />
            )}
          />
          {errors.birthday && <Text style={styles.error}>{errors.birthday.message}</Text>}

          <Button title="Continuar" onPress={handleNext} />
        </>
      )}
      {step === 2 && (
        <>
          <Text style={styles.stepTitle}>Datos de la reserva</Text>
          {/* Sucursal */}
          <Text style={styles.label}>Sucursal</Text>
          <Controller
            control={control}
            name="store_id"
            rules={{ required: 'Sucursal requerida' }}
            render={({ field: { onChange, value } }) => (
              <Picker
                selectedValue={value}
                onValueChange={onChange}
                style={styles.input}
              >
                <Picker.Item label="Selecciona una sucursal" value="" />
                {stores.map((store) => (
                  <Picker.Item key={store.id} label={store.address} value={store.id} />
                ))}
              </Picker>
            )}
          />
          {errors.store_id && <Text style={styles.error}>{errors.store_id.message}</Text>}

          {/* Fecha */}
          <Text style={styles.label}>Fecha de reserva</Text>
          <Controller
            control={control}
            name="reservation_date"
            rules={{ required: 'Fecha requerida' }}
            render={({ field: { onChange, value } }) => (
              Platform.OS === 'web' ? (
                <input
                  type="date"
                  value={value}
                  onChange={e => onChange(e.target.value)}
                  style={{ ...styles.input, padding: 10, fontSize: 16, marginBottom: 10 }}
                />
              ) : (
                <>
                  <Button
                    title={value ? value : "YYYY-MM-DD"}
                    onPress={() => setShowDatePicker(true)}
                  />
                  {showDatePicker && (
                    <View style={{ position: 'absolute', left: 0, right: 0, bottom: 0, backgroundColor: '#fff', zIndex: 10 }}>
                      <Text>Fecha</Text>
                      <DateTimePicker
                        value={value ? new Date(value) : new Date()}
                        mode="date"
                        display={Platform.OS === 'ios' ? 'spinner' : 'calendar'}
                        onChange={(event, selectedDate) => {
                          setShowDatePicker(Platform.OS === 'ios');
                          if (selectedDate) {
                            onChange(selectedDate.toISOString().split('T')[0]);
                          }
                        }}
                        style={{ width: '100%' }}
                      />
                      {Platform.OS === 'ios' && (
                        <Button title="Confirmar" onPress={() => setShowDatePicker(false)} />
                      )}
                    </View>
                  )}
                </>
              )
            )}
          />
          {errors.reservation_date && <Text style={styles.error}>{errors.reservation_date.message}</Text>}

          {/* Hora */}
          <Text style={styles.label}>Hora</Text>
          <Controller
            control={control}
            name="reservation_time"
            rules={{ required: 'Hora requerida' }}
            render={({ field: { onChange, value } }) => (
              <Picker
                selectedValue={value}
                onValueChange={onChange}
                style={styles.input}
                enabled={!!reservation_date && !!storeId}
              >
                <Picker.Item label="Selecciona un horario" value="" />
                {availableHours.map((h) => (
                  <Picker.Item key={h} label={h} value={h} />
                ))}
              </Picker>
            )}
          />
          {errors.reservation_time && <Text style={styles.error}>{errors.reservation_time.message}</Text>}

          <Controller
            control={control}
            name="people"
            rules={{ required: 'Cantidad de personas requerida' }}
            render={({ field: { onChange, value } }) => (
              <Picker
                selectedValue={value}
                onValueChange={onChange}
                style={styles.input}
              >
                <Picker.Item label="Personas" value="" />
                {[...Array(10)].map((_, i) => (
                  <Picker.Item key={i + 1} label={`${i + 1}`} value={`${i + 1}`} />
                ))}
              </Picker>
            )}
          />
          {errors.people && <Text style={styles.error}>{errors.people.message}</Text>}

          <Controller
            control={control}
            name="comment"
            render={({ field: { onChange, value } }) => (
              <TextInput
                style={styles.input}
                placeholder="Notas (opcional)"
                value={value}
                onChangeText={onChange}
                multiline
              />
            )}
          />

          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginTop: 12 }}>
            <Button title="Atrás" onPress={handleBack} />
            <Button title={isSubmitting ? 'Enviando...' : 'Reservar'} onPress={handleSubmit(handleFormSubmit)} disabled={isSubmitting} />
          </View>
        </>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  form: {
    width: '100%',
    maxWidth: 400,
    alignSelf: 'center',
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    marginTop: 24,
    elevation: 2,
    shadowColor: '#000',
    shadowOpacity: 0.08,
    shadowRadius: 4,
    shadowOffset: { width: 0, height: 2 },
  },
  input: {
    borderWidth: 1,
    borderColor: '#cbd5e1',
    borderRadius: 8,
    padding: 10,
    marginBottom: 10,
    fontSize: 16,
    backgroundColor: '#f1f5f9',
  },
  error: {
    color: '#ef4444',
    marginBottom: 8,
    fontSize: 14,
  },
  stepTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 16,
    color: '#1e293b',
    textAlign: 'center',
  },
  label: {
    fontSize: 16,
    fontWeight: '500',
    marginBottom: 8,
    color: '#1e293b',
  },
});
