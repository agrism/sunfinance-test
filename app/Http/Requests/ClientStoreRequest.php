<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LVR\Phone\E164;
use OpenApi\Annotations as OA;


/**
 * @OA\RequestBody(
 *  request="ClientStoreRequest",
 *  description="Client object that needs to be added to the store",
 *  @OA\JsonContent(
 *      type="object",
 *      required={"firstName","lastName","email","phoneNumber"},
 *      @OA\Property(property="firstName", type="string", format="string", example="John"),
 *      @OA\Property(property="lastName", type="string", format="string", example="Doe"),
 *      @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
 *      @OA\Property(property="phoneNumber", type="string", format="phone", example="+37128323111"),
 *  )
 * )
 */
class ClientStoreRequest extends FormRequest
{
    /**
     * @return true
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $nameValidator = [
            'required',
            'min:2',
            'max:32',
            function ($attribute, $value, $fail) {
                if (!preg_match("/^[\w\d\s.,-]*$/", $value)) {
                    $fail(sprintf('The %s should contains only latin symbols.', $this->camelToHuman($attribute)));
                }
            },
        ];

        return [
            'firstName' => $nameValidator,
            'lastName' => $nameValidator,
            'email' => 'required|email|max:255',
            'phoneNumber' => new E164,
        ];
    }

    protected function camelToHuman(string $value): string
    {
        $attribute = implode(' ', preg_split('/(?=[A-Z])/', $value));
        return mb_convert_case($attribute, MB_CASE_LOWER, 'UTF-8');
    }
}
