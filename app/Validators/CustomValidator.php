<?php

namespace App\Validators;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;

class CustomValidator extends Validator
{
    public function setErrors(array $errors): void
    {
        /** @var MessageBag $messageBag */
        $messageBag = app(MessageBag::class);
        $this->messages = $messageBag->merge($errors);
    }
}
