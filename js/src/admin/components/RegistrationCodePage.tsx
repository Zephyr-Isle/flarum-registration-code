import Component from 'flarum/common/Component';
import RegistrationCodeManager from './RegistrationCodeManager';

export default class RegistrationCodePage extends Component {
  view() {
    return (
      <div className="RegistrationCodePage">
        <div className="container">
          <RegistrationCodeManager />
        </div>
      </div>
    );
  }
}
