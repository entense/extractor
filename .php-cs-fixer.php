<?php

declare(strict_types=1);

$rules = [
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
    'list_syntax' => ['syntax' => 'long'],
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'no_unused_imports' => true,
    'not_operator_with_successor_space' => false,
    'trailing_comma_in_multiline' => [
        'elements' => [],
    ],
    'clean_namespace' => true,
    'single_import_per_statement' => false,
    'group_import' => false,
    'unary_operator_spaces' => true,
    'binary_operator_spaces' => true,
    'phpdoc_align' => ['align' => 'left'],
    'phpdoc_single_line_var_spacing' => true,
    'multiline_comment_opening_closing' => true,
    'single_blank_line_at_eof' => true,
    'class_attributes_separation' => [
        'elements' => [
            'method' => 'one',
        ],
    ],
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline',
        'keep_multiple_spaces_after_comma' => false,
    ],
    'single_trait_insert_per_statement' => false,
    'blank_line_after_namespace' => true,
    'array_indentation' => true,
    'no_empty_comment' => false,
    'clean_namespace' => true,
    'phpdoc_trim' => true,
    'phpdoc_var_without_name' => true,
    'phpdoc_scalar' => true,
    'phpdoc_add_missing_param_annotation' => true,
    'phpdoc_annotation_without_dot' => true,
    'new_with_braces' => false,
    'concat_space' => ['spacing' => 'one'],
    'doctrine_annotation_spaces' => true,
    'no_blank_lines_before_namespace' => false,
    'no_leading_namespace_whitespace' => true,
    'single_line_after_imports' => true,
    'no_trailing_whitespace_in_comment' => true,
    'no_whitespace_in_blank_line' => true,
    'no_spaces_after_function_name' => true,
    'no_spaces_inside_parenthesis' => true,
    'blank_line_after_opening_tag' => true,
    'braces' => [
        'allow_single_line_closure' => false,
        'allow_single_line_anonymous_class_with_empty_body' => false,
    ],
    'no_whitespace_before_comma_in_array' => true,
    'trim_array_spaces' => true,
    'fully_qualified_strict_types' => true,
    'no_trailing_comma_in_singleline' => [
        'elements' => [
            'arguments',
            'array',
            'array_destructuring',
            'group_import',
        ],
    ],
    'multiline_whitespace_before_semicolons' => [
        'strategy' => 'no_multi_line',
    ],
    'visibility_required' => true,
    'increment_style' => false,
    'yoda_style' => [
        'equal' => false,
        'identical' => false,
        'less_and_greater' => false,
    ],
    'global_namespace_import' => [
        'import_classes' => true,
        'import_functions' => true,
        'import_constants' => true,
    ],
    'whitespace_after_comma_in_array' => [
        'ensure_single_space' => true,
    ],
];

$excludes = [
    'vendor',
    'storage',
    'node_modules',
];

$config = new PhpCsFixer\Config();
$config->setRules($rules);

return $config;
