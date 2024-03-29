<?php
/*
 * No direct access to this file
 */
if (! isset($data)) {
	exit;
}

// [wpacu_lite]
$availableForPro = '<span class="wpacu-tooltip">'.__('Available for Pro users', 'wp-asset-clean-up').'<br />'.__('Click to upgrade!', 'wp-asset-clean-up').'</span> <img style="opacity: 0.4;" width="20" height="20" src="'.WPACU_PLUGIN_URL.'/assets/icons/icon-lock.svg" valign="top" alt="" />';
// [/wpacu_lite]
?>
<p><span class="dashicons dashicons-info" style="color: #004567;"></span> This page is an overview of all the assets that have bulk changes applied such as <em>"Unload site-wide"</em> or <em>"Unload on All Pages of <strong>post</strong> post type"</em> etc. These are added when editing pages via the CSS/JS Load Manager. <strong>A bulk change is considered anything that is applied once and it has effect on multiple pages of the same kind or site-wide</strong>.</p>

<nav class="nav-tab-wrapper">
	<a href="<?php echo admin_url('admin.php?page=wpassetcleanup_bulk_unloads'); ?>" class="nav-tab <?php if ($data['for'] === 'everywhere') { ?>nav-tab-active<?php } ?>"><?php _e('Everywhere', 'wp-asset-clean-up'); ?></a>
	<a href="<?php echo admin_url('admin.php?page=wpassetcleanup_bulk_unloads&wpacu_for=post_types'); ?>" class="nav-tab <?php if ($data['for'] === 'post_types') { ?>nav-tab-active<?php } ?>">Posts, Pages &amp; Custom Post Types</a>
	<a href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=plugin_bulk_unloaded&utm_medium=tab_link" target="_blank" class="nav-tab go-pro-link-no-style no-transition"><?php echo $availableForPro; ?> &nbsp;Taxonomies</a>
	<a href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=plugin_bulk_unloaded&utm_medium=tab_link" target="_blank" class="nav-tab go-pro-link-no-style no-transition"><?php echo $availableForPro; ?> &nbsp;Authors</a>
	<a href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=plugin_bulk_unloaded&utm_medium=tab_link" target="_blank" class="nav-tab go-pro-link-no-style no-transition"><?php echo $availableForPro; ?> &nbsp;Search Results</a>
	<a href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=plugin_bulk_unloaded&utm_medium=tab_link" target="_blank" class="nav-tab go-pro-link-no-style no-transition"><?php echo $availableForPro; ?> &nbsp;Dates</a>
	<a href="<?php echo WPACU_PLUGIN_GO_PRO_URL; ?>?utm_source=plugin_bulk_unloaded&utm_medium=tab_link" target="_blank" class="nav-tab go-pro-link-no-style no-transition"><?php echo $availableForPro; ?> &nbsp;404 Not Found</a>
</nav>

<div class="wpacu-clearfix"></div>

<?php
do_action('wpacu_admin_notices');

if ($data['for'] === 'post_types') {
	?>
	<div style="margin: 15px 0;">
		<form id="wpacu_post_type_form" method="get" action="<?php echo admin_url('admin.php'); ?>">
			<input type="hidden" name="page" value="wpassetcleanup_bulk_unloads" />
			<input type="hidden" name="wpacu_for" value="post_types" />

			<div style="margin: 0 0 10px 0;"><?php _e('Select the page or post type (including custom ones) for which you want to see the unloaded scripts &amp; styles', 'wp-asset-clean-up'); ?>:</div>
			<select id="wpacu_post_type_select" name="wpacu_post_type">
				<?php foreach ($data['post_types_list'] as $postTypeKey => $postTypeValue) { ?>
					<option <?php if ($data['post_type'] === $postTypeKey) { echo 'selected="selected"'; } ?> value="<?php echo $postTypeKey; ?>"><?php echo $postTypeValue; ?></option>
				<?php } ?>
			</select>
		</form>
	</div>
	<?php
}
?>

<form action="" method="post">
	<?php
	if ($data['for'] === 'everywhere') {
		?>
		<div class="wpacu-clearfix"></div>

		<div class="alert">
			<p>This is the list of the assets that are <strong>globally unloaded</strong> on all pages (including home page).</p>
			<p>If you want to remove this rule and have them loading, use the "Remove rule" checkbox.</p>
			<div style="margin: 0; background: white; padding: 10px; border: 1px solid #ccc; width: auto; display: inline-block;">
				<ul>
					<li>This list fills once you select "<em>Unload site-wide</em>" when you edit posts/pages for the assets that you want to prevent from loading on every page.</li>
					<li>On this page you can only remove the global rules that were added while editing the pages/posts.</li>
				</ul>
			</div>
		</div>

		<div class="wpacu-clearfix"></div>

		<div style="padding: 0 10px 0 0;">
			<h3><?php _e('Styles', 'wp-asset-clean-up'); ?></h3>
			<?php
			if (! empty($data['values']['styles'])) {
				?>
				<table class="wp-list-table widefat fixed striped">
					<tr>
						<td><strong>Handle</strong></td>
						<td><strong><?php _e('Actions', 'wp-asset-clean-up'); ?></strong></td>
					</tr>
					<?php
					foreach ($data['values']['styles'] as $handle) {
						?>
						<tr class="wpacu_global_rule_row">
							<td><strong><span style="color: green;"><?php echo $handle; ?></span></strong></td>
							<td>
								<label><input type="checkbox"
								              class="wpacu_remove_rule"
								              name="wpacu_options_styles[<?php echo $handle; ?>]"
								              value="remove" /> Remove rule</label>
							</td>
						</tr>
						<?php
					}
					?>
				</table>
				<?php
			} else {
				?>
				<p><?php _e('There are no site-wide unloaded styles.', 'wp-asset-clean-up'); ?></p>
				<?php
			}
			?>

			<h3><?php _e('Scripts', 'wp-asset-clean-up'); ?></h3>
			<?php
			if (! empty($data['values']['scripts'])) {
				?>
				<table class="wp-list-table widefat fixed striped">
					<tr>
						<td><strong>Handle</strong></td>
						<td><strong><?php _e('Actions', 'wp-asset-clean-up'); ?></strong></td>
					</tr>
					<?php
					foreach ($data['values']['scripts'] as $handle) {
						?>
						<tr class="wpacu_global_rule_row">
							<td><strong><span style="color: green;"><?php echo $handle; ?></span></strong></td>
							<td>
								<label><input type="checkbox"
								              class="wpacu_remove_rule"
								              name="wpacu_options_scripts[<?php echo $handle; ?>]"
								              value="remove" /> <?php _e('Remove rule', 'wp-asset-clean-up'); ?></label>
							</td>
						</tr>
						<?php
					}
					?>
				</table>
				<?php
			} else {
				?>
				<p><?php _e('There are no site-wide unloaded scripts.', 'wp-asset-clean-up'); ?></p>
				<?php
			}
			?>
		</div>
		<?php
	} elseif ($data['for'] === 'post_types') {
		?>
		<div class="wpacu-clearfix"></div>

		<div class="alert">
			<p>This is the list of the assets that are <strong>unloaded</strong> on all pages belonging to the <strong><u><?php echo $data['post_type']; ?></u></strong> post type.</p>
			<p>If you want to make an asset load again, use the "Remove rule" checkbox.</p>
			<div style="margin: 0; background: white; padding: 10px; border: 1px solid #ccc; width: auto; display: inline-block;">
				<ul>
					<li>This list fills once you select "<em>Unload on All Pages of <strong><?php echo $data['post_type']; ?></strong> post type</em>" when you edit posts/pages for the assets that you want to prevent from loading.</li>
					<li>On this page you can only remove the global rules that were added while editing <strong><?php echo $data['post_type']; ?></strong> post types.</li>
				</ul>
			</div>
		</div>

		<div class="wpacu-clearfix"></div>

		<div style="padding: 0 10px 0 0;">
			<h3><?php _e('Styles', 'wp-asset-clean-up'); ?></h3>
			<?php
			if (! empty($data['values']['styles'])) {
				?>
				<table class="wp-list-table widefat fixed striped">
					<tr>
						<td><strong>Handle</strong></td>
						<td><strong><?php _e('Actions', 'wp-asset-clean-up'); ?></strong></td>
					</tr>
					<?php
					foreach ($data['values']['styles'] as $handle) {
						?>
						<tr class="wpacu_global_rule_row">
							<td><strong><span style="color: green;"><?php echo $handle; ?></span></strong></td>
							<td>
								<label><input type="checkbox"
								              class="wpacu_remove_rule"
								              name="wpacu_options_post_type_styles[<?php echo $handle; ?>]"
								              value="remove" /> <?php _e('Remove rule', 'wp-asset-clean-up'); ?></label>
							</td>
						</tr>
						<?php
					}
					?>
				</table>
				<?php
			} else {
				?>
				<p>There are no bulk unloaded styles for the <strong><?php echo $data['post_type']; ?></strong> post type.</p>
				<?php
			}
			?>

			<h3><?php _e('Scripts', 'wp-asset-clean-up'); ?></h3>
			<?php
			if (! empty($data['values']['scripts'])) {
				?>
				<table class="wp-list-table widefat fixed striped">
					<tr>
						<td><strong>Handle</strong></td>
						<td><strong><?php _e('Actions', 'wp-asset-clean-up'); ?></strong></td>
					</tr>
					<?php
					foreach ($data['values']['scripts'] as $handle) {
						?>
						<tr class="wpacu_global_rule_row">
							<td><strong><span style="color: green;"><?php echo $handle; ?></span></strong></td>
							<td>
								<label><input type="checkbox"
								              class="wpacu_remove_rule"
								              name="wpacu_options_post_type_scripts[<?php echo $handle; ?>]"
								              value="remove" /> <?php _e('Remove rule', 'wp-asset-clean-up'); ?></label>
							</td>
						</tr>
						<?php
					}
					?>
				</table>
				<?php
			} else {
				?>
				<p>There are no bulk unloaded scripts for the <strong><?php echo $data['post_type']; ?></strong> post type.</p>
				<?php
			}
			?>
		</div>
		<?php
	}

	$noAssetsToRemove = (empty($data['values']['styles']) && empty($data['values']['scripts']));
	?>
	<?php wp_nonce_field($data['nonce_action'], $data['nonce_name']); ?>

	<input type="hidden" name="wpacu_for" value="<?php echo $data['for']; ?>" />
	<input type="hidden" name="wpacu_update" value="1" />

	<?php
	if ($data['for'] === 'post_types' && isset($data['post_type'])) {
		?>
		<input type="hidden" name="wpacu_post_type" value="<?php echo $data['post_type']; ?>" />
		<?php
	}
	?>

	<div class="wpacu-clearfix"></div>
	<div id="wpacu-update-button-area" class="no-left-margin">
		<p class="submit">
			<?php
			wp_nonce_field('wpacu_bulk_unloads_update', 'wpacu_bulk_unloads_nonce');
			?>
			<input type="submit"
			       name="submit"
			       id="submit"
				<?php if ($noAssetsToRemove) { ?>
					disabled="disabled"
				<?php } ?>
				   class="button button-primary"
				   value="<?php esc_attr_e('Update', 'wp-asset-clean-up'); ?>" />
			<?php
			if ($noAssetsToRemove) {
				?>
				&nbsp;<small><?php _e('Note: As there are no unloaded assets (scripts &amp; styles) to be managed, the button is disabled.', 'wp-asset-clean-up'); ?></small>
				<?php
			}
			?>
		</p>
	</div>
</form>
