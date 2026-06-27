import Extend from 'flarum/common/extenders';
import RegistrationCodeManager from './components/RegistrationCodeManager';

export default [
  new Extend.Admin().page(RegistrationCodeManager),
];
