<?php declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventTypeValidator extends BaseValidator
{

    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct($validator);
    }

    public function validate($typeName)
    {
        $typeNameConstraint          = new Assert\NotBlank();
        $typeNameConstraint->message = 'Event type name cannot be blank';

        $this->errors = $this->validator->validate($typeName, $typeNameConstraint);
    }
}
