<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

trait FailedValidationTrait
{
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
                                                             'errors' => $validator->errors(),
                                                             'status' => 'error'
                                                         ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
