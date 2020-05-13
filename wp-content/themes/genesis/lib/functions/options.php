<?php
/**
 * Genesis Framework.
 *
 * WARNING: This file is part of the core Genesis Framework. DO NOT edit this file under any circumstances.
 * Please do all modifications in the form of a child theme.
 *
 * @package Genesis\Options
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://my.studiopress.com/themes/genesis/
 */

/**
 * Return option from the options table and cache result.
 *
 * Applies `genesis_pre_get_option_$key` and `genesis_options` filters.
 *
 * Values pulled from the database are cached on each request, so a second request for the same value won't cause a
 * second DB interaction.
 *
 * @since 1.0.0
 *
 * @param string $key       Option name.
 * @param string $setting   Optional. Settings field name. Eventually defaults to `GENESIS_SETTINGS_FIELD` if not
 *                          passed as an argument.
 * @param bool   $use_cache Optional. Whether to use the Genesis cache value or not. Default is true.
 * @return mixed The value of the `$key` in the database, or the return from
 *               `genesis_pre_get_option_{$key}` short circuit filter if not `null`.
 */
function genesis_get_option( $key, $setting = null, $use_cache = true ) {

	// The default is set here, so it doesn't have to be repeated in the function arguments for genesis_option() too.
	$setting = $setting ?: GENESIS_SETTINGS_FIELD;

	// Allow child theme to short circuit this function.
	$pre = apply_filters( "genesis_pre_get_option_{$key}", null, $setting );
	if ( null !== $pre ) {
		return $pre;
	}

	// Bypass cache if viewing site in Customizer.
	if ( genesis_is_customizer() ) {
		$use_cache = false;
	}

	// If we need to bypass the cache.
	if ( ! $use_cache ) {
		$options = get_option( $setting );

		if ( ! is_array( $options ) || ! array_key_exists( $key, $options ) ) {
			return '';
		}

		return is_array( $options[ $key ] ) ? $options[ $key ] : wp_kses_decode_entities( $options[ $key ] );
	}

	// Setup caches.
	static $settings_cache = [];
	static $options_cache  = [];

	// Check options cache.
	if ( isset( $options_cache[ $setting ][ $key ] ) ) {
		// Option has been cached.
		return $options_cache[ $setting ][ $key ];
	}

	// Check settings cache.
	if ( isset( $settings_cache[ $setting ] ) ) {
		// Setting has been cached.
		$options = apply_filters( 'genesis_options', $settings_cache[ $setting ], $setting );
	} else {
		// Set value and cache setting.
		$settings_cache[ $setting ] = apply_filters( 'genesis_options', get_option( $setting ), $setting );
		$options                    = $settings_cache[ $setting ];
	}

	// Check for non-existent option.
	if ( ! is_array( $options ) || ! array_key_exists( $key, (array) $options ) ) {
		// Cache non-existent option.
		$options_cache[ $setting ][ $key ] = '';
	} else {
		// Option has not previously been cached, so cache now.
		$options_cache[ $setting ][ $key ] = is_array( $options[ $key ] ) ? $options[ $key ] : wp_kses_decode_entities( $options[ $key ] );
	}

	return $options_cache[ $setting ][ $key ];

}

/**
 * Echo options from the options database.
 *
 * @since 1.0.0
 *
 * @param string $key       Option name.
 * @param string $setting   Optional. Settings field name. Eventually defaults to GENESIS_SETTINGS_FIELD.
 * @param bool   $use_cache Optional. Whether to use the Genesis cache value or not. Default is true.
 */
function genesis_option( $key, $setting = null, $use_cache = true ) {

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo genesis_get_option( $key, $setting, $use_cache );

}

/**
 * Return SEO options from the SEO options database.
 *
 * @since 1.0.0
 *
 * @param string $key       Option name.
 * @param bool   $use_cache Optional. Whether to use the Genesis cache value or not. Defaults to true.
 * @return mixed The value of the `$key` in the database, or the return from
 *               `genesis_pre_get_option_{$key}` short circuit filter if not `null`.
 */
function genesis_get_seo_option( $key, $use_cache = true ) {

	return genesis_get_option( $key, GENESIS_SEO_SETTINGS_FIELD, $use_cache );

}

/**
 * Echo an SEO option from the SEO options database.
 *
 * @since 1.0.0
 *
 * @param string $key       Option name.
 * @param bool   $use_cache Optional. Whether to use the Genesis cache value or not. Defaults to true.
 */
function genesis_seo_option( $key, $use_cache = true ) {

	genesis_option( $key, GENESIS_SEO_SETTINGS_FIELD, $use_cache );

}

/**
 * Return a CPT Archive setting value from the options table.
 *
 * @since 2.0.0
 *
 * @param string $key            Option name.
 * @param string $post_type_name Post type name.
 * @param bool   $use_cache      Optional. Whether to use the Genesis cache value or not. Defaults to true.
 * @return mixed The value of the `$key` in the database, or the return from
 *               `genesis_pre_get_option_{$key}` short circuit filter if not `null`.
 */
function genesis_get_cpt_option( $key, $post_type_name = '', $use_cache = true ) {

	$post_type_name = genesis_get_global_post_type_name( $post_type_name );

	return genesis_get_option( $key, GENESIS_CPT_ARCHIVE_SETTINGS_FIELD_PREFIX . $post_type_name, $use_cache );

}

/**
 * Echo a CPT Archive option from the options table.
 *
 * @since 2.0.0
 *
 * @param string $key            Option name.
 * @param string $post_type_name Post type name.
 * @param bool   $use_cache      Optional. Whether to use the Genesis cache value or not. Defaults to true.
 */
function genesis_cpt_option( $key, $post_type_name, $use_cache = true ) {

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo genesis_get_cpt_option( $key, $post_type_name, $use_cache );

}

/**
 * Echo data from a post or page custom field.
 *
 * Echo only the first value of custom field.
 *
 * Pass in a `printf()` pattern as the second parameter and have that wrap around the value, if the value is not falsy.
 *
 * @since 1.0.0
 *
 * @param string $field          Custom field key.
 * @param string $output_pattern `printf()` compatible output pattern.
 * @param int    $post_id        Optional. Post ID to use for Post Meta lookup, defaults to `get_the_ID()`.
 */
function genesis_custom_field( $field, $output_pattern = '%s', $post_id = null ) {

	$value = genesis_get_custom_field( $field, $post_id );
	if ( $value ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( $output_pattern, $value );
	}

}

/**
 * Return custom field post meta data.
 *
 * Return only the first value of custom field. Return empty string if field is blank or not set.
 *
 * @since 1.0.0
 *
 * @param string $field   Custom field key.
 * @param int    $post_id Optional. Post ID to use for Post Meta lookup, defaults to `get_the_ID()`.
 * @return string|bool Return value or empty string on failure.
 */
function genesis_get_custom_field( $field, $post_id = null ) {

	// Use get_the_ID() if no $post_id is specified.
	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;

	if ( ! $post_id ) {
		return '';
	}

	$custom_field = get_post_meta( $post_id, $field, true );

	if ( ! $custom_field ) {
		return '';
	}

	return is_array( $custom_field ) ? $custom_field : wp_kses_decode_entities( $custom_field );

}

/**
 * Save post meta / custom field data for a post or page.
 *
 * It verifies the nonce, then checks we're not doing autosave, ajax or a future post request. It then checks the
 * current user's permissions, before finally* either updating the post meta, or deleting the field if the value was not
 * truthy.
 *
 * By passing an array of fields => values from the same meta box (and therefore same nonce) into the $data argument,
 * repeated checks against the nonce, request and permissions are avoided.
 *
 * @since 1.9.0
 *
 * @param array       $data         Key/Value pairs of data to save in '_field_name' => 'value' format.
 * @param string      $nonce_action Nonce action for use with wp_verify_nonce().
 * @param string      $nonce_name   Name of the nonce to check for permissions.
 * @param WP_Post|int $post         Post object or ID.
 * @param int         $deprecated   Deprecated (formerly accepted a post ID).
 * @return void Return early if permissions are incorrect, doing autosave, Ajax or future post.
 */
function genesis_save_custom_fields( array $data, $nonce_action, $nonce_name, $post, $deprecated = null ) {

	if ( ! empty( $deprecated ) ) {
		_deprecated_argument( __FUNCTION__, '2.0.0' );
	}

	// Verify the nonce.
	if ( ! isset( $_POST[ $nonce_name ] ) || ! wp_verify_nonce( $_POST[ $nonce_name ], $nonce_action ) ) {
		return;
	}

	// Don't try to save the data under autosave, ajax, or future post.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}
	if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
		return;
	}

	// Grab the post object.
	if ( null !== $deprecated ) {
		$post = get_post( $deprecated );
	} else {
		$post = get_post( $post );
	}

	// Don't save if WP is creating a revision (same as DOING_AUTOSAVE?).
	if ( 'revision' === get_post_type( $post ) ) {
		return;
	}

	// Check that the user is allowed to edit the post.
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return;
	}

	// Cycle through $data, insert value or delete field.
	foreach ( $data as $field => $value ) {
		// Save $value, or delete if the $value is empty.
		if ( $value ) {
			update_post_meta( $post->ID, $field, $value );
		} else {
			delete_post_meta( $post->ID, $field );
		}
	}

}

/**
 * Get an expiring database setting.
 *
 * Checks the associate expiration timestamp before returning the setting value.
 *
 * If the setting has expired, delete the setting and expiration.
 *
 * @since 2.9.0
 *
 * @param string $setting The setting key.
 * @param int    $current_time The current timestamp. `time()` if null.
 * @return mixed The value of the setting, or false if setting is expired.
 */
function genesis_get_expiring_setting( $setting, $current_time = null ) {
	if ( is_null( $current_time ) ) {
		$current_time = time();
	}

	// Prefix the setting, so we're not messing with non-expiring settings.
	$setting = 'genesis_expiring_setting_' . $setting;

	if ( (int) $current_time >= (int) get_option( $setting . '_expiration' ) ) {
		delete_option( $setting );
		delete_option( $setting . '_expiration' );
		return false;
	}

	return get_option( $setting );
}

/**
 * Set an expiring database setting.
 *
 * Updates the value and expiration timestamp for an expiring setting.
 *
 * @since 2.9.0
 *
 * @param string $setting  The setting key.
 * @param mixed  $value    The value to save.
 * @param int    $duration The duration this setting should remain active.
 * @return mixed The result of `update_option( $setting )`.
 */
function genesis_set_expiring_setting( $setting, $value, $duration ) {
	// Prefix the setting, so we're not messing with non-expiring settings.
	$setting = 'genesis_expiring_setting_' . $setting;

	update_option( $setting . '_expiration', time() + $duration, false );
	return update_option( $setting, $value, false );
}

/**
 * Delete an expiring database setting.
 *
 * Deletes the database settings for both the $setting and the associated expiration timestamp.
 *
 * @since 2.9.0
 *
 * @param string $setting  The setting key.
 * @return mixed The result of `delete_option( $setting )`.
 */
function genesis_delete_expiring_setting( $setting ) {
	// Prefix the setting, so we're not messing with non-expiring settings.
	$setting = 'genesis_expiring_setting_' . $setting;

	delete_option( $setting . '_expiration' );
	return delete_option( $setting );
}

/**
 * Takes an array of new settings, merges them with the old settings, and pushes them into the database.
 *
 * @since 2.1.0
 *
 * @param string|array $new     New settings. Can be a string, or an array.
 * @param string       $setting Optional. Settings field name. Default is GENESIS_SETTINGS_FIELD.
 * @return bool `true` if option was updated, `false` otherwise.
 */
function genesis_update_settings( $new = '', $setting = GENESIS_SETTINGS_FIELD ) {

	$old = get_option( $setting );

	$settings = wp_parse_args( $new, $old );

	// Allow settings to be deleted.
	foreach ( $settings as $key => $value ) {
		if ( 'unset' === $value ) {
			unset( $settings[ $key ] );
		}
	}

	return update_option( $setting, $settings );

}
