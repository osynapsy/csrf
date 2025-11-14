<?php
namespace Osynapsy\Csrf;

use Osynapsy\Html\Component\InputHidden;
use Osynapsy\Html\Tag;

class FormCsrf
{
    const FIELD_NONCE = 'csrf_nonce';
    const FIELD_TOKEN = 'csrf_token';

    public static function apply(Tag $form, string $secretKey)
    {
        list($nonce, $token) = self::generateCsrfToken($secretKey);
        $form->add(new InputHidden(self::FIELD_NONCE))->setValue($nonce);
        $form->add(new InputHidden(self::FIELD_TOKEN))->setValue($token);
        return $form;
    }

    protected static function generateCsrfToken($secretKey)
    {
        return (new Token($secretKey))->generate();
    }
}
