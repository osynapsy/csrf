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
    /**
     * Name of the nonce hidden field.
     */
    const FIELD_NONCE = 'csrf_nonce';

    /**
     * Name of the token hidden field.
     */
    const FIELD_TOKEN = 'csrf_token';

    /**
     * Applies CSRF protection fields to the given form.
     *
     * Generates a new CSRF nonce and token pair, then adds them to the form
     * as hidden input fields.
     *
     * @param Tag $form The form object to add CSRF fields to.
     * @param string $secretKey Secret key used to generate the CSRF token.
     * @return Tag The form object with CSRF fields added.
     */
    public static function apply(Tag $form, string $secretKey)
    {
        $token = self::generateCsrfToken($secretKey);
        $form->add(new InputHidden(self::FIELD_NONCE))->setValue($token['nonce']);
        $form->add(new InputHidden(self::FIELD_TOKEN))->setValue($token['token']);
        return $form;
    }

    /**
     * Generates a CSRF nonce and token pair.
     *
     * @param string $secretKey Secret key used to generate the CSRF token.
     * @return array Array containing [nonce, token].
     */
    protected static function generateCsrfToken($secretKey)
    {
        return (new Token($secretKey))->generate();
    }
}
