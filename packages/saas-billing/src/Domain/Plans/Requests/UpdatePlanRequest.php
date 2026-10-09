<?php
namespace VueFileManager\Subscription\Domain\Plans\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
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
            'name'        => 'sometimes|string',
            'visible'     => 'sometimes|bool',
            'description' => 'sometimes|string|nullable',
        ];
    }
}
