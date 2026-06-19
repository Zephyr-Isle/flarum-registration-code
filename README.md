# zephyrisle/flarum-registration-code

A Flarum extension that enforces one-time registration codes mapped to usernames.

## Features

- Manage username and registration-code pairs in the admin extension page.
- Import and export CSV data.
- Require a registration code on the sign-up form.
- Match each code to a specific username.
- Store the used registration code on the created user record.

## CSV format

```text
username,code
alice,ALICE-001
bob,BOB-001
```

## Development

```bash
composer install
cd js
npm install
npm run build
```
