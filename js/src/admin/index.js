import app from 'flarum/admin/app';
import RegistrationCodeManager from './components/RegistrationCodeManager';

app.initializers.add('zephyrisle/flarum-registration-code', () => {
  app.extensionData
    .for('zephyrisle-registration-code')
    .registerPage(RegistrationCodeManager);
});
