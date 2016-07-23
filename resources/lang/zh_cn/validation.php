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

    'accepted'             => ':attribute must be accepted.',
    'active_url'           => ':attribute 不是一个合法的 URL',
    'after'                => ':attribute 必须为 :date 之后的一个日期',
    'alpha'                => ':attribute 只能包含英文字母',
    'alpha_dash'           => ':attribute 只能包含英文字母、数字、减号',
    'alpha_num'            => ':attribute 只能包含英文字母、数字',
    'array'                => ':attribute 必须为一个数组',
    'before'               => ':attribute 必须为 :date 之前的一个日期',
    'between'              => [
        'numeric' => ':attribute 必须是 :min 到 :max 的数字',
        'file'    => ':attribute 的长度必须是 :min 到 :max KB',
        'string'  => ':attribute 必须包含 :min 到 :max 个字符',
        'array'   => ':attribute 必须包含 :min 到 :max 个数据项',
    ],
    'boolean'              => ':attribute 必须为 true 或者 false',
    'confirmed'            => ':attribute 不匹配',
    'date'                 => ':attribute 不是一个合法的日期',
    'date_format'          => ':attribute 不符合格式 :format.',
    'different'            => ':attribute 和 :other 不能相同',
    'digits'               => ':attribute 必须是长度为 :digits 的数字',
    'digits_between'       => ':attribute 必须是 :min 和 :max 之间的数字',
    'distinct'             => ':attribute 已存在',
    'email'                => ':attribute 必须是一个合法的Email地址',
    'exists'               => ':attribute 已存在',
    'filled'               => ':attribute 不能为空',
    'image'                => ':attribute 必须是一个图片',
    'in'                   => ':attribute 不合法',
    'in_array'             => ':attribute 不能属于 :other.',
    'integer'              => ':attribute 必须是一个整数',
    'ip'                   => ':attribute 必须是一个合法的IP地址',
    'json'                 => ':attribute 必须是一个合法的JSON字符串',
    'max'                  => [
        'numeric' => ':attribute 不能大于 :max',
        'file'    => ':attribute 不能大于 :max KB',
        'string'  => ':attribute 不能包含多于 :max 个字符',
        'array'   => ':attribute 不能包含多于 :max 个数据项',
    ],
    'mimes'                => ':attribute 必须是一个 :values 类型的文件',
    'min'                  => [
        'numeric' => ':attribute 必须大于 :min.',
        'file'    => ':attribute 必须大于 :min KB',
        'string'  => ':attribute 必须包含至少 :min 个字符',
        'array'   => ':attribute 必须包含至少 :min 个数据项',
    ],
    'not_in'               => ':attribute 不合法',
    'numeric'              => ':attribute 必须是一个数字',
    'present'              => ':attribute 必须出现',
    'regex'                => ':attribute 格式不正确',
    'required'             => ':attribute 不能为空',
    'required_if'          => '当 :other 为 :value 时，:attribute 不能为空',
    'required_unless'      => '当 :other 不属于 :values 时，:attribute 不能为空',
    'required_with'        => '当 :values 不为空时，:attribute 不能为空',
    'required_with_all'    => '当 :values 都不为空时，:attribute 不能为空',
    'required_without'     => '当 :values 为空时， :attribute 不能为空',
    'required_without_all' => '当 :values 全部为空时，:attribute 不能为空',
    'same'                 => ':attribute 必须和 :other 一致',
    'size'                 => [
        'numeric' => ':attribute 的长度必须为 :size.',
        'file'    => ':attribute 的长度必须为 :size KB',
        'string'  => ':attribute 必须是一个长度为 :size 的字符串',
        'array'   => ':attribute 必须包含 :size 个数据项',
    ],
    'string'               => ':attribute 必须是一个字符串',
    'timezone'             => ':attribute 必须是一个合法的时区',
    'unique'               => ':attribute 已经被占用',
    'url'                  => ':attribute 格式不正确',

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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
