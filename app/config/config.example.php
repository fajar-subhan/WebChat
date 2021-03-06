<?php 
/**
 * Require for helper files
 * 
 */
require_once './app/helpers/My_Helper.php';

/**
 * Define layout name
 * 
 */
if(!defined('LAYOUT')) define('LAYOUT','');

/**
 * This route indicates which controller class should be loaded if the
 * 
 */
if(!defined('CONTROLLER')) define('CONTROLLER','auth');

/**
 * Define base url 
 * 
 * Example https://www.example.com/ 
 */
if(!defined('BASE_URL')) define('BASE_URL','');

/**
 * Default time zone Asia/Jakarta 
 * 
 */
date_default_timezone_set('Asia/Jakarta');

