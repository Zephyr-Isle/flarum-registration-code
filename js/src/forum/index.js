import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import Stream from 'flarum/common/utils/Stream';

function forumDomain() {
  return window.location.hostname || 'forum.local';
}

function autoEmail(username) {
  return `${username}@${forumDomain()}`;
}

app.initializers.add('zephyrisle/flarum-registration-code', () => {
  extend(app.components, 'SignUpModal', (component) => {
    extend(component.prototype, 'oninit', function () {
      this.registrationCode = Stream('');
    });

    extend(component.prototype, 'fields', function (items) {
      items.remove('email');
      items.add(
        'registrationCode',
        <div className="Form-group">
          <label>{app.translator.trans('zephyrisle-registration-code.forum.signup.registration_code_label')}</label>
          <input
            className="FormControl"
            type="text"
            placeholder={app.translator.trans('zephyrisle-registration-code.forum.signup.registration_code_placeholder')}
            bidi={this.registrationCode}
          />
        </div>,
        5
      );
    });

    extend(component.prototype, 'submitData', function (data) {
      const username = data.username || this.username();
      data.email = autoEmail(username);
      data.registrationCode = this.registrationCode();
    });
  });
});
