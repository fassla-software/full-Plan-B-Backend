<?php

namespace Modules\Chat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FetchChatRecordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "freelancer_id" => "required|exists:users,id",
            "client_id" => "required|exists:users,id",
            "project_id" => "nullable|exists:projects,id",
            "from_user" => "required"
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge(
            $this->from_user == 1 ? ["client_id" => auth('web')->id()] : ["freelancer_id" => auth('web')->id()]
            + ['from_user' => $this->from_user == 1 ? 1 : 2]
        );
    }

    public function authorize(): bool
    {
        return true;
    }
}
