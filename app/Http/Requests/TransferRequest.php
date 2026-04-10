<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set true biar bisa dipake 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     
     */
    public function rules(): array
    {
        return [
            //  Rules nya ada di sini
            'amount' => 'required|numeric|min:10000',
            'recipient_account' => 'required|exists:users,id',
            'note' => 'nullable|string|max:100'
        ];
    }
}
