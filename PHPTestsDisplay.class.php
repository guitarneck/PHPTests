<?php
if ( !defined('__PHPTESTS_TESTS_DISPLAY_CLASS__') ):
       define('__PHPTESTS_TESTS_DISPLAY_CLASS__',1);

if ( strtolower(substr(PHP_OS,0,3)) == 'win' ):
    define('ticks', '-');
    define('cross', 'X');
else:
    define('ticks', '✔');
    define('cross', '✘');
endif;

class TestsDisplay
{
    static $is_cli = null;
    static $is_col = null;

    static
    function initialize ()
    {
        static::$is_cli = php_sapi_name() == 'cli';
        static::$is_col = static::$is_cli && preg_match('/^xterm.*color$/',$_SERVER["TERM"]);
    }

    static
    function isCli () { return static::$is_cli; }

    static
    function isColor () { return static::$is_col; }

    static
    function create ()
    {
        if ( static::$is_cli == null ) static::initialize();

        if ( static::$is_cli )
            return new TestsDisplayCli();
        else
            return new TestsDisplayHTML();
    }

    function msu ( $ms )
    {
        $input = $ms * 1000.0;

        $uSec = $input % 1000;
        $input = floor($input / 1000);
        
        $seconds = $input % 60;
        $input = floor($input / 60);
        
        $minutes = $input % 60;
        //$input = floor($input / 60); // etc.
        
        return $minutes.'m '.$seconds.'s '.$uSec.'ms';        
    }

    function header ($title=null) {}
    function suite  ($label) {}
    function success ($label) {}
    function failure ($label) {}
    function end ($ms, $total, $pass) {}
    function footer () {}
}

define('col_end'    ,"\033[0m");
define('col_white'  ,"\033[1;37m");
define('col_red'    ,"\033[31m");
define('col_green'  ,"\033[32m");
define('col_cyan'  ,"\033[36m");

/*
_BLUE=$(shell echo "\x1b[34m")
_MAGENTA=$(shell echo "\x1b[35m")
_CYAN=$(shell echo "\x1b[36m")
*/

class TestsDisplayCli extends TestsDisplay
{
    function color ( $txt, $color, $color_end=col_end )
    {
        if ( self::isColor() )
            return "$color$txt$color_end";
        else
            return $txt;
    }

    function colorWhite ( $txt )
    {
        return $this->color($txt,col_white);
    }

    function colorRed ( $txt )
    {
        return $this->color($txt,col_red);
    }
    
    function colorGreen ( $txt )
    {
        return $this->color($txt,col_green);
    }

    function header ($title=null)
    {
        if($title==null) return;
        echo PHP_EOL.'['.$this->colorWhite($title).']'.PHP_EOL;
    }

    function suite  ($label)
    {
        echo PHP_EOL."  ".$this->colorWhite($label).PHP_EOL;
        echo "  ".$this->colorWhite(str_repeat("-", strlen($label))).PHP_EOL;
    }
    function success ($label)
    {
        echo "    ".$this->colorGreen(ticks).' '.$label.PHP_EOL;
    }
    function failure ($label)
    {
        echo "    ".$this->colorRed(cross).' '.$label.PHP_EOL;
    }
    function end ($ms, $total, $pass)
    {
        echo PHP_EOL.PHP_EOL;
        echo $this->color('',col_cyan,'');
        echo "  Done in ".number_format($ms, 5, ',', ' ')."ms. (~".$this->msu($ms).")".PHP_EOL;
        echo "    Total : ".$total.PHP_EOL;
        echo "    Pass  : ".$this->color($pass,col_green,col_cyan).PHP_EOL;
        if ( ($failed = ($total - $pass)) > 0 )
            echo "    Fail  : ".$this->color($failed,col_red,col_cyan).PHP_EOL;
        echo $this->color('','');
        echo PHP_EOL;
    }
}

class TestsDisplayHTML extends TestsDisplay
{
    function header ($title=null)
    {
        echo "<!DOCTYPE html><html><head><meta charset='utf-8'>";
        echo "<style>section.tests{color:#555}.tests b{color:#558}.tests span.ok{color:#0b0}.tests span.ko{color:#b00}.tests ul{list-style-type:none}";
        echo ".tests table,.tests tr,.tests td{border:0px;margin:0;padding:0;border-collapse:collapse}.tests tr :nth-child(3){text-align:right}";
        echo ".tests i,.tests td{color:#68a;font-size:105%}";
        echo ".tests h1{display:inline-block;margin:0;padding:.5em;font-size:110%;border:thick double #bbb;background:#eef;color:#778}</style>";
        echo "</head><body><section class='tests'>";
        if ($title!=null) echo "<h1>$title</h1>";
        echo "<ul>";
    }
    function suite  ($label)
    {
        echo "</ul><b><u>".$label."</u></b><ul style='margin-top:3px'>";
    }
    function success ($label)
    {
        echo "<li><span class='ok'>✔</span>&nbsp;".$label."</li>";
    }
    function failure ($label)
    {
        echo "<li><span class='ko'>✘</span>&nbsp;".$label."</li>";
    }
    function end ($ms, $total, $pass)
    {
        echo "</ul><br /><ul>";
        echo "<li><i>Done in ".number_format($ms, 5, ',', ' ')."ms.  (~".$this->msu($ms).")</i></li><li>";
        echo "<table>";
        echo "<tr><td>Total</td><td>&nbsp;&nbsp;:&nbsp;</td><td>".$total."</td></tr>";
        echo "<tr><td>Pass</td><td>&nbsp;&nbsp;:&nbsp;</td><td><span class='ok'>".$pass."</span></td></tr>";
        if ( ($failed = ($total - $pass)) > 0 )
            echo "<tr><td>Fail</td><td>&nbsp;&nbsp;:&nbsp;</td><td><span class='ko'>".$failed."</span></td></tr>";
        echo "</table></li></ul></section>";
    }
    function footer ()
    {
        echo "</body></html>";
    }
}

endif;