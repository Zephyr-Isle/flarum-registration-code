import app from 'flarum/admin/app';
import { extend } from 'flarum/common/extend';

app.initializers.add('zephyrisle/flarum-registration-code', () => {
  extend(app.extensionData, 'settings', (settings) => {
    settings.addSetting({
      setting: 'zephyrisle-registration-code.enabled',
      label: app.translator.trans('zephyrisle-registration-code.admin.settings.enabled_label', {}, true),
      type: 'boolean',
    });
  });
});
