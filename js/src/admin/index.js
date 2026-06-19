import app from 'flarum/admin/app';
import RegistrationCodeManager from './components/RegistrationCodeManager';

app.initializers.add('zephyrisle/flarum-registration-code', () => {
  app.extensionData
    .for('zephyrisle-registration-code')
    .registerSetting(function () {
      return (
        <div className="Form-group">
          <label>{app.translator.trans('zephyrisle-registration-code.admin.manager.heading')}</label>
          <p className="helpText">{app.translator.trans('zephyrisle-registration-code.admin.manager.description')}</p>
          <RegistrationCodeManager />
        </div>
      );
    }, 10);
});
