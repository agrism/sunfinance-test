<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *   response="validationErrorResponse422",
 *   description="validation error",
 *   @OA\JsonContent(
 *      ref="#/components/schemas/validationErrorSchema",
 *   )
 * )
 * @OA\Schema(
 *    schema="validationErrorSchema",
 *    type="object",
 *    @OA\Property(
 *      property="message",
 *      type="string",
 *      example="fieldNameOne first error message"
 *    ),
 *    @OA\Property(
 *      property="errors",
 *      type="object",
 *      @OA\Property(
 *          property="fieldNameOne",
 *          type="array",
 *          @OA\Items(anyOf={
 *              @OA\Schema(type="string",example="fieldNameOne first error message"),
 *              @OA\Schema(type="string",example="fieldNameOne second error message"),
 *          })
 *      ),
 *      @OA\Property(
 *          property="fieldNameTwo",
 *          type="array",
 *          @OA\Items(anyOf={
 *              @OA\Schema(type="string",example="fieldNameTwo first error message"),
 *              @OA\Schema(type="string",example="fieldNameTwo second error message"),
 *          })
 *      ),
 *    ),
 * )
 *
 * @class ValidationRequest
 * @package App\Http\Requests
 */
class ValidationRequest extends FormRequest
{
    /**
     * @return true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
    }
}
