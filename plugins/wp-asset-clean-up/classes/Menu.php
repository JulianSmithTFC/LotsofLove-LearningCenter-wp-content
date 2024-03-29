<?php
namespace WpAssetCleanUp;

/**
 * Class Menu
 * @package WpAssetCleanUp
 */
class Menu
{
	/**
	 * @var string
	 */
	private static $_capability = 'administrator';

	/**
	 * @var string
	 */
	private static $_slug;

    /**
     * Menu constructor.
     */
    public function __construct()
    {
    	self::$_slug = WPACU_PLUGIN_ID . '_getting_started';

        add_action('admin_menu', array($this, 'activeMenu'));

        if (isset($_GET['page']) && $_GET['page'] === WPACU_PLUGIN_ID . '_go_pro') {
        	header('Location: '.WPACU_PLUGIN_GO_PRO_URL.'?utm_source=plugin_go_pro');
        	exit();
        }
    }

    /**
     *
     */
    public function activeMenu()
    {
	    // User should be of 'administrator' role and allowed to activate plugins
	    if (! self::userCanManageAssets()) {
		    return;
	    }

        add_menu_page(
            __('Asset CleanUp', 'wp-asset-clean-up'),
            __('Asset CleanUp', 'wp-asset-clean-up'),
	        self::$_capability,
            self::$_slug,
            array(new Info, 'gettingStarted'),
	        WPACU_PLUGIN_URL.'/assets/icons/icon-asset-cleanup.png'
        );

	    add_submenu_page(
		    self::$_slug,
		    __('CSS &amp; JS Manager', 'wp-asset-clean-up'),
		    __('CSS &amp; JS Manager', 'wp-asset-clean-up'),
		    self::$_capability,
		    WPACU_PLUGIN_ID . '_assets_manager',
		    array(new AssetsPagesManager, 'page')
	    );

	    add_submenu_page(
		    self::$_slug,
		    __('Settings', 'wp-asset-clean-up'),
		    __('Settings', 'wp-asset-clean-up'),
		    self::$_capability,
		    WPACU_PLUGIN_ID . '_settings',
		    array(new Settings, 'settingsPage')
	    );

	    add_submenu_page(
	        self::$_slug,
            __('Bulk Changes', 'wp-asset-clean-up'),
            __('Bulk Changes', 'wp-asset-clean-up'),
	        self::$_capability,
		    WPACU_PLUGIN_ID . '_bulk_unloads',
            array(new BulkUnloads, 'pageBulkUnloads')
        );

	    add_submenu_page(
	    	self::$_slug,
		    __('Tools', 'wp-asset-clean-up'),
		    __('Tools', 'wp-asset-clean-up'),
		    self::$_capability,
		    WPACU_PLUGIN_ID . '_tools',
		    array(new Tools, 'toolsPage')
	    );

	    // License Page
	    add_submenu_page(
		    self::$_slug,
		    __('License', 'wp-asset-clean-up'),
		    __('License', 'wp-asset-clean-up'),
		    self::$_capability,
		    WPACU_PLUGIN_ID . '_license',
		    array(new Info, 'license')
	    );

        // Get Help | Support Page
        add_submenu_page(
	        self::$_slug,
            __('Help', 'wp-asset-clean-up'),
            __('Help', 'wp-asset-clean-up'),
	        self::$_capability,
	        WPACU_PLUGIN_ID . '_get_help',
            array(new Info, 'help')
        );

	    // Upgrade to "Go Pro" | Redirects to sale page
	    add_submenu_page(
		    self::$_slug,
		    __('Go Pro', 'wp-asset-clean-up'),
		    __('Go Pro', 'wp-asset-clean-up') . ' <span style="font-size: 16px;" class="dashicons dashicons-star-filled"></span>',
		    self::$_capability,
		    WPACU_PLUGIN_ID . '_go_pro',
		    function() {}
	    );

	    // Add "Asset CleanUp Pro" Settings Link to the main "Settings" menu within the Dashboard
	    // For easier navigation
	    $GLOBALS['submenu']['options-general.php'][] = array(
		    __('Asset CleanUp', 'wp-asset-clean-up'),
		    self::$_capability,
		    admin_url( 'admin.php?page=' . WPACU_PLUGIN_ID . '_settings'),
		    __('Asset CleanUp', 'wp-asset-clean-up'),
	    );

        // Rename first item from the menu which has the same title as the menu page
        $GLOBALS['submenu'][self::$_slug][0][0] = esc_attr__('Getting Started', 'wp-asset-clean-up');
    }

	/**
	 * @return bool
	 */
	public static function userCanManageAssets()
	{
		return current_user_can(self::$_capability) && current_user_can('activate_plugins');
	}
}
