import app from 'flarum/admin/app';
import { extend } from 'flarum/common/extend';
import RegistrationCodeManager from './components/RegistrationCodeManager';

export default {
  extension() {
    // Use the proper Flarum v2 settings API
    extend(app.extensionData, 'settings', (settings) => {
      settings.addSetting({
        setting: 'zephyrisle-registration-code',
        label: app.translator.trans('zephyrisle-registration-code.admin.manager.heading'),
        type: 'component',
        component: RegistrationCodeManager,
      });
    });
  }
};
