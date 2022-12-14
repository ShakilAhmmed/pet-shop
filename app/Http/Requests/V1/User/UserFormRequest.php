<?php

namespace App\Http\Requests\V1\User;

use App\Enums\AdminStatus;
use App\Enums\MarketingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
        return [
            'first_name' => ['required', 'min:3'],
            'last_name' => ['required', 'min:3'],
            'email' => ['required', 'email',
                Rule::unique('users', 'email')->ignore($this->route('id')),
            ],
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required'],
            'address' => ['required'],
            'phone_number' => ['required'],
        ];
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'first_name' => $this->input('first_name'),
            'last_name' => $this->input('last_name'),
            'is_admin' => AdminStatus::NO,
            'email' => $this->input('email'),
            'password' => $this->input('password'),
            'address' => $this->input('address'),
            'phone_number' => $this->input('phone_number'),
            'is_marketing' => $this->input('is_marketing', MarketingStatus::NO),
        ];
    }
}
