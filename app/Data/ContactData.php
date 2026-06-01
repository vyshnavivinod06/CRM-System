<?php

namespace App\Data;

final readonly class ContactData
{
    public function __construct(
        public ?string $firstName,
        public ?string $lastName,
        public ?string $email,
        public ?string $phone,
        public bool $business = false,
        public ?string $companyName = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'business' => $this->business,
            'company_name' => $this->companyName,
        ];
    }
}
