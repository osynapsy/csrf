<?php
namespace Osynapsy\Csrf;

use Osynapsy\Html\Component\InputHidden;
use Osynapsy\Html\Tag;

/**
 * Helper class to apply CSRF protection fields to Osynapsy forms.
 *
 * This class provides a static method `apply()` which generates a CSRF nonce
 * and token using the `Token` class and adds them as hidden input fields
 * (`csrf_nonce` and `csrf_token`) to a given form component.
 *
 * Usage:
 *   $form = new MyForm();
 *   \Osynapsy\Csrf\FormCsrf::apply($form, $secretKey);
 *
 * The hidden fields added by this helper should be validated using
 * `Osynapsy\Csrf\Action\AbstractAction::checkCsrf()` in the action
 * handling the form submission.
 *
 * @package Osynapsy\Csrf
 * @author Pietro Celeste <p.celeste@osynapsy.net>
 */
class FormCsrf
{
    const FIELD_NONCE = 'csrf_nonce';
    const FIELD_TOKEN = 'csrf_token';

    public static function apply(Tag $form, string $secretKey)
    {
        $token = self::generateCsrfToken($secretKey);
        $form->add(new InputHidden(self::FIELD_NONCE))->setValue($token['nonce']);
        $form->add(new InputHidden(self::FIELD_TOKEN))->setValue($token['token']);
        return $form;
    }

    protected static function generateCsrfToken($secretKey)
    {
        return (new Token($secretKey))->generate();
    }
}
