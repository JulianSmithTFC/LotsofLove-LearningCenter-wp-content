<?php
namespace WpAssetCleanUp;

/**
 * Class Misc
 * contains various common functions that are used by the plugin
 * @package WpAssetCleanUp
 */
class Misc
{
	/**
	 * @var array
	 */
	public static $potentialCachePlugins = array(
		'wp-rocket/wp-rocket.php', // WP Rocket
		'wp-super-cache/wp-cache.php', // WP Super Cache
		'w3-total-cache/w3-total-cache.php', // W3 Total Cache
		'wp-fastest-cache/wpFastestCache.php', // WP Fastest Cache
		'swift-performance-lite/performance.php', // Swift Performance Lite
		'breeze/breeze.php', // Breeze – WordPress Cache Plugin
		'comet-cache/comet-cache.php', // Comet Cache
		'cache-enabler/cache-enabler.php', // Cache Enabler
		'hyper-cache/plugin.php', // Hyper Cache
		'cachify/cachify.php', // Cachify
		'simple-cache/simple-cache.php', // Simple Cache
		'litespeed-cache/litespeed-cache.php' // LiteSpeed Cache
	);

	/**
	 * @var array
	 */
	public $activeCachePlugins = array();

    /**
     * Misc constructor.
     */
    public function __construct()
    {
        if (isset($_REQUEST['wpacuNoAdminBar'])) {
	        self::noAdminBarLoad();
        }
    }

    /**
     * @var
     */
    public static $showOnFront;

	/**
	 *
	 */
	public function getActiveCachePlugins()
	{
		if (empty($this->activeCachePlugins)) {
			$activePlugins = get_option( 'active_plugins' );

			foreach ( self::$potentialCachePlugins as $cachePlugin ) {
				if ( in_array( $cachePlugin, $activePlugins ) ) {
					$this->activeCachePlugins[] = $cachePlugin;
				}
			}
		}

		return $this->activeCachePlugins;
	}

    /**
     * @param $string
     * @param $start
     * @param $end
     * @return string
     */
    public static function extractBetween($string, $start, $end)
    {
        $pos = stripos($string, $start);

        $str = substr($string, $pos);

        $strTwo = substr($str, strlen($start));

        $secondPos = stripos($strTwo, $end);

        $strThree = substr($strTwo, 0, $secondPos);

        return trim($strThree); // remove whitespaces;
    }

	/**
	 * @param $string
	 * @param $endsWithString
	 * @return bool
	 */
	public static function endsWith($string, $endsWithString)
	{
		$stringLen = strlen($string);
		$endsWithStringLen = strlen($endsWithString);

		if ($endsWithStringLen > $stringLen) {
			return false;
		}

		return (substr_compare(
			        $string,
			        $endsWithString,
			        $stringLen - $endsWithStringLen, $endsWithStringLen
		        ) === 0);
	}

	/**
	 * @return string
	 */
	public static function isHttpsSecure()
	{
		$isSecure = false;

		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
			$isSecure = true;
		} elseif (
			( ! empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' )
			|| ( ! empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on' )
		) {
			// Is it behind a load balancer?
			$isSecure = true;
		}

		return $isSecure;
	}

    /**
     * @param $postId
     * @return false|mixed|string
     */
    public static function getPageUrl($postId)
    {
        // Was the home page detected?
        if (self::isHomePage()) {
            if (get_site_url() !== get_home_url()) {
                $pageUrl = get_home_url();
            } else {
                $pageUrl = get_site_url();
            }

            return self::_filterPageUrl($pageUrl);
        }

	    // It's singular page: post, page, custom post type (e.g. 'product' from WooCommerce)
        if ($postId > 0) {
            return self::_filterPageUrl(get_permalink($postId));
        }

	    // If it's not a singular page, nor the home page, continue...
	    // It could be: Archive page (e.g. author, category, tag, date, custom taxonomy), Search page, 404 page etc.
	    global $wp;

        $permalinkStructure = get_option('permalink_structure');

        if ($permalinkStructure) {
		    $pageUrl = home_url($wp->request);
	    } else {
		    $pageUrl = home_url($_SERVER['REQUEST_URI']);
	    }

        if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
	        list( $cleanRequestUri ) = explode( '?', $_SERVER['REQUEST_URI'] );
        } else {
	        $cleanRequestUri = $_SERVER['REQUEST_URI'];
        }

        if (substr($cleanRequestUri, -1) === '/') {
        	$pageUrl .= '/';
        }

        return self::_filterPageUrl($pageUrl);
    }

    /**
     * @param $postUrl
     * @return mixed
     */
    private static function _filterPageUrl($postUrl)
    {
        // If we are in the Dashboard on a HTTPS connection,
        // then we will make the AJAX call over HTTPS as well for the front-end
        // to avoid blocking
        if (self::isHttpsSecure() && strpos($postUrl, 'http://') === 0) {
            $postUrl = str_ireplace('http://', 'https://', $postUrl);
        }

        return $postUrl;
    }

    /**
     * @return mixed
     */
    public static function isHomePage()
    {
	    // Docs: https://codex.wordpress.org/Conditional_Tags

	    // "Your latest posts" -> sometimes it works as is_front_page(), sometimes as is_home())
	    // "A static page (select below)" -> In this case is_front_page() should work

	    // Sometimes neither of these two options are selected
	    // (it happens with some themes that have an incorporated page builder)
	    // and is_home() tends to work fine

	    // Both will be used to be sure the home page is detected

	    // VARIOUS SCENARIOS for "Your homepage displays" option from Settings -> Reading

	    // 1) "Your latest posts" is selected
	    if (self::getShowOnFront() === 'posts' && is_front_page()) {
	    	// Default homepage
	    	return true;
	    }

	    // 2) "A static page (select below)" is selected

	    // Note: Either "Homepage:" or "Posts page:" need to have a value set
	    // Otherwise, it will default to "Your latest posts", the other choice from "Your homepage displays"

	    if (self::getShowOnFront() === 'page') {
			$pageOnFront = get_option('page_on_front');

		    // "Homepage:" has a value
			if ($pageOnFront > 0 && is_front_page()) {
				// Static Homepage
				return true;
			}

		    // "Homepage:" has no value
			if (! $pageOnFront && self::isBlogPage()) {
				// Blog page
				return true;
			}

		    // Another scenario is when both 'Homepage:' and 'Posts page:' have values
		    // If we are on the blog page (which is "Posts page:" value), then it will return false
		    // As it's not the main page of the website
		    // e.g. Main page: www.yoursite.com - Blog page: www.yoursite.com/blog/
	    }

	    // Some WordPress themes such as "Extra" have their own custom value
	    $return = ( ( (self::getShowOnFront() !== '') || (self::getShowOnFront() === 'layout') )
	         &&
		    ((is_home() || self::isBlogPage()) || self::isRootUrl())
	    );

	    return $return;
    }

	/**
	 * @return bool
	 */
	public static function isRootUrl()
    {
    	$siteUrl = get_bloginfo('url');

	    $urlPath = parse_url($siteUrl, PHP_URL_PATH);
	    $requestURI = $_SERVER['REQUEST_URI'];

	    $urlPathNoForwardSlash = $urlPath;
	    $requestURINoForwardSlash = $requestURI;

	    if (substr($urlPath, -1) === '/') {
	    	$urlPathNoForwardSlash = substr($urlPath, 0, -1);
	    }

	    if (substr($requestURI, -1) === '/') {
		    $requestURINoForwardSlash = substr($requestURI, 0, -1);
	    }

	    return ($urlPathNoForwardSlash === $requestURINoForwardSlash);
    }

	/**
	 * @param $src
	 *
	 * @return array
	 */
	public static function getLocalSrc($src)
    {
    	if (! $src) {
    	    return array();
	    }

    	// Clean it up first
	    if (strpos($src, '.css?') !== false) {
	    	list($src) = explode('.css?', $src);
		    $src .= '.css';
	    }

	    if (strpos($src, '.js?') !== false) {
		    list($src) = explode('.js?', $src);
		    $src .= '.js';
	    }

	    $paths = array('wp-content/themes/','wp-content/plugins/','wp-content/uploads/');

	    foreach ($paths as $path) {
	    	if (strpos($src, $path) !== false) {
	    		list ($baseUrl, $relSrc) = explode($path, $src);

	    		$localPathToFile = ABSPATH . $path . $relSrc;

	    		if (file_exists($localPathToFile)) {
	    			return array('base_url' => $baseUrl, 'rel_src' => $path . $relSrc, 'file_exists' => 1);
			    }
		    }
	    }

	    return array();
    }

	/**
	 * @return bool
	 */
	public static function isBlogPage()
    {
    	return (is_home() && !is_front_page());
    }

    /**
     * @return mixed
     */
    public static function getShowOnFront()
    {
        if (! self::$showOnFront) {
            self::$showOnFront = get_option('show_on_front');
        }

        return self::$showOnFront;
    }

    /**
     *
     */
    public static function noAdminBarLoad()
    {
        add_filter('show_admin_bar', '__return_false');
    }

    /**
     * @return bool
     */
    public static function isWooCommerceActive()
    {
        return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
    }

	/**
	 * @return bool
	 */
	public static function isPerfmattersActive()
    {
	    return in_array('perfmatters/perfmatters.php', apply_filters('active_plugins', get_option('active_plugins')));
    }

	/**
	 * @param $array
	 *
	 * @return mixed
	 */
	public static function arrayKeyFirst($array)
	{
		if (function_exists('array_key_first')) {
			return array_key_first($array);
		}

		$arrayKeys = array_keys($array);

		return $arrayKeys[0];
	}

	/**
	 * @return bool|int
	 */
	public static function jsonLastError()
	{
		if (function_exists('json_last_error')) {
			return json_last_error();
		}

		// Fallback (notify the user through a warning)
		return 0;
	}

	/**
	 * @param $string
	 *
	 * @return bool
	 */
	public static function isJsonValid($string)
	{
		@json_decode($string, ARRAY_A);
		return JSON_ERROR_NONE === self::jsonLastError();
	}

	/**
	 * @param $requestMethod
	 * @param $key
	 * @param mixed $defaultValue
	 *
	 * @return mixed
	 */
	public static function getVar($requestMethod, $key, $defaultValue = '')
    {
	    if ($requestMethod === 'get' && $key && isset($_GET[$key])) {
		    return $_GET[$key];
	    }

		if ($requestMethod === 'post' && $key && isset($_POST[$key])) {
			return $_POST[$key];
		}

	    if ($requestMethod === 'request' && $key && isset($_REQUEST[$key])) {
		    return $_REQUEST[$key];
	    }

	    return $defaultValue;
    }

	/**
	 * @param $optionName
	 * @param $optionValue
	 * @param string $autoload
	 */
	public static function addUpdateOption($optionName, $optionValue, $autoload = 'no')
    {
    	// Nothing in the database | Add it
    	if (! get_option($optionName)) {
		    add_option($optionName, $optionValue, '', $autoload);
		    return;
	    }

    	// Value is in the database already | Update it
    	update_option($optionName, $optionValue, $autoload);
    }

	/**
	 * @param bool $onlyTransient
	 *
	 * @return array|bool|mixed|object
	 */
	public static function fetchActiveFreePluginsIcons($onlyTransient = false)
    {
    	$activePluginsIconsJson = get_transient('wpacu_active_plugins_icons');

    	if ($activePluginsIconsJson) {
		    $activePluginsIcons = @json_decode($activePluginsIconsJson, ARRAY_A);
	    }

    	if (! empty($activePluginsIcons) && is_array($activePluginsIcons)) {
    		return $activePluginsIcons;
	    }

    	// Do not fetch the icons from the WordPress.org repository if only transient was required
    	if ($onlyTransient) {
    		return array();
	    }

	    $allActivePlugins = get_option('active_plugins');

	    if (empty($allActivePlugins)) {
	    	return array();
	    }

	    foreach ($allActivePlugins as $activePlugin) {
	    	if (strpos($activePlugin, '/') === false) {
	    		continue;
		    }

	    	list($pluginSlug) = explode('/', $activePlugin);

	    	// Avoid the calls to WordPress.org as much as possible
		    // as it would decrease the resources and timing to fetch the data we need

	    	// not relevant to check Asset CleanUp's plugin info in this case
	    	if (in_array($pluginSlug, array('wp-asset-clean-up', 'wp-asset-clean-up-pro'))) {
	    		continue;
		    }

	    	// no readme.txt file in the plugin's root folder? skip it
			if (! file_exists(WP_PLUGIN_DIR.'/'.$pluginSlug.'/readme.txt')) {
				continue;
			}

		    $payload = array(
			    'action'  => 'plugin_information',
			    'request' => serialize( (object) array(
				    'slug'   => $pluginSlug,
				    'fields' => array(
					    'tags'          => false,
					    'icons'         => true, // that's what will get fetched
					    'sections'      => false,
					    'description'   => false,
					    'tested'        => false,
					    'requires'      => false,
					    'rating'        => false,
					    'downloaded'    => false,
					    'downloadlink'  => false,
					    'last_updated'  => false,
					    'homepage'      => false,
					    'compatibility' => false,
					    'ratings'       => false,
					    'added'         => false,
					    'donate_link'   => false
				    ),
			    ) ),
		    );

		    $body = @wp_remote_post('http://api.wordpress.org/plugins/info/1.0/', array('body' => $payload));

		    if (! (isset($body['body']) && is_serialized($body['body']))) {
		        continue;
		    }

		    $pluginInfo = @unserialize($body['body']);

		    if (! isset($pluginInfo->name, $pluginInfo->icons)) {
		    	continue;
		    }

		    if (empty($pluginInfo->icons)) {
		    	continue;
		    }

		    $pluginIcon = array_shift($pluginInfo->icons);

		    if ($pluginIcon !== '') {
			    $activePluginsIcons[$pluginSlug] = $pluginIcon;
		    }
	    }

	    if (empty($activePluginsIcons)) {
	    	return array();
	    }

	    set_transient('wpacu_active_plugins_icons', json_encode($activePluginsIcons), 1209600); // in seconds

	    return $activePluginsIcons;
    }

	/**
	 * @return array|bool|mixed|object
	 */
	public static function getAllActivePluginsIcons()
    {
	    $popularPluginsIcons = array(
		    'elementor'     => WPACU_PLUGIN_URL . '/assets/icons/premium-plugins/elementor.svg',
		    'elementor-pro' => WPACU_PLUGIN_URL . '/assets/icons/premium-plugins/elementor-pro.jpg',
		    'oxygen'        => WPACU_PLUGIN_URL . '/assets/icons/premium-plugins/oxygen.png',
		    'gravityforms'  => WPACU_PLUGIN_URL . '/assets/icons/premium-plugins/gravityforms-blue.svg',
		    'revslider'     => WPACU_PLUGIN_URL . '/assets/icons/premium-plugins/revslider.png',
		    'LayerSlider'   => WPACU_PLUGIN_URL . '/assets/icons/premium-plugins/LayerSlider.jpg',
		    'wpdatatables'  => WPACU_PLUGIN_URL . '/assets/icons/premium-plugins/wpdatatables.jpg',
		    'monarch'       => WPACU_PLUGIN_URL . '/assets/icons/premium-plugins/monarch.jpg'
	    );

	    $allActivePluginsIcons = self::fetchActiveFreePluginsIcons(true) ?: array();

	    foreach (get_option('active_plugins') as $activePlugin) {
		    if (strpos($activePlugin, '/') !== false) {
			    list ($pluginSlug) = explode('/', $activePlugin);

			    if (! array_key_exists($pluginSlug, $allActivePluginsIcons) && array_key_exists($pluginSlug, $popularPluginsIcons)) {
				    $allActivePluginsIcons[$pluginSlug] = $popularPluginsIcons[$pluginSlug];
			    }
		    }
	    }

	    return $allActivePluginsIcons;
    }

	/**
	 * @param $themeName
	 *
	 * @return array|string
	 */
	public static function getThemeIcon($themeName)
    {
	    $themesIconsPathToDir = WPACU_PLUGIN_DIR.'/assets/icons/themes/';
	    $themesIconsUrlDir    = WPACU_PLUGIN_URL.'/assets/icons/themes/';

	    if (! is_dir($themesIconsPathToDir)) {
	        return array();
	    }

	    $themeName = strtolower($themeName);

	    $themesIcons = scandir($themesIconsPathToDir);

	    foreach ($themesIcons as $themesIcon) {
	    	if (strpos($themesIcon, $themeName.'.') !== false) {
				return $themesIconsUrlDir . $themesIcon;
				break;
		    }
	    }

	    return '';
    }

	/**
	 * @return array
	 */
	public function allCssHandles()
	{
		global $wp_styles;

		$allCssHandles = array();

		if (isset($wp_styles->registered) && ! empty($wp_styles->registered)) {
			$allCssHandles = array_keys($wp_styles->registered);
		}

		return $allCssHandles;
	}

	/**
	 * @return array
	 */
	public function allJsHandles()
	{
		global $wp_scripts;

		$allJsHandles = array();

		if (isset($wp_scripts->registered) && ! empty($wp_scripts->registered)) {
			$allJsHandles = array_keys($wp_scripts->registered);
		}

		return $allJsHandles;
	}

	/**
	 * Triggers only in the front-end view (e.g. Homepage URL, /contact/, /about/ etc.)
	 * Except the situations below: no page builders edit mode etc.
	 *
	 * @return bool
	 */
	public static function triggerFrontendOptimization()
	{
		// "Elementor" Edit Mode
		if (isset($_GET['elementor-preview']) && $_GET['elementor-preview']) {
			return false;
		}

		// "Divi" Edit Mode
		if (isset($_GET['et_fb']) && $_GET['et_fb']) {
			return false;
		}

		// Not within the Dashboard
		if (is_admin()) {
			return false;
		}

		// Default (triggers in most cases)
		return true;
	}
}
