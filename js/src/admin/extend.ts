import Extend from 'flarum/common/extenders';
import app from 'flarum/admin/app';

export default [
  new Extend.Admin()
    .setting(() => ({
      setting: 'zephyrisle-registration-code',
      label: app.translator.trans('zephyrisle-registration-code.admin.manager.heading'),
      type: 'component',
    }), 30)
];
