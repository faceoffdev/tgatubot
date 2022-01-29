<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'node_modules',
        'storage',
        'bootstrap/cache',
    ])
    ->notName(['_ide_helper*']);

return (new Config('@Symfony'))
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        '@Symfony'                              => true,
        '@PSR1'                                 => true,
        '@PSR2'                                 => true,
        '@PSR12'                                => true,
        '@PHP80Migration'                       => true,
        'align_multiline_comment'               => ['comment_type' => 'phpdocs_like'],
        'combine_consecutive_unsets'            => true,
        'concat_space'                          => ['spacing' => 'one'],
        'heredoc_to_nowdoc'                     => true,
        'no_alias_functions'                    => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_return'                     => true,
        'ordered_imports'                       => [
            'sort_algorithm' => 'alpha',
        ],
        'global_namespace_import' => true,
        'no_unused_imports'       => true,
        'phpdoc_align'            => [
            'align' => 'vertical',
            'tags'  => [
                'param',
                'property',
                'return',
                'throws',
                'type',
                'var',
            ],
        ],
        'random_api_migration'       => true,
        'ternary_to_null_coalescing' => true,
        'yoda_style'                 => [
            'equal'            => false,
            'identical'        => false,
            'less_and_greater' => false,
        ],
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align_single_space_minimal',
                '='  => 'align_single_space_minimal',
            ],
        ],
    ]);
