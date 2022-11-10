<?php declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventValidator extends BaseValidator
{
    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct($validator);
    }

    public function validate($eventData)
    {
        $groups = new Assert\GroupSequence(['Default', 'custom']);

        $constraint = new Assert\Collection([
            'details' => new Assert\NotBlank(null, 'Details cannot be blank'),
            'type' => new Assert\NotBlank(null, 'Type cannot be blank'),
        ]);

        $this->errors = $this->validator->validate($eventData, $constraint, $groups);
    }
}
