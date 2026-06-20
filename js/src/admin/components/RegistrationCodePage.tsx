import app from 'flarum/admin/app';
import ExtensionPage from 'flarum/admin/components/ExtensionPage';
import RegistrationCodeManager from './RegistrationCodeManager';

export default class RegistrationCodePage extends ExtensionPage {
  content() {
    return (
      <div className="RegistrationCodePage">
        <div className="container">
          <RegistrationCodeManager />
        </div>
      </div>
    );
  }
}
