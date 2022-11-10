<?php declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseValidator
{
    protected $errors = [];

    protected $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0 ? true : false;
    }

    public function getErrors()
    {
        if (count($this->errors) > 0) {
            return $this->errors[0]->getMessage();
        }

    }
}
