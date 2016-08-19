<?php

namespace Mailer\Form\Validator;

use Zend\Validator\EmailAddress;

class EmailListValidator extends EmailAddress
{
    public function isValid($value)
    {
        $emails = explode(',', $value);
        foreach ($emails as $email) {
            $result = parent::isValid($email);
            if (!$result) {
                return false;
            }
        }
        return true;
    }
}