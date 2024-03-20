<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVendaRequest extends FormRequest
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
                'array:valor,vencimento,meio_pagamento',
                'required'
            ],
            'parcelas.*.vencimento' => ['required', 'date'],
            'parcelas.*.meio_pagamento' => ['exists:forma_pagamentos'],
            'total' => ['required', 'decimal:2'],
            'produtos' => ['array:item,qtde'],
            'produtos.*.item' => ['exists:produtos', 'required'],
            'produtos.*.qtde' => ['numeric', 'min:1'],
            'cliente_id' => ['required', 'exists:clientes']

        ];
    }
}
