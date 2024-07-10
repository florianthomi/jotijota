<?php

namespace App\Validator;

use Symfony\Component\Intl\Countries;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class JidValidator extends ConstraintValidator
{
    public const JID_REGEX = '[1-7]([A-Za-z]{2})[0-9]{2}[A-Za-z0-9]';

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var Jid $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (preg_match('/^'.self::JID_REGEX.'$/', $value, $matches) && Countries::exists($matches[1])) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
