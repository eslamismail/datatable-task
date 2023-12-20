<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;

class UserDataTableRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        // {
        //     "sort": {
        //         "column": [
        //             "id",
        //             "name"
        //         ],
        //         "asc": [
        //             "desc",
        //             "asc"
        //         ]
        //     },
        //     "filter": {
        //         "column": [
        //             "name"
        //         ],
        //         "opreator": [
        //             "like"
        //         ],
        //         "value": [
        //             "Mills"
        //         ]
        //     },
        //     "page": "1",
        //     "per_page": "10"
        // }
        $headers = Schema::getColumnListing('users');
        $headers = implode(',', $headers);
        $operators = [
            '>',
            '>=',
            '<',
            '<=',
            '=',
            '!=',
            'like',
            'not like'
        ];

        $operators = implode(',', $operators);
        return [
            'page' => 'nullable|numeric|min:1',
            'per_page' => 'nullable|numeric|min:1',
            'sort.column' => 'sometimes|array',
            'sort.column.*' => 'string',
            'sort.asc' => 'sometimes|array',
            'sort.asc.*' => 'in:asc,desc',
            'filter.column' => 'sometimes|array',
            'filter.column.*' => 'in:' . $headers,
            'filter.opreator' => 'sometimes|array',
            'filter.opreator.*' => 'in:' . $operators,
            'filter.value' => 'sometimes|array',
            'filter.value.*' => 'string',
        ];
    }
}
