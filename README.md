# Tests - Another PHP Unit Test library

Yet another Unit Testing library !

I made that one because I don't like PHPUnit, too much complicated and too much Java way stylized. Adding to this, a ton of 'assert this' or 'assert that' while I only need a 'case == true' assertion.

I need clear and simple test cases, without wondering what 'same' means inside a framework or a library.

This library contains 4 files :
- PHPTests.lib.php
- PHPTestsTrapAssert.lib.php
- PHPTestsScenarii.lib.php
- PHPTestsDisplay.class.php

## The library

### PHPTests.lib.php

This is the main file that should be included in your unit testing script.

### PHPTestsTrapAssert.lib.php

This library is an exception's trapper. Usefull for unit testing on exception. Inluded it if needed.

### PHPTestsScenarii.lib.php

This library is a switcher for unit testing cases. [auto included]

### PHPTestsDisplay.class.php

The only 'class' of this package, render output upon 'cli' or 'html'. [auto included]

## Other files

### test.test.php

The unit test for this library.
> Have a look, it's just that simple and it's up to you to have a clear test code.

### makefile

The makefile to run with `make` in a shell (Terminal.app, command.exe, etc.). Two rules :

| Rule |                                   |
|:-----|:----------------------------------|
| cli  | To run test.test.php in cli mode.  |
| html | To run test.test.php in HTML mode. |

```shell
make cli
```

> Have a look to the makefile if you don't know how to launch a script with PHP. The test files can't be a part of a web server dev.

# Unit Test

There's only four methods to know :

* `Tests::start( [string $title] )`
* `Tests::suite( string $chapter )`
* `Tests::test( bool $case, string $text_for_that_case )`
* `Tests::end()`

## `Tests::start([string $title])`
The begin of all your test cases, with an optional `$title`.

## `Tests::suite( string $chapter )`
A chapter in your test cases. For create a specific block

## `Tests::test( bool $case, string $text_for_that_case )`
The test case itself. Where `$case` must be `true` (So `$case == false`, if you wish a false result case), and `$text_for_that_case` is a text to describe this test case.

## `Tests::end()`
The end of all the test cases. With a final report.

# Install

Clone or download.

# License

[MIT © guitarneck](./LICENSE)