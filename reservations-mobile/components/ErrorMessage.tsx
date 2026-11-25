import React from 'react';
import { Text, View } from 'react-native';

interface ErrorMessageProps {
  message?: string | null;
}

const ErrorMessage: React.FC<ErrorMessageProps> = ({ message }) => {
  if (!message) return null;
  return (
    <View style={{ backgroundColor: '#fee2e2', padding: 10, borderRadius: 8, marginBottom: 10 }}>
      <Text style={{ color: '#b91c1c', fontSize: 14 }}>{message}</Text>
    </View>
  );
};

export default ErrorMessage;
