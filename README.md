# Osynapsy CSRF Protection

A lightweight and simple CSRF protection package for Osynapsy forms and actions.  
Provides a stateless, HMAC-based token system to secure sensitive POST operations.

---

## Features

- Generate CSRF tokens (`nonce` + `token`) for forms.
- Easy integration with Osynapsy `Form` components.
- Optional check in actions extending `AbstractAction`.
- Minimal and explicit: only enable CSRF where necessary.
- No session overhead, fully stateless.

---

## Installation

Install via Composer:

```bash
composer require osynapsy/csrf
```

## Usage

## Adding CSRF fields to a form

```php
use Osynapsy\Csrf\FormCsrf;

$form = new \MyProject\Form\UserEditForm();
\FormCsrf::apply($form, $_ENV['CSRF_SECRET']);

```

This will add two hidden fields to your form:

- csrf_nonce
- csrf_token

## Checking CSRF in an action

Extend your action from Osynapsy\Csrf\Action\AbstractAction:

```php
public function execute()
{
    $this->checkCsrf(); // Validates the CSRF token and nonce

    // Your action logic here
}
```

The check will throw an exception if the CSRF token is missing or invalid.

### Security Notes

Only enable CSRF on forms that perform sensitive POST operations.
Use HTTPS and set secure cookies for sessions.
Keep SECRET_KEY secret and unique per project.
The package is stateless, so no server-side session storage is required.

### Classes

Osynapsy\Csrf\Token – Generates and verifies CSRF tokens.
Osynapsy\Csrf\FormCsrf – Helper to apply CSRF fields to a form.
Osynapsy\Csrf\Action\AbstractAction – Base action with checkCsrf() method.

## License

MIT licence