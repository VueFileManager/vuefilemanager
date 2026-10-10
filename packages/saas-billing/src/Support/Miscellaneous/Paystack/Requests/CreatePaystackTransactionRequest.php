<?php
namespace VueFileManager\Subscription\Support\Miscellaneous\Paystack\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaystackTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'planCode' => 'sometimes|nullable|string',
            'amount'   => 'sometimes|nullable|integer',
        ];
    }
}
