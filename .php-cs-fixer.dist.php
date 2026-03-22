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
        'single_line_comment_spacing' => true,
        'no_unused_imports' => true,
        'braces_position' => [
            'functions_opening_brace' => 'same_line',
        ],
    ])
    ->setFinder($finder);
