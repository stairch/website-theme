<?php
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'node_modules',
        '.git',
    ])
    ->name('*.php');

$config = new PhpCsFixer\Config();
return $config
    ->setRules([
        '@PSR12' => true,
        'statement_indentation' => false,
        'control_structure_continuation_position' => false,
        'no_closing_tag' => false,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => true,
        'no_unused_imports' => true,
        'braces_position' => [
            'functions_opening_brace' => 'same_line',
        ],
        'single_line_comment_spacing' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'single_blank_line_at_eof' => true,
        'no_extra_blank_lines' => true,
        'single_quote' => true,
        'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        'trailing_comma_in_multiline' => true,
        'no_whitespace_before_comma_in_array' => true,
    ])
    ->setFinder($finder);
