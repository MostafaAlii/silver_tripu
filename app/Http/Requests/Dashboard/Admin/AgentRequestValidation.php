<?php
namespace App\Http\Requests\Dashboard\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class AgentRequestValidation extends FormRequest {
    public function authorize() {
        return true;
    }

    public function rules(){
        $id = $this->route('agent');
        $rules = [
            'name' => 'required|string|max:255',
            'email' => $this->getEmailRules($id),
            'status' => 'required|in:active,inactive',
            
        ];
        if ($this->isMethod('post')) {
            $rules['country_id'] = 'required|exists:countries,id';
            $rules['phone'] = ['required', 'string', 'max:255', 'unique:agents,phone'];
            $rules['password'] = 'required|string|min:6';
            
        } else {
            $rules['country_id'] = 'nullable|exists:countries,id';
            $rules['phone'] = ['nullable', 'string', 'max:255', Rule::unique('agents', 'phone')->ignore($id)];
        }
        return $rules;
    }

    private function getEmailRules($id) {
        return [
            'required',
            'email',
            Rule::unique('agents', 'email')->ignore($id),
        ];
    }
}