<?php
if ( !defined('__PHPTESTS_TRAP_ASSERT_LIB__') ):
       define('__PHPTESTS_TRAP_ASSERT_LIB__',1);
/*
 * Utils to trap assertion into an exception
 */

define('E_FATAL',  E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR);

class TrapErrors
{
    public $errno, $errstr, $errfile, $errline;

    function __construct ( $errorLevel = E_ALL )
    {
        set_exception_handler( array($this,'exceptionHandler') );

        set_error_handler( array($this,'errorHandler'), $errorLevel );

        //register_shutdown_function( array($this,'shutdownHandler') );

        assert_options(ASSERT_ACTIVE        , true);
        assert_options(ASSERT_WARNING       , false);
        assert_options(ASSERT_BAIL          , false);
        assert_options(ASSERT_QUIET_EVAL    , true);
        assert_options(ASSERT_CALLBACK      , array($this,'assertHandler') );
    }

    function assertHandler ()
    {
        throw new Exception('Assert trapped !');
    }

    //function exceptionHandler ( Exception $exception )
    function exceptionHandler ( $exception )
    {
        $this->errno    = $exception->getCode();// errno;
        $this->errstr   = $exception->getMessage();// errstr;
        $this->errfile  = $exception->getFile();// errfile;
        $this->errline  = $exception->getLine();// errline;
    }

    function errorHandler ( $errno, $errstr, $errfile, $errline )
    {
        // error was suppressed with the @-operator
        if ( 0 === error_reporting() ) { return false; }
        
        switch ( $errno )
        {
            case E_ERROR:               throw new ErrorException            ($errstr, 0, $errno, $errfile, $errline);
            case E_WARNING:             throw new WarningException          ($errstr, 0, $errno, $errfile, $errline);
            case E_PARSE:               throw new ParseException            ($errstr, 0, $errno, $errfile, $errline);
            case E_NOTICE:              throw new NoticeException           ($errstr, 0, $errno, $errfile, $errline);
            case E_CORE_ERROR:          throw new CoreErrorException        ($errstr, 0, $errno, $errfile, $errline);
            case E_CORE_WARNING:        throw new CoreWarningException      ($errstr, 0, $errno, $errfile, $errline);
            case E_COMPILE_ERROR:       throw new CompileErrorException     ($errstr, 0, $errno, $errfile, $errline);
            case E_COMPILE_WARNING:     throw new CoreWarningException      ($errstr, 0, $errno, $errfile, $errline);
            case E_USER_ERROR:          throw new UserErrorException        ($errstr, 0, $errno, $errfile, $errline);
            case E_USER_WARNING:        throw new UserWarningException      ($errstr, 0, $errno, $errfile, $errline);
            case E_USER_NOTICE:         throw new UserNoticeException       ($errstr, 0, $errno, $errfile, $errline);
            case E_STRICT:              throw new StrictException           ($errstr, 0, $errno, $errfile, $errline);
            case E_RECOVERABLE_ERROR:   throw new RecoverableErrorException ($errstr, 0, $errno, $errfile, $errline);
            case E_DEPRECATED:          throw new DeprecatedException       ($errstr, 0, $errno, $errfile, $errline);
            case E_USER_DEPRECATED:     throw new UserDeprecatedException   ($errstr, 0, $errno, $errfile, $errline);
            case E_ALL:                 throw new AllException              ($errstr, 0, $errno, $errfile, $errline);
            default:                    throw new OtherException            ($errstr, 0, $errno, $errfile, $errline);
        }

    }

    // For Fatal Error catching.
    function shutdownHandler ()
    {
        $error = error_get_last();
        if ( $error && ($error['type'] & E_FATAL) )
        {
           $this->errorHandler( $error['type'], $error['message'], $error['file'], $error['line'] );
        }
    }
}

class WarningException              extends ErrorException {}
class ParseException                extends ErrorException {}
class NoticeException               extends ErrorException {}
class CoreErrorException            extends ErrorException {}
class CoreWarningException          extends ErrorException {}
class CompileErrorException         extends ErrorException {}
class CompileWarningException       extends ErrorException {}
class UserErrorException            extends ErrorException {}
class UserWarningException          extends ErrorException {}
class UserNoticeException           extends ErrorException {}
class StrictException               extends ErrorException {}
class RecoverableErrorException     extends ErrorException {}
class DeprecatedException           extends ErrorException {}
class UserDeprecatedException       extends ErrorException {}
class AllException                  extends ErrorException {}
class OtherException                extends ErrorException {}

$errors = new TrapErrors();

endif;