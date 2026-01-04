<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $message = $this->input('message');

        if (is_string($message)) {
            $message = trim($message);
        }

        $this->merge([
            'message' => $message === '' ? null : $message,
        ]);
    }

    public function rules(): array
    {
        return [
            'message' => 'nullable|string|required_without:file',
            'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt',
        ];
    }

    public function messages(): array
    {
        return [
            'message.required_without' => 'Either message or file must be provided',
            'file.max' => 'File size exceeds 10MB limit',
        ];
    }
}
