// Firma el build release con la clave de subida que recibe por variables de entorno.
// Sin ANDROID_UPLOAD_STORE_FILE se mantiene la firma de debug (builds locales).
const { withAppBuildGradle } = require('expo/config-plugins');

const RELEASE_SIGNING = `
        release {
            if (System.getenv('ANDROID_UPLOAD_STORE_FILE')) {
                storeFile file(System.getenv('ANDROID_UPLOAD_STORE_FILE'))
                storePassword System.getenv('ANDROID_UPLOAD_STORE_PASSWORD')
                keyAlias System.getenv('ANDROID_UPLOAD_KEY_ALIAS')
                keyPassword System.getenv('ANDROID_UPLOAD_KEY_PASSWORD')
            }
        }`;

const RELEASE_CONFIG =
  "signingConfig System.getenv('ANDROID_UPLOAD_STORE_FILE') ? signingConfigs.release : signingConfigs.debug";

function addReleaseSigning(gradle) {
  if (gradle.includes("System.getenv('ANDROID_UPLOAD_STORE_FILE')")) return gradle;

  const signingStart = gradle.indexOf('signingConfigs {');
  const buildTypesStart = gradle.indexOf('buildTypes {');
  const releaseStart = gradle.indexOf('release {', buildTypesStart);
  const debugLine = 'signingConfig signingConfigs.debug';
  const releaseLine = gradle.indexOf(debugLine, releaseStart);
  if (signingStart < 0 || buildTypesStart < 0 || releaseStart < 0 || releaseLine < 0) {
    throw new Error('withReleaseSigning: android/app/build.gradle no tiene el formato esperado');
  }

  const withConfig =
    gradle.slice(0, releaseLine) + RELEASE_CONFIG + gradle.slice(releaseLine + debugLine.length);
  const insertAt = signingStart + 'signingConfigs {'.length;
  return withConfig.slice(0, insertAt) + RELEASE_SIGNING + withConfig.slice(insertAt);
}

module.exports = (config) =>
  withAppBuildGradle(config, (mod) => {
    mod.modResults.contents = addReleaseSigning(mod.modResults.contents);
    return mod;
  });
