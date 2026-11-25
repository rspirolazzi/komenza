import React from 'react';
import { Text, View } from 'react-native';

interface ValidationErrorsProps {
  errors: { [key: string]: string[] };
}

const ValidationErrors: React.FC<ValidationErrorsProps> = ({ errors }) => {
  if (!errors || Object.keys(errors).length === 0) return null;
  return (
    <View style={{ backgroundColor: '#fee2e2', padding: 10, borderRadius: 8, marginBottom: 10 }}>
      {Object.entries(errors).map(([field, messages]) =>
        messages.map((msg, i) => (
          <Text key={field + i} style={{ color: '#b91c1c', fontSize: 14 }}>{msg}({field})</Text>
        ))
      )}
    </View>
  );
};

export default ValidationErrors;
