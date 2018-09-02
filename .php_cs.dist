<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

$config = PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR1' => true, // Apply PSR-1
        '@PSR2' => true, // Apply PSR-2

        'align_multiline_comment' => true, // Each line of multi-line DocComments must have an asterisk [PSR-5] and must be aligned with the first one.
        'array_indentation' => true, // Each element of an array must be indented exactly once.
        'array_syntax' => ['syntax' => 'short'], // PHP arrays should be declared using the configured syntax.
        'backtick_to_shell_exec' => true, // Converts backtick operators to shell_exec calls.
        'binary_operator_spaces' => ['default' => 'single_space'], // Binary operators should be surrounded by space as configured.
        'blank_line_after_opening_tag' => true, // Ensure there is no code on the same line as the PHP open tag and it is followed by a blank line.
        'cast_spaces' => true, // A single space or none should be between cast and variable.
        'class_attributes_separation' => true, // Class, trait and interface elements must be separated with one blank line.
        'class_keyword_remove' => true, // Converts ::class keywords to FQCN strings.
        'combine_consecutive_issets' => true, // Converts ::class keywords to FQCN strings.
        'combine_consecutive_unsets' => true, // Calling unset on multiple items should be done in one call.
        'compact_nullable_typehint' => true, // Remove extra spaces in a nullable typehint.
        'concat_space' => ['spacing' => 'one'], // Concatenation should be spaced according configuration.
        'declare_equal_normalize' => ['space' => 'single'], // Equal sign in declare statement should be surrounded by spaces or not following configuration.
        'dir_constant' => true, // Replaces dirname(__FILE__) expression with equivalent __DIR__ constant.
        'ereg_to_preg' => true, // Replace deprecated ereg regular expression functions with preg.
        'explicit_indirect_variable' => true, // Add curly braces to indirect variables to make them clear to understand. Requires PHP >= 7.0.
        'fully_qualified_strict_types' => true, // Transforms imported FQCN parameters and return types in function arguments to short version.
        'function_typehint_space' => true, // Add missing space between function's argument and its typehint.
        'include' => true, // Include/Require and file path should be divided with a single space. File path should not be placed under brackets.
        'increment_style' => ['style' => 'pre'], // Pre- or post-increment and decrement operators should be used if possible.
        'line_ending' => true, // All PHP files must use same line ending.
        'list_syntax' => ['syntax' => 'short'], // List (array destructuring) assignment should be declared using the configured syntax. Requires PHP >= 7.1.
        'logical_operators' => true, // Use && and || logical operators instead of and and or.
        'lowercase_cast' => true, // Cast should be written in lower case.
        'lowercase_static_reference' => true, // Class static references self, static and parent MUST be in lower case.
        'magic_constant_casing' => true, // Magic constants should be referred to using the correct casing.
        'mb_str_functions' => true, // Replace non multibyte-safe functions with corresponding mb function.
        'modernize_types_casting' => true, // Replaces intval, floatval, doubleval, strval and boolval function calls with according type casting operator.
        'multiline_comment_opening_closing' => true, // DocBlocks must start with two asterisks, multiline comments must start with a single asterisk, after the opening slash. Both must end with a single asterisk before the closing slash.
        'multiline_whitespace_before_semicolons' => true, // Forbid multi-line whitespace before the closing semicolon or move the semicolon to the new li  ne for chained calls.
        'native_function_casing' => true, // Function defined by PHP should be called using the correct casing.
        'new_with_braces' => true, // All instances created with new keyword must be followed by braces.
        'no_alias_functions' => true, // Master functions shall be used instead of aliases.
        'no_alternative_syntax' => true, // Replace control structure alternative syntax to use braces.
        'no_blank_lines_after_class_opening' => true, // There should be no empty lines after class opening brace.
        'no_empty_comment' => true, // There should not be any empty comments.
        'no_empty_phpdoc' => true, // There should not be empty PHPDoc blocks.
        'no_empty_statement' => true, // Remove useless semicolon statements.
        'no_extra_blank_lines' => ['tokens' => [ // Removes extra blank lines and/or blank lines following configuration.
            'break',
            'case',
            'continue',
            'curly_brace_block',
            'default',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'switch',
            'throw',
            'use',
            'useTrait',
            'use_trait',
        ]],
        'no_homoglyph_names' => true, // Replace accidental usage of homoglyphs (non ascii characters) in names.
        'no_leading_import_slash' => true, // Remove leading slashes in use clauses.
        'no_leading_namespace_whitespace' => true, // The namespace declaration line shouldn't contain leading whitespace.
        'no_mixed_echo_print' => ['use' => 'echo'], // Either language construct print or echo should be used.
        'no_multiline_whitespace_around_double_arrow' => true, // Operator => should not be surrounded by multi-line whitespaces.
        'no_null_property_initialization' => true, // Properties MUST not be explicitly initialized with null.
        'no_php4_constructor' => true, // Convert PHP4-style constructors to __construct.
        'no_short_bool_cast' => true, // Short cast bool using double exclamation mark should not be used.
        'no_short_echo_tag' => true, // Replace short-echo <?= with long format <?php echo syntax.
        'no_singleline_whitespace_before_semicolons' => true, // Single-line whitespace before closing semicolon are prohibited.
        'no_spaces_around_offset' => true, // There MUST NOT be spaces around offset braces.
        'no_trailing_comma_in_singleline_array' => true, // PHP single-line arrays should not have trailing comma.
        'no_unneeded_control_parentheses' => true, // Removes unneeded parentheses around control statements.
        'no_unneeded_curly_braces' => true, // Removes unneeded curly braces that are superfluous and aren't part of a control structure's body.
        'no_unneeded_final_method' => true, // A final class must not have final methods.
        'no_unreachable_default_argument_value' => true, // In function arguments there must not be arguments with default values before non-default ones.
        'no_unused_imports' => true, // Unused use statements must be removed.
        'no_useless_return' => true, // There should not be an empty return statement at the end of a function.
        'no_whitespace_before_comma_in_array' => true, // In array declaration, there MUST NOT be a whitespace before each comma.
        'no_whitespace_in_blank_line' => true, // Remove trailing whitespace at the end of blank lines.
        'non_printable_character' => true, // Remove Zero-width space (ZWSP), Non-breaking space (NBSP) and other invisible unicode symbols.
        'normalize_index_brace' => true, // Array index should always be written by using square braces.
        'object_operator_without_whitespace' => true, // There should not be space before or after object T_OBJECT_OPERATOR ->.

        'phpdoc_add_missing_param_annotation' => true, // Phpdoc should contain @param for all params.
        'phpdoc_align' => ['align' => 'left'], // All items of the given phpdoc tags must be either left-aligned or (by default) aligned vertically.
        'phpdoc_indent' => true, // Docblocks should have the same indentation as the documented subject.
        'phpdoc_scalar' => true, // Scalar types should always be written in the same form. int not integer, bool not boolean, float not real or double.
        'phpdoc_separation' => true, // Annotations in phpdocs should be grouped together so that annotations of the same type immediately follow each oth  er, and annotations of a different type are separated by a single blank line.
        'phpdoc_single_line_var_spacing' => true, // Single line @var PHPDoc should have proper spacing.
        'phpdoc_to_comment' => true, // Docblocks should only be used on structural elements.
        'phpdoc_types' => true, // The correct case must be used for standard PHP types in PHPDoc.
        'phpdoc_var_without_name' => true, // @var and @type annotations should not contain the variable name.

        'pow_to_exponentiation' => true, // Converts pow to the ** operator.
        'random_api_migration' => true, // Replaces rand, srand, getrandmax functions calls with their mt_* analogs.
        'return_assignment' => true, // Local, dynamic and directly referenced variables should not be assigned and directly returned by a function or method.
        'return_type_declaration' => true, // There should be one or no space before colon, and one space after it in return type declarations, according to configuration.
        'self_accessor' => true, // Inside class or interface element self should be preferred to the class name itself.
        'semicolon_after_instruction' => true, // Instructions must be terminated with a semicolon.
        'set_type_to_cast' => true, // Cast shall be used, not settype.
        'short_scalar_cast' => true, // Cast (boolean) and (integer) should be written as (bool) and (int), (double) and (real) as (float).
        'single_blank_line_before_namespace' => true, // There should be exactly one blank line before a namespace declaration.
        'single_line_comment_style' => true, // Single-line comments and multi-line comments with only one line of actual content should use the // syntax  .
        'single_quote' => true, // Convert double quotes to single quotes for simple strings.
        'space_after_semicolon' => true, // Fix whitespace after a semicolon.
        'standardize_increment' => true, // Increment and decrement operators should be used if possible.
        'standardize_not_equals' => true, // Replace all <> with !=.
        'string_line_ending' => true, // All multi-line strings must use correct line ending.
        'ternary_operator_spaces' => true, // Standardize spaces around ternary operator.
        'trailing_comma_in_multiline_array' => true, // PHP multi-line arrays should have a trailing comma.
        'trim_array_spaces' => true, // Arrays should be formatted like function/method arguments, without leading or trailing single line space.
        'unary_operator_spaces' => true, // Unary operators should be placed adjacent to their operands.
        'whitespace_after_comma_in_array' => true, // In array declaration, there MUST be a whitespace after each comma.

    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    )
;

return $config;
