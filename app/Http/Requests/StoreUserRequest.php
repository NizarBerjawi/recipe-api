<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ForResource;
use App\Models\User;

#[ForResource(User::class)]
class StoreUserRequest extends JsonApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
