import Extend from 'flarum/common/extenders';
import RegistrationCodePage from './components/RegistrationCodePage';

export default [
  new Extend.Admin()
    .page(RegistrationCodePage)
];
