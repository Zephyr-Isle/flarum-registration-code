import app from 'flarum/admin/app';
import extend from './extend';

app.initializers.add('zephyrisle/flarum-registration-code', () => {
  extend.extension();
});

export * from './extend';
