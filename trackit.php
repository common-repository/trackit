<?php
/**
 * @package Trackit
 * @version 1.0.2
 */
/*
Plugin Name: TrackIt
Plugin URI: https://www.seomywp.com/products/trackit
Description: Trackit is a simple plugin that allows the user to add multiple tracking codes(Google Analytics, Facebook Pixel, JSON schema markup, CSS Javascript) to the Header or Footer of WordPress.
Author: SeoMyWP
Version: 1.0.2
Author URI: https://www.seomywp.com/
*/
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
	require_once('controller.php');
	require_once('ui.php');
	$trackitUI = new trackitUI();
	$trackitController = new trackitController();
	register_activation_hook( __FILE__, array($trackitController,'databaseInstall'));
	add_filter('plugin_action_links_'.plugin_basename( __FILE__ ),array($trackitController,'addSettingLink'));
	add_action('wp_loaded', array($trackitController,'processForm'));
	add_action('wp_head', array($trackitController,'injectHeaderTrackers'));
	add_action('wp_footer', array($trackitController,'injectFooterTrackers'));
?>