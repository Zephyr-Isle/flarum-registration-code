import app from 'flarum/admin/app';
import Button from 'flarum/common/components/Button';
import Component from 'flarum/common/Component';
import Stream from 'flarum/common/utils/Stream';
import extractText from 'flarum/common/utils/extractText';
import withAttr from 'flarum/common/utils/withAttr';

function apiUrl(path = '') {
  const base = (app.forum && app.forum.attribute && app.forum.attribute('apiUrl')) || app.data.apiUrl || '/api';
  return `${base}${path}`;
}

function showAlert(type, message) {
  app.alerts.show({ type }, message);
}

function errorMessage(error) {
  return error?.response?.errors?.[0]?.detail || error?.message || extractText(app.translator.trans('core.lib.error.generic_message'));
}

export default class RegistrationCodeManager extends Component {
  oninit() {
    this.loading = false;
    this.submitting = false;
    this.records = [];
    this.username = Stream('');
    this.code = Stream('');
    this.importContent = Stream('');
    this.load();
  }

  async load() {
    this.loading = true;
    m.redraw();

    try {
      const response = await app.request({ method: 'GET', url: apiUrl('/registration-codes') });
      this.records = response.data || [];
    } catch (error) {
      showAlert('error', errorMessage(error));
    } finally {
      this.loading = false;
      m.redraw();
    }
  }

  async addRecord(event) {
    event.preventDefault();
    this.submitting = true;
    m.redraw();

    try {
      const response = await app.request({
        method: 'POST',
        url: apiUrl('/registration-codes'),
        body: { username: this.username(), code: this.code() },
      });

      this.username('');
      this.code('');
      showAlert('success', response.message || 'Registration code created.');
      await this.load();
    } catch (error) {
      showAlert('error', errorMessage(error));
    } finally {
      this.submitting = false;
      m.redraw();
    }
  }

  async deleteRecord(id) {
    if (!confirm(app.translator.trans('zephyrisle-registration-code.admin.manager.delete_confirm'))) {
      return;
    }

    this.submitting = true;
    m.redraw();

    try {
      const response = await app.request({ method: 'DELETE', url: apiUrl(`/registration-codes/${id}`) });
      showAlert('success', response.message || 'Registration code deleted.');
      await this.load();
    } catch (error) {
      showAlert('error', errorMessage(error));
    } finally {
      this.submitting = false;
      m.redraw();
    }
  }

  async importRecords() {
    this.submitting = true;
    m.redraw();

    try {
      const response = await app.request({
        method: 'POST',
        url: apiUrl('/registration-codes/import'),
        body: { content: this.importContent() },
      });

      const summary = response.summary || { created: 0, skipped: 0 };
      this.importContent('');
      showAlert('success', `Import complete. Created: ${summary.created}, skipped: ${summary.skipped}.`);
      await this.load();
    } catch (error) {
      showAlert('error', errorMessage(error));
    } finally {
      this.submitting = false;
      m.redraw();
    }
  }

  async exportRecords() {
    try {
      const response = await app.request({ method: 'GET', url: apiUrl('/registration-codes/export') });
      const blob = new Blob([response.content || ''], { type: 'text/csv;charset=utf-8' });
      const url = window.URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = response.filename || 'registration-codes.csv';
      document.body.appendChild(link);
      link.click();
      link.remove();
      window.URL.revokeObjectURL(url);
    } catch (error) {
      showAlert('error', errorMessage(error));
    }
  }

  async loadFile(event) {
    const file = event.target.files && event.target.files[0];

    if (!file) {
      return;
    }

    this.importContent(await file.text());
    m.redraw();
  }

  view() {
    return (
      <div className="RegistrationCodeManager">
        <form className="RegistrationCodeManager-form" onsubmit={this.addRecord.bind(this)}>
          <div className="Form-group">
            <label>{app.translator.trans('zephyrisle-registration-code.admin.manager.username_label')}</label>
            <input className="FormControl" type="text" placeholder={extractText(app.translator.trans('zephyrisle-registration-code.admin.manager.username_placeholder'))} bidi={this.username} disabled={this.submitting} />
          </div>

          <div className="Form-group">
            <label>{app.translator.trans('zephyrisle-registration-code.admin.manager.code_label')}</label>
            <input className="FormControl" type="text" placeholder={extractText(app.translator.trans('zephyrisle-registration-code.admin.manager.code_placeholder'))} bidi={this.code} disabled={this.submitting} />
          </div>

          <div className="ButtonGroup">
            <Button className="Button Button--primary" type="submit" loading={this.submitting}>{app.translator.trans('zephyrisle-registration-code.admin.manager.add_button')}</Button>
            <Button className="Button" type="button" onclick={this.load.bind(this)} disabled={this.loading || this.submitting}>{app.translator.trans('zephyrisle-registration-code.admin.manager.refresh_button')}</Button>
            <Button className="Button" type="button" onclick={this.exportRecords.bind(this)} disabled={this.loading || this.submitting}>{app.translator.trans('zephyrisle-registration-code.admin.manager.export_button')}</Button>
          </div>
        </form>

        <div className="Form-group">
          <label>{app.translator.trans('zephyrisle-registration-code.admin.manager.import_label')}</label>
          <input className="FormControl" type="file" accept=".csv,text/csv" onchange={this.loadFile.bind(this)} disabled={this.submitting} />
          <textarea className="FormControl RegistrationCodeManager-import" rows="6" placeholder={extractText(app.translator.trans('zephyrisle-registration-code.admin.manager.import_placeholder'))} value={this.importContent()} oninput={withAttr('value', this.importContent)} disabled={this.submitting} />
          <Button className="Button Button--primary" type="button" onclick={this.importRecords.bind(this)} loading={this.submitting}>{app.translator.trans('zephyrisle-registration-code.admin.manager.import_button')}</Button>
        </div>

        {this.loading ? (
          <p>{app.translator.trans('core.admin.loading.text')}</p>
        ) : this.records.length ? (
          <div className="RegistrationCodeManager-tableWrap">
            <table className="RegistrationCodeManager-table">
              <thead>
                <tr>
                  <th>{app.translator.trans('zephyrisle-registration-code.admin.manager.table.username')}</th>
                  <th>{app.translator.trans('zephyrisle-registration-code.admin.manager.table.code')}</th>
                  <th>{app.translator.trans('zephyrisle-registration-code.admin.manager.table.status')}</th>
                  <th>{app.translator.trans('zephyrisle-registration-code.admin.manager.table.used_by')}</th>
                  <th>{app.translator.trans('zephyrisle-registration-code.admin.manager.table.used_at')}</th>
                  <th>{app.translator.trans('zephyrisle-registration-code.admin.manager.table.actions')}</th>
                </tr>
              </thead>
              <tbody>
                {this.records.map((record) => (
                  <tr>
                    <td>{record.username}</td>
                    <td><code>{record.code}</code></td>
                    <td>{record.used ? app.translator.trans('zephyrisle-registration-code.admin.manager.status.used') : app.translator.trans('zephyrisle-registration-code.admin.manager.status.unused')}</td>
                    <td>{record.usedBy || '-'}</td>
                    <td>{record.usedAt || '-'}</td>
                    <td><Button className="Button Button--danger Button--small" type="button" onclick={() => this.deleteRecord(record.id)} disabled={this.submitting}>{app.translator.trans('zephyrisle-registration-code.admin.manager.delete_button')}</Button></td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <p>{app.translator.trans('zephyrisle-registration-code.admin.manager.empty_text')}</p>
        )}
      </div>
    );
  }
}
