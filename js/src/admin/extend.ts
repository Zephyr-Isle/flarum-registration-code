import Extend from 'flarum/common/extenders';
import app from 'flarum/admin/app';
import RegistrationCodeManager from './components/RegistrationCodeManager';

export default [
  new Extend.Admin()
    .setting(() => ({
      setting: 'zephyrisle-registration-code.enabled',
      label: app.translator.trans('zephyrisle-registration-code.admin.settings.enabled_label', {}, true),
      type: 'boolean',
    }), 30)
    .setting(
      () => function () {
        return m('div', { className: 'Form-group' }, [
          m('label', app.translator.trans('zephyrisle-registration-code.admin.manager.heading')),
          m('p', { className: 'helpText' }, app.translator.trans('zephyrisle-registration-code.admin.manager.description')),
          m(RegistrationCodeManager),
        ]);
      },
      40
    )
];
