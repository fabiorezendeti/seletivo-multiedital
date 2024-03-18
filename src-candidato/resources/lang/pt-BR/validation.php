<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'O campo :attribute deve ser aceito.',
    'active_url' => 'O campo :attribute não é uma URL Válida.',
    'after' => 'O campo :attribute deve ter uma data depois de :date.',
    'after_or_equal' => 'O campo :attribute deve ser uma data posterior ou igual a :date.',
    'alpha' => 'O campo :attribute deve conter somente letras.',
    'alpha_dash' => 'O campo :attribute deve conter somente letras, números, traços ou _ sublinhado.',
    'alpha_num' => 'O campo :attribute deve conter letras ou números.',
    'array' => 'O campo :attribute deve ser um array.',
    'before' => 'O campo :attribute deve ser uma data antes de :date.',
    'before_or_equal' => 'O campo :attribute deve ser uma data anterior ou igual a :date.',
    'between' => [
        'numeric' => 'O campo :attribute deve estar entrar :min e :max.',
        'file' => 'O campo :attribute deve estar entre :min e :max kilobytes.',
        'string' => 'O campo :attribute deve estar entre :min e :max caracteres.',
        'array' => 'O campo :attribute deve ter entre :min e :max itens.',
    ],
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'O campo :attribute não corresponde com a confirmação.',
    'date' => 'O campo :attribute não é uma data válida.',
    'date_equals' => 'O campo :attribute deve ser igual a :date.',
    'date_format' => 'O campo :attribute não está no formato :format.',
    'different' => 'O campo :attribute e :other devem ser diferentes.',
    'digits' => 'O campo :attribute deve ter :digits dígitos.',
    'digits_between' => 'O campo :attribute deve estar entre :min e :max dígitos.',
    'dimensions' => 'O campo :attribute tem dimensões inválidas de imagem.',
    'distinct' => 'O campo :attribute tem um valor duplicado.',
    'email' => 'O campo :attribute deve ser um e-mail válido.',
    'ends_with' => 'O campo :attribute deve terminar com um dos seguintes valores: :values.',
    'exists' => 'A seleção :attribute é inválida.',
    'file' => 'O campo :attribute deve ser um arquivo.',
    'filled' => 'O campo :attribute deve ser preenchido.',
    'gt' => [
        'numeric' => 'O campo :attribute deve ser maior que :value.',
        'file' => 'O :attribute deve ter mais que :value kilobytes.',
        'string' => 'O campo :attribute deve ter mais que :value caracteres.',
        'array' => 'O campo :attribute deve ter mais que :value itens.',
    ],
    'gte' => [
        'numeric' => 'O campo :attribute deve ser maior ou igual que :value.',
        'file' => 'O campo :attribute deve ser maior ou igual que :value kilobytes.',
        'string' => 'O campo :attribute deve ser maior ou igual que :value characters.',
        'array' => 'O campo :attribute deve ter :value itens ou mais.',
    ],
    'image' => 'O campo :attribute deve conter uma imagem.',
    'in' => 'A seleção :attribute é inválida.',
    'in_array' => 'O campo :attribute não existe em :other.',
    'integer' => 'O campo :attribute deve ser um inteiro.',
    'ip' => 'O campo :attribute deve ser um IP válido.',
    'ipv4' => 'O campo :attribute deve ser um IPv4 válido.',
    'ipv6' => 'O campo :attribute deve ser um Ipv6 válido.',
    'json' => 'O campo :attribute deve ser um JSON válido.',
    'lt' => [
        'numeric' => 'O campo :attribute deve ser menor que :value.',
        'file' => 'O campo :attribute deve ser menor que :value kilobytes.',
        'string' => 'O campo :attribute deve ser menor que :value caracteres.',
        'array' => 'O campo :attribute deve conter menos que :value itens.',
    ],
    'lte' => [
        'numeric' => 'O campo :attribute deve ser menor ou igual que :value.',
        'file' => 'O campo :attribute deve ser menor ou igual que :value kilobytes.',
        'string' => 'O campo :attribute deve ser menor ou igual que :value characters.',
        'array' => 'O campo :attribute não deve ter mais que :value itens.',
    ],
    'max' => [
        'numeric' => 'O campo :attribute não pode ser maior que :max.',
        'file' => 'O campo :attribute não pode ter mais que :max kilobytes.',
        'string' => 'O campo :attribute não pode ser maior que :max caracteres.',
        'array' => 'O campo :attribute não pode ter mais que :max itens.',
    ],
    'mimes' => 'O campo :attribute deve ter um arquivo do tipo: :values.',
    'mimetypes' => 'O campo :attribute deve ter um arquivo do tipo: :values.',
    'min' => [
        'numeric' => 'O campo :attribute deve ser no mínimo :min.',
        'file' => 'O campo :attribute deve ter no mínimo :min kilobytes.',
        'string' => 'O campo :attribute deve ter no mínimo :min caracteres.',
        'array' => 'O campo :attribute deve ter no mínimo :min itens.',
    ],
    'not_in' => 'A seleção :attribute é inválida.',
    'not_regex' => 'O campo :attribute tem formato inválido.',
    'numeric' => 'O campo :attribute deve ser um número.',
    'password' => 'A senha está incorreta.',
    'present' => 'O campo :attribute deve está presente.',
    'regex' => 'O campo :attribute tem formato inválido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório quando o campo :other for selecionado.',
    'required_unless' => 'O campo :attribute é obrigatório a menos que  :other esteja em :values.',
    'required_with' => 'O campo :attribute é obrigatório :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values estão presentes.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum dos valores :values estão presentes.',
    'same' => 'O campo :attribute e :other deve ser iguais.',
    'size' => [
        'numeric' => 'O campo :attribute deve ter :size.',
        'file' => 'O campo :attribute deve ter :size kilobytes.',
        'string' => 'O campo :attribute deve ter :size caracteres.',
        'array' => 'O campo :attribute deve conter :size itens.',
    ],
    'starts_with' => 'O campo :attribute deve iniciar com um dos seguintes valores: :values.',
    'string' => 'O campo :attribute deve ser uma string.',
    'timezone' => 'O campo :attribute deve ser um timezone válido.',
    'unique' => 'O campo :attribute já existe.',
    'uploaded' => 'O campo :attribute falhou ao carregar.',
    'url' => 'O campo :attribute está em um formato inválido.',
    'uuid' => 'O campo :attribute deve ser uma UUID válida.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
