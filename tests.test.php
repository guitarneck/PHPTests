<?php
include_once 'PHPTests.lib.php';
include_once 'PHPTestsTrapAssert.lib.php';

Tests::start();

Tests::suite('Testing Tests methods');
  Tests::test( method_exists( 'Tests', 'start'), 'it should have "start" method' );
  Tests::test( method_exists( 'Tests', 'suite'), 'it should have "suite" method' );
  Tests::test( method_exists( 'Tests', 'test'), 'it should have "test" method' );
  Tests::test( method_exists( 'Tests', 'end'), 'it should have "end" method' );
  Tests::test( method_exists( 'Tests', 'mstime'), 'it should have "mstime" method' );

Tests::suite('Testing Tests lib');
  Tests::test( true, 'this should success');
  Tests::test( false, 'This should failed');

Tests::suite('Testing Trap Assertion');
  $catched = false;
  try { $oops = 45/0; } catch (Exception $e) { $catched = true; }
  Tests::test( $catched ,'This should be catched');
  try { throw new WarningException('Warning !'); } catch (Exception $e) { $catched = true; }
  Tests::test( $catched ,'This should be catched too');

Tests::suite('Testing Scenarii');
  TestsScenarii::init(['test this','test that']);
  function testedMock ()
  {
    return TestsScenarii::is('test this') ? true : false;
  }
  TestsScenarii::set('test this'); // switching to 'test this'
  Tests::test( testedMock() == true, 'It should have tested this');
  TestsScenarii::set('test that'); // switching to 'test that'
  Tests::test( testedMock() == false, 'It should not have tested this, but that'); 
  
Tests::end();