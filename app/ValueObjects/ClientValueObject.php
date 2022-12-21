<?php

namespace App\ValueObjects;

/**
 * @OA\Schema()
 */
class ClientValueObject
{
    /**
     * The customer id
     * @var int
     *
     * @OA\Property(
     *   property="id",
     *   type="id",
     *   description="id",
     *   example="1"
     * )
     */
    public readonly int $id;

    /**
     * The customer first name
     * @var string
     *
     * @OA\Property(
     *   property="firstName",
     *   type="string",
     *   description="first name",
     *   example="John"
     * )
     */
    public readonly string $firstName;

    /**
     * Channel
     * @var string
     *
     * @OA\Property(
     *   property="lastName",
     *   type="string",
     *   description="last name",
     *   example="Doe"
     * )
     */
    public readonly string $lastName;

    /**
     * Channel
     * @var string
     *
     * @OA\Property(
     *   property="email",
     *   type="string",
     *   description="email",
     *   example="johndoe@example.com"
     * )
     */
    public readonly string $email;

    /**
     * Channel
     * @var string
     *
     * @OA\Property(
     *   property="phone",
     *   type="string",
     *   description="phone",
     *   example="+37128323111"
     * )
     */
    public readonly string $phone;

    /**
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $phone
     */
    public function __construct(int $id, string $firstName, string $lastName, string $email, string $phone)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phoneNumber = $phone;
    }


}
