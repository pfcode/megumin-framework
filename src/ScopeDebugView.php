<?php

namespace pfcode\MeguminFramework;

/**
 * Class ScopeDebugView
 * @package pfcode\MeguminFramework
 * @author pfcode
 */
class ScopeDebugView extends View
{
    public function display($outputHTML = false)
    {
        echo "<html lang='en'><head><meta charset='utf-8'/><title>" . $this->getPageTitle() . "</title></head><body>";
        var_dump($this->varScope);
        echo "</body></html>";
    }
}