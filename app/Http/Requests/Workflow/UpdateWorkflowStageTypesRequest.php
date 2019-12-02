<?php

namespace App\Http\Requests\Workflow;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Workflow\WorkflowStageType;

class UpdateWorkflowStageTypesRequest extends FormRequest
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
        $rules = WorkflowStageType::$rules;

        $rules['slug'] = 'required|bail|string|unique:workflow_types,id,'.$this->input('slug');
        
        return $rules;
    }
}
