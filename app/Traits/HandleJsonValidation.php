<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

trait HandleJsonValidation
{
    /**
     * pass if validation failed to public validator instance.
     *
     * @return void
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->is('api*')) {
            throw new ValidationException($validator, responseJson(
                success: false,
                message: 'Invalid Request Payload',
                code: Response::HTTP_UNPROCESSABLE_ENTITY,
                errors: $validator->getMessageBag()
            ));
        }
    }
}
