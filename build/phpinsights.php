<?php
declare(strict_types=1);

return [
    'preset' => 'default',
    'exclude' => [
    ],
    'remove' => [
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenGlobals::class,
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses::class,
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits::class,
        \NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff::class,
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class,
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterCastSniff::class,
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterNotSniff::class,
        \SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff::class,
        \SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff::class,
        \SlevomatCodingStandard\Sniffs\ControlStructures\DisallowEmptySniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff::class
    ],
    'config' => [
        \NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 11
        ],
        \ObjectCalisthenics\Sniffs\Files\ClassTraitAndInterfaceLengthSniff::class => [
            'maxLength' => 300
        ],
        \ObjectCalisthenics\Sniffs\Files\FunctionLengthSniff::class => [
            'maxLength' => 30
        ],
        \ObjectCalisthenics\Sniffs\Metrics\MethodPerClassLimitSniff::class => [
            'maxCount' => 30
        ],
        \SlevomatCodingStandard\Sniffs\Commenting\DocCommentSpacingSniff::class => [
            'linesCountBetweenDifferentAnnotationsTypes' => 0
        ],
        \SlevomatCodingStandard\Sniffs\Namespaces\UseSpacingSniff::class => [
            'linesCountBetweenUseTypes' => 1
        ]
    ]
];
