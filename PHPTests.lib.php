<?php
if ( !defined('__PHPTESTS_TESTS_LIB__') ):
       define('__PHPTESTS_TESTS_LIB__',1);
/**
 * A simpe test lib with reporter
 */

error_reporting(E_ALL);
ini_set("display_errors",true);

require_once 'PHPTestsDisplay.class.php';
require_once 'PHPTestsScenarii.lib.php';

/**
 * Tests reporter light
 * @author Laurent
 * @example
 * Tests::suite('testing some stuffs');
 *    Tests::test(true == false,It should failed');
 * Tests::end();
 */
class Tests
{
    static $test_count = 0;
    static $test_pass  = 0; 
    static $test_start = -1;
    
    static $display;
        
    static
    function start ( $title=null )
    {
        self::$test_start = self::mstime();
        
        self::$display = TestsDisplay::create();
        self::$display->header($title);
    }
    
    static
    function suite ( $label='A test suite' )
    {
        self::$display->suite($label);
    }

    static
    function test ( $case=false, $label='It should failed' )
    {
        self::$test_count++;
        if( $case == true )
        {            
            self::$display->success($label);
            self::$test_pass++;
        } else {
            self::$display->failure($label);
        }
    }

    static
    function end ()
    {
        $delta = self::mstime() - self::$test_start;
        self::$display->end( $delta, self::$test_count, self::$test_pass );
        self::$display->footer();
    }

    static
    function mstime ()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

}

endif;