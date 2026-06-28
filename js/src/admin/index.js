import Extend from 'flarum/common/extenders';
import RegistrationCodeManager from './components/RegistrationCodeManager';

export default [
  new Extend.Admin()
    .setting(() => ({
      setting: 'zephyrisle-registration-code.enabled',
      label: app.translator.trans('zephyrisle-registration-code.admin.settings.enabled_label', {}, true),
      type: 'boolean',
    }), 30)
    .customSetting(() => <RegistrationCodeManager />, 40),
];
