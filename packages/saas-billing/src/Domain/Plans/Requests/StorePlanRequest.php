<?php
namespace VueFileManager\Subscription\Domain\Plans\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
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
            'type'        => 'required|string',
            'name'        => 'required|string',
            'amount'      => 'sometimes|numeric',
            'interval'    => 'sometimes|string',
            'description' => 'sometimes|string|nullable',
            'currency'    => 'required|string',
            'meters'      => 'sometimes|array',
        ];
    }
}
