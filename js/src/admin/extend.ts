import Extend from 'flarum/common/extenders';
import app from 'flarum/admin/app';
import RegistrationCodePage from './components/RegistrationCodePage';

export default [
  new Extend.Admin()
    .page(RegistrationCodePage)
    .setting(() => ({
      setting: 'zephyrisle-registration-code.enabled',
      label: app.translator.trans('zephyrisle-registration-code.admin.settings.enabled_label', {}, true),
      type: 'boolean',
    })),
];
