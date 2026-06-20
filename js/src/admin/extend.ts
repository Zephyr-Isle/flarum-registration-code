import Extend from 'flarum/common/extenders';
import app from 'flarum/admin/app';
import RegistrationCodeManager from './components/RegistrationCodeManager';

export default [
  new Extend.Admin()
    .setting(() => ({
      setting: 'zephyrisle-registration-code.manager',
      label: app.translator.trans('zephyrisle-registration-code.admin.manager.heading'),
      type: 'component',
      component: RegistrationCodeManager,
    }), 30)
];
