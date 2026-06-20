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
  // Flarum 2 中 SignUpModal 可能是懒加载的，先安全获取
  const SignUpModal = flarum.reg.get('core', 'forum/components/SignUpModal');

  if (!SignUpModal || !SignUpModal.prototype) {
    return;
  }

  extend(SignUpModal.prototype, 'oninit', function () {
    this.registrationCode = Stream('');
  });

  extend(SignUpModal.prototype, 'fields', function (items) {
    items.remove('email');
    items.add(
      'registrationCode',
      m('div', { className: 'Form-group' }, [
        m('label', app.translator.trans('zephyrisle-registration-code.forum.signup.registration_code_label')),
        m('input', {
          className: 'FormControl',
          type: 'text',
          placeholder: app.translator.trans('zephyrisle-registration-code.forum.signup.registration_code_placeholder'),
          bidi: this.registrationCode,
        }),
      ]),
      5
    );
  });

  extend(SignUpModal.prototype, 'submitData', function (data) {
    const username = data.username || this.username();
    data.email = autoEmail(username);
    data.registrationCode = this.registrationCode();
  });
});
