<?php
namespace WpAssetCleanUp;

/**
 *
 * Class BulkUnloads
 * @package WpAssetCleanUp
 */
class BulkUnloads
{
    /**
     * @var string
     */
    public $wpacuFor = 'everywhere';

    /**
     * @var string
     */
    public $wpacuPostType = 'post';

    /**
     * @var array
     */
    public $data = array();

    /**
     * GlobalRules constructor.
     */
    public function __construct()
    {
        $this->wpacuFor      = Misc::getVar('request', 'wpacu_for', $this->wpacuFor);
        $this->wpacuPostType = Misc::getVar('request', 'wpacu_post_type', $this->wpacuPostType);

        if (isset($_REQUEST['wpacu_update']) && $_REQUEST['wpacu_update'] == 1) {
            $this->update();
        }
    }

    /**
     * @return array
     */
    public function getCount()
    {
        $values = array();

        if ($this->wpacuFor === 'everywhere') {
            $values = Main::instance()->getGlobalUnload();
        } elseif ($this->wpacuFor === 'post_types') {
            $values = Main::instance()->getBulkUnload('post_type', $this->wpacuPostType);
        }

        if (isset($values['styles']) && ! empty($values['styles'])) {
	        sort($values['styles']);
        }

	    if (isset($values['scripts']) && ! empty($values['scripts'])) {
		    sort($values['scripts']);
	    }

        return $values;
    }

    /**
     *
     */
    public function pageBulkUnloads()
    {
        $this->data['for'] = $this->wpacuFor;

        if ($this->wpacuFor === 'post_types') {
            $this->data['post_type'] = $this->wpacuPostType;

            // Get All Post Types
            $postTypes = get_post_types(array('public' => true));
            $this->data['post_types_list'] = $this->filterPostTypesList($postTypes);
        }

        $this->data['values'] = $this->getCount();

        $this->data['nonce_name'] = Update::NONCE_FIELD_NAME;
        $this->data['nonce_action'] = Update::NONCE_ACTION_NAME;

        Main::instance()->parseTemplate('admin-page-settings-bulk-changes', $this->data, true);
    }

    /**
     * @param $postTypes
     *
     * @return mixed
     */
    public function filterPostTypesList($postTypes)
    {
        foreach ($postTypes as $postTypeKey => $postTypeValue) {
            if ($postTypeKey === 'product' && Misc::isWooCommerceActive()) {
                $postTypes[$postTypeKey] = 'product &#10230; WooCommerce';
            }
        }

        return $postTypes;
    }

    /**
     *
     */
    public function update()
    {
        if (! Misc::getVar('post', 'wpacu_bulk_unloads_nonce')) {
            return;
        }

	    check_admin_referer('wpacu_bulk_unloads_update', 'wpacu_bulk_unloads_nonce');

        $wpacuUpdate = new Update;

        if ($this->wpacuFor === 'everywhere') {
            $removed = $wpacuUpdate->removeEverywhereUnloads(array(), array(), 'post');

            if ($removed) {
                add_action('wpacu_admin_notices', array($this, 'noticeGlobalsRemoved'));
            }
        }

        if ($this->wpacuFor === 'post_types') {
            $removed = $wpacuUpdate->removeBulkUnloads($this->wpacuPostType);

            if ($removed) {
                add_action('wpacu_admin_notices', array($this, 'noticePostTypesRemoved'));
            }
        }
    }

    /**
     *
     */
    public function noticeGlobalsRemoved()
    {
    ?>
        <div class="updated notice wpacu-notice is-dismissible">
            <p><span class="dashicons dashicons-yes"></span>
                <?php
                _e('The selected styles/scripts were removed from the global unload list and they will now load in the pages/posts, unless you have other rules that would prevent them from loading.', 'wp-asset-clean-up');
                ?>
            </p>
        </div>
    <?php
    }

    /**
     *
     */
    public function noticePostTypesRemoved()
    {
        ?>
        <div class="updated notice wpacu-notice is-dismissible">
            <p><span class="dashicons dashicons-yes"></span>
                <?php
                echo sprintf(
                    __('The selected styles/scripts were removed from the unload list for <strong><u>%s</u></strong> post type and they will now load in the pages/posts, unless you have other rules that would prevent them from loading.', 'wp-asset-clean-up'),
                    $this->wpacuPostType
                );
                ?>
            </p>
        </div>
        <?php
    }
}
