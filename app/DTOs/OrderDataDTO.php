<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class OrderDataDTO
{
    public string $name;
    public string $phone;
    public string $delivery;
    public ?string $city;
    public ?string $street;
    public bool $save_profile;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->phone = $data['phone'];
        $this->delivery = $data['delivery'];
        $this->city = $data['city'] ?? null;
        $this->street = $data['street'] ?? null;
        $this->save_profile = (bool)($data['save_profile'] ?? false);
    }

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'regex:/^\+?[0-9\s\-\(\)]{10,20}$/',
            ],
            'delivery' => 'required|in:courier,pickup',
            'city' => 'required_if:delivery,courier|string|max:255|nullable',
            'street' => 'required_if:delivery,courier|string|max:255|nullable',
            'save_profile' => 'nullable|boolean',
        ]);

        return new self($validated);
    }
}
