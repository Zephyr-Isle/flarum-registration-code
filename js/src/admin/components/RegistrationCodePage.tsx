import app from 'flarum/admin/app';
import ExtensionPage from 'flarum/admin/components/ExtensionPage';
import RegistrationCodeManager from './RegistrationCodeManager';

export default class RegistrationCodePage extends ExtensionPage {
  content() {
    return (
      <div className="RegistrationCodePage">
        <div className="container">
          <div className="ExtensionPageSettingsContainer">
            {this.buildSettingComponent({
              setting: 'zephyrisle-registration-code.enabled',
              label: app.translator.trans('zephyrisle-registration-code.admin.settings.enabled_label'),
              type: 'boolean',
            })}
          </div>
          
          <div className="RegistrationCodeManagerContainer">
            <RegistrationCodeManager />
          </div>
        </div>
      </div>
    );
  }

  oninit() {
    super.oninit();
    console.log('RegistrationCodePage initialized');
  }
}
