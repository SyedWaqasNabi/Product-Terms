<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ExportFileRequest
 * @package App\Http\Requests
 */
class ExportFileRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'exportable' => 'string',
            'export_all' => 'bool',
            'selected_id' => 'array',
            'selected_id.*' => 'numeric',
        ];
    }
}
