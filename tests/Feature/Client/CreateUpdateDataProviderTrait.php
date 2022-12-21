<?php

namespace Tests\Feature\Client;

trait CreateUpdateDataProviderTrait
{
    public function creationUpdateDataProvider(): array
    {
        return [
            [
                'ш',
                'ш',
                'aa@aa.lv',
                '+37128 3231',
                [
                    'message' => 'The first name must be at least 2 characters. (and 4 more errors)',
                    'errors' => [
                        'firstName' => [
                            'The first name must be at least 2 characters.',
                            'The first name should contains only latin symbols.',
                        ],
                        'lastName' => [
                            'The last name must be at least 2 characters.',
                            'The last name should contains only latin symbols.',
                        ],
                        'phoneNumber' => [
                            'phone number must be in E.164 phone format',
                        ],
                    ]
                ]
            ]
        ];
    }
}
