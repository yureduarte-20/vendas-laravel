<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendaRequest extends FormRequest
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
        return [
            'parcelas' => [
                'array',
                'required',
                'min:1'
            ],
            'parcelas.*.vencimento' => [ 'required_with:parcelas.*', 'date_format:Y-m-d'],
            'parcelas.*.meio_pagamento' => ['exists:forma_pagamentos,id', 'required_with:parcelas.*'],
            'parcelas.*.valor' => ['numeric', 'required_with:parcelas.*', 'min:1'],
            'total' => ['required', 'numeric'],
            'produtos' => ['array', 'min:1', 'required'],
            'produtos.*.item' => [
                'required_with:produtos.*',
                'exists:produtos,id',
                'integer'
            ],
            'produtos.*.valor' => ['numeric', 'min:1', 'required_with:produtos.*'],
            'produtos.*.qtde' => ['numeric', 'min:1', 'required_with:produtos.*'],
            'cliente' => ['required', 'exists:clientes,id']
        ];
    }
}
