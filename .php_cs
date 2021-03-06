<?php

declare(strict_types=1);
$finder = PhpCsFixer\Finder::create()
    ->in(getcwd())
    ->path('src')
    ->path('tests/src')
    ->path('apps/*/src')
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,

        // php7.1+
        '@PHP71Migration' => true,

        // php7+
        'declare_strict_types' => true,

        // custom config
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_unsets' => true,
        'general_phpdoc_annotation_remove' => ['annotations' => ['expectedException', 'expectedExceptionMessage', 'expectedExceptionMessageRegExp']],
        'heredoc_to_nowdoc' => true,
        'no_extra_blank_lines' => ['tokens' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block']],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => ['sortAlgorithm' => 'length'],
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'semicolon_after_instruction' => true,
        'strict_comparison' => true,
        'concat_space' => ['spacing' => 'one'],
        'native_function_invocation' => ['strict' => false],
        'no_superfluous_phpdoc_tags' => false,
        'single_line_throw' => false
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ;
