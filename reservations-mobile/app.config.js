// Extiende app.json con lo que depende del entorno de compilación (CI de Android).
// ANDROID_VERSION_CODE debe crecer en cada AAB que se suba a Google Play.
module.exports = ({ config }) => ({
  ...config,
  android: {
    ...config.android,
    // Definitivo una vez publicado en Google Play
    package: 'ar.com.komenza.app',
    versionCode: Number(process.env.ANDROID_VERSION_CODE) || 1,
  },
  plugins: [...(config.plugins || []), './plugins/withReleaseSigning'],
});
