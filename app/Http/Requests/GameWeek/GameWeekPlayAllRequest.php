<?php

namespace App\Http\Requests\GameWeek;

use Illuminate\Foundation\Http\FormRequest;

class GameWeekPlayAllRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'all' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            'all.boolean' => 'Invalid parameters for playing all weeks',
        ];
    }
}
