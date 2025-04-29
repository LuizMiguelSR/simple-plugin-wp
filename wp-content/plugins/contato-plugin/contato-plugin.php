<?php 

/**
 * 
 * Plugin Name: Contato Plugin
 * Description: Meu plugin de testes
 * Version: 1.0.0
 * Text Domain: contact-plugin 
 * 
 */

if(!defined('ABSPATH'))
{
    die('Você não devia estar aqui');
}

if(!class_exists('ContatoPlugin')) {
    class ContatoPlugin {
        public function __construct()
        {
            define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
            define('MY_PLUGIN_URL', plugin_dir_url(__FILE__));
            require_once(MY_PLUGIN_PATH .'/vendor/autoload.php');
        }

        public function initialize()
        {
            include_once MY_PLUGIN_PATH . 'includes/utilities.php';
            include_once MY_PLUGIN_PATH . 'includes/options-page.php';
            include_once MY_PLUGIN_PATH . 'includes/contact-form.php';
        }
    }

    $contatoPlugin = new ContatoPlugin;
    $contatoPlugin->initialize();
}