<?php
if ( !defined('__PHPTESTS_TESTS_SCENARII_LIB__') ):
       define('__PHPTESTS_TESTS_SCENARII_LIB__',1);
/**
 * Tests scenarii helper for switching testing cases.
 * @author guitarneck
 * @example
 * TestsScenarii::init(['test this','test that']);
 * ...
 *  // A mocked function returning true or false
 *  function testedMock ()
 *  {
 *      return TestsScenarii::is('test this') ? true : false;
 *  }
 * ...
 *  TestsScenarii::set('test this'); // switching to 'test this'
 *      Tests::test( testedMock() == true, 'It should have tested this');
 *  TestsScenarii::set('test that'); // switching to 'test that'
 *      Tests::test( testedMock() == false, 'It should not have tested this, but that');
 */
class TestsScenarii
{
    static $state;
    static $cases;

    static
    function init ( $scenarii=array() )
    {
        self::$cases = $scenarii;
    }
    static
    function set  ( $scenario )
    {
        assert( in_array($scenario,self::$cases) );
        self::$state = $scenario;
    }
    static
    function is ( $scenario )
    {
        return self::$state == $scenario;
    }
}
endif;