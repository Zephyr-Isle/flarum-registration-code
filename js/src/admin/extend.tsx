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
    .setting(() => function () {
      return (
        <div className="Form-group">
          <h3>{app.translator.trans('zephyrisle-registration-code.admin.manager.heading')}</h3>
          <RegistrationCodeManager />
        </div>
      );
    }, 20)
];
