<?php
// no direct access
if (! isset($data)) {
	exit;
}

$listAreaStatus = $data['plugin_settings']['assets_list_layout_areas_status'];

/*
* -------------------------------
* [START] BY Loaded or Unloaded
* -------------------------------
*/
?>

    <div>
        <?php
        if (! empty($data['all']['styles']) || ! empty($data['all']['scripts'])) {
        ?>
        <p><?php echo sprintf(__('The following styles &amp; scripts are loading on this page. Please select the ones that are %sNOT NEEDED%s. If you are not sure which ones to unload, it is better to enable "Test Mode" (to make the changes apply only to you), while you are going through the trial &amp; error process.', 'wp-asset-clean-up'), '<span style="color: #CC0000;"><strong>', '</strong></span>'); ?></p>
        <p><?php echo __('"Load in on this page (make exception)" will take effect when a bulk unload rule is used. Otherwise, the asset will load anyway unless you select it for unload.', 'wp-asset-clean-up'); ?></p>
        <?php
        if ($data['plugin_settings']['hide_core_files']) {
            ?>
            <div class="wpacu_note"><span class="dashicons dashicons-info"></span> WordPress CSS &amp; JavaScript core files are hidden as requested in the plugin's settings. They are meant to be managed by experienced developers in special situations.</div>
            <div class="wpacu-clearfix" style="margin-top: 10px;"></div>
            <?php
        }

        if ( ( (isset($data['core_styles_loaded']) && $data['core_styles_loaded']) || (isset($data['core_scripts_loaded']) && $data['core_scripts_loaded']) ) && ! $data['plugin_settings']['hide_core_files']) {
            ?>
            <div class="wpacu_note wpacu_warning"><em><?php
                    echo sprintf(
                        __('Assets that are marked with %s are part of WordPress core files. Be careful if you decide to unload them! If you are not sure what to do, just leave them loaded by default and consult with a developer.', 'wp-asset-clean-up'),
                        '<span class="dashicons dashicons-warning"></span>'
                    );
                    ?>
                </em></div><br />
            <?php
        }
        ?>
    </div>
	<?php
	$handleStatusesText = array(
		'loaded' => '<span style="color: green;" class="dashicons dashicons-yes"></span>&nbsp; All loaded (.css &amp; .js)',
		'unloaded' => '<span style="color: #cc0000;" class="dashicons dashicons-no-alt"></span>&nbsp; All unloaded (.css &amp; .js)'
	);

	$data['view_by_loaded_unloaded'] =
	$data['rows_build_array'] =
	$data['rows_by_loaded_unloaded'] = true;

	$data['rows_assets'] = array();

	require_once __DIR__.'/_asset-style-rows.php';
	require_once __DIR__.'/_asset-script-rows.php';

	if (! empty($data['rows_assets'])) {
		// Sorting: loaded & unloaded
		$rowsAssets = array('loaded' => array(), 'unloaded' => array());

		foreach ($data['rows_assets'] as $handleStatus => $values) {
			$rowsAssets[$handleStatus] = $values;
		}

		foreach ($rowsAssets as $handleStatus => $values) {
			ksort($values);
			?>
            <div class="wpacu-assets-collapsible-wrap wpacu-by-parents wpacu-<?php echo $handleStatus; ?>">
                <a class="wpacu-assets-collapsible <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-assets-collapsible-active<?php } ?>" href="#wpacu-assets-collapsible-content-<?php echo $handleStatus; ?>">
					<?php echo $handleStatusesText[$handleStatus]; ?>
                </a>

                <div class="wpacu-assets-collapsible-content <?php if ($listAreaStatus !== 'contracted') { ?>wpacu-open<?php } ?>">
					<?php if ($handleStatus === 'loaded') {
                        if (count($values) > 0) {
                            $loadedFilesNote = __('The following files were not selected for unload in any way (e.g. per page, site-wide) on this page. The list also includes any load exceptions (e.g. a file can be unloaded site-wide, but loaded on this page).', 'wp-asset-clean-up');
                        } else {
	                        $loadedFilesNote = __('All the CSS/JS files were chosen to be unloaded on this page', 'wp-asset-clean-up');
                        }
					    ?>
                        <p class="wpacu-assets-note"><?php echo $loadedFilesNote; ?></p>
					<?php } elseif ($handleStatus === 'unloaded') {
					    if (count($values) > 0) {
						    $unloadedFilesNote = __('The following CSS/JS files are unloaded on this page (either only on this page or site-wide)', 'wp-asset-clean-up');
					    } else {
						    $unloadedFilesNote = __('There are no unloaded CSS/JS files on this page', 'wp-asset-clean-up');
                        }
					    ?>
                        <p class="wpacu-assets-note"><?php echo $unloadedFilesNote; ?>.</p>
					<?php } ?>

                    <?php if (count($values) > 0) { ?>
                        <table class="wpacu_list_table wpacu_list_by_parents wpacu_widefat wpacu_striped">
                            <tbody>
                            <?php
                            $assetRowIndex = 1;

                            foreach ($values as $assetType => $assetRows) {
                                foreach ($assetRows as $assetRow) {
                                    echo $assetRow . "\n";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
			<?php
		}
	}
}
/*
* ----------------------------
* [END] BY Loaded or Unloaded
* ----------------------------
*/

include '_inline_js.php';
