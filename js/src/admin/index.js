import app from 'flarum/admin/app';
import { extend } from 'flarum/common/extend';
import RegistrationCodeManager from './components/RegistrationCodeManager';

app.initializers.add('zephyrisle/flarum-registration-code', () => {
  extend(app.extensionData, 'settings', (settings) => {
    settings.addSetting({
      setting: 'zephyrisle-registration-code',
      label: app.translator.trans('zephyrisle-registration-code.admin.manager.heading'),
      type: 'component',
      component: RegistrationCodeManager,
    });
  });
});
