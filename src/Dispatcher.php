<?php

namespace pfcode\MeguminFramework;

/**
 * Class Dispatcher
 * @package pfcode\MeguminFramework
 * @author pfcode
 */
class Dispatcher
{
    const LEFT_VAR_CLOSURE = "{";
    const RIGHT_VAR_CLOSURE = "}";
    const FRIENDLY_BLOCK_SEPARATOR = "/";
    const FRIENDLY_QUERY_SEPARATOR = "?";

    private static $baseURL = null;
    private static $urlPatterns = null;
    private static $notFoundFallbackController= null;
    private static $notSpecifiedFallbackController = null;
    private static $currentController = null;
    private static $matchedPattern = null;
    private static $controllersNamespace = null;

    /**
     * "Explosion!" - use only one time for a session :)
     * There's why: http://myanimelist.net/character/117225/Megumin
     */
    public static function explosion(){
        self::performRouting();

        // Out of energy, try to run on the next session
        die();
    }

    /**
     * Parse URL and execute proper controller
     */
    public static function performRouting(){
        // When patterns are defined, try to parse friendly URLs at first
        if(is_array(self::$urlPatterns)){
            if(self::performFriendlyRouting()){
                return;
            }
        }

        // Use standard routing method
        if(self::performStandardRouting()){
            return;
        }

        // Execute controller set as a fallback
        if(!self::executeController(self::$notFoundFallbackController)){
            die("Dispatcher: Failed to execute fallback controller");
        }
    }

    /**
     * Perform routing based on patterns (friendly URLs)
     * @return bool
     */
    private static function performFriendlyRouting(){
        $request = trim($_SERVER['REQUEST_URI']);

        // Find and remove GET query from the request
        $getpos = strpos($request, self::FRIENDLY_QUERY_SEPARATOR);
        if($getpos !== false){
            $request = substr($request, 0, $getpos);
        }

        // Remove slashes from request sides
        $request = rtrim($request, self::FRIENDLY_BLOCK_SEPARATOR);
        $request = ltrim($request, self::FRIENDLY_BLOCK_SEPARATOR);
        $request_blocks = explode(self::FRIENDLY_BLOCK_SEPARATOR, $request);
        $request_blocks_count = count($request_blocks);

        // Look for the matching URL pattern
        foreach(self::$urlPatterns as $raw_pattern => $controller){
            // Remove slashes from pattern sides
            $pattern = rtrim($raw_pattern, self::FRIENDLY_BLOCK_SEPARATOR);
            $pattern = ltrim($pattern, self::FRIENDLY_BLOCK_SEPARATOR);
            $pattern_blocks = explode(self::FRIENDLY_BLOCK_SEPARATOR, $pattern);

            // Check if blocks counts are equal
            if(count($pattern_blocks) != $request_blocks_count){
                continue;
            }

            // Match every block (and extract variables, if exists)
            $vars = array();
            $match_failed = 0;
            for($i = 0; $i < $request_blocks_count; $i++){
                // Check if block is a variable and extract
                $block_len = strlen($pattern_blocks[$i]);
                if($block_len > 2 && $pattern_blocks[$i][0] == self::LEFT_VAR_CLOSURE && $pattern_blocks[$i][$block_len - 1] == self::RIGHT_VAR_CLOSURE){
                    $key = substr($pattern_blocks[$i], 1, $block_len - 2);
                    $value = $request_blocks[$i];

                    $vars[$key] = $value;

                    continue;
                }

                // Block is a constant string - check if matches the pattern
                if($request_blocks[$i] !== $pattern_blocks[$i]){
                    $match_failed = 1;
                    break;
                }
            }

            if($match_failed == 0){
                // Blocks are matching, execute the proper controller
                self::$matchedPattern = $raw_pattern;
                return self::executeController($controller, $vars);
            }
        }

        // Not found any valid pattern
        return false;
    }

    /**
     * Perform classic routing based on $_GET['controller'] parameter
     * @return bool
     */
    private static function performStandardRouting(){
        if(!isset($_GET['controller'])) {
            return self::executeController(self::$notSpecifiedFallbackController, $_GET);
        } else if(empty($_GET['controller'])) {
            return false;
        }

        return self::executeController($_GET['controller'], $_GET);
    }

    /**
     * Execute controller if valid
     * @param $controller_class
     * @param array $args
     * @param bool $ignoreControllerNamespace
     * @return bool
     */
    private static function executeController($controller_class, $args = array(), $ignoreControllerNamespace = false){
        if(!$ignoreControllerNamespace && self::$controllersNamespace){
            if(self::executeController(self::$controllersNamespace . "\\" . $controller_class, $args, true)){
                return true;
            }
        }

        if(!class_exists($controller_class) || !is_subclass_of($controller_class, Controller::class)){
            return false;
        }

        self::$currentController = new $controller_class($args);

        return true;
    }

    /**
     * Set friendly URL rewrite patterns
     * @param array|null $urlPatterns
     */
    public static function setUrlPatterns($urlPatterns)
    {
        self::$urlPatterns = $urlPatterns;
    }

    /**
     * Set namespace for used controllers
     * @param string $controllersNamespace
     */
    public static function setControllersNamespace($controllersNamespace)
    {
        self::$controllersNamespace = $controllersNamespace;
    }

    /**
     * @return null|string
     */
    public static function getMatchedPattern()
    {
        return self::$matchedPattern;
    }

    /**
     * Set name of controller class that should be used as a fallback controller (global 404 page)
     * @param string $notFoundFallbackController           - executed when no controller is found
     * @param string $notSpecifiedFallbackController       - executed when $_GET['controller'] param is not specified
     * when using standard routing method
     */
    public static function setFallbackControllers($notFoundFallbackController, $notSpecifiedFallbackController = null)
    {
        self::$notFoundFallbackController = $notFoundFallbackController;

        if($notSpecifiedFallbackController === null){
            self::$notSpecifiedFallbackController = $notFoundFallbackController;
        } else{
            self::$notSpecifiedFallbackController = $notSpecifiedFallbackController;
        }
    }

    /**
     * Get absolute base link of this site
     * @return string
     */
    public static function getBaseURL(){
        return self::$baseURL;
    }

    /**
     * Set absolute base link of this site
     * @param string $baseURL
     */
    public static function setBaseURL($baseURL){
        self::$baseURL = $baseURL;
    }

    /**
     * Perform redirection to specified URL
     * (and display fallback message, when redirects are blocked by the browser)
     * @param string $url
     */
    public static function redirect($url){
        header('Location: '.$url);
        die("Redirecting to page <a href='".$url."'>".$url."</a>");
    }

    /**
     * Generate friendly URL
     * @param bool|array|string $blocks    array of strings (consts or variables passed to Dispatcher) to be slash-separated
     * @param bool|array $get_data  associative array of data parsed as GET parameters
     * @param bool $isAbsolute      if true, absolute domain will be prepended to URL
     * @return string
     */
    public static function getLink($blocks, $get_data = false, $isAbsolute = false){
        $ret = $isAbsolute ? self::getBaseURL() : "";
        $ret .= self::FRIENDLY_BLOCK_SEPARATOR;

        if(is_array($blocks) && count($blocks)){
            $ret .= implode(self::FRIENDLY_BLOCK_SEPARATOR, $blocks) . self::FRIENDLY_BLOCK_SEPARATOR;
        }

        if(is_string($blocks)){
            $ret .= $blocks;
        }

        if(is_array($get_data) && count($get_data)){
            $ret .= self::FRIENDLY_QUERY_SEPARATOR . http_build_query($get_data);
        }

        return $ret;
    }

    /**
     * Generate friendly URL and redirect to it
     * @param $blocks
     * @param bool $get_data
     * @param bool $is_absolute
     */
    public static function redirectLink($blocks, $get_data = false, $is_absolute = false){
        self::redirect(htmlspecialchars_decode(self::getLink($blocks, $get_data, $is_absolute)));
    }
}