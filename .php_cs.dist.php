<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setUsingCache(false)
    ->setRules([
        'strict_param' => true,
        'strict_comparison' => true,
        'array_syntax' => ['syntax' => 'short'],
        'phpdoc_to_comment' => false,
    ])
    ->setFinder($finder)
;
