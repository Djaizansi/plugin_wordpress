<?php
/*
Plugin Name: Carsery plugin
Plugin URI: http://carsery.com
Description: Générateur d'un formulaire côté administrateur pouvant ajouter des véhicules et côté widget, ajouter un tableau listant les véhicules afficher
Version: 0.1
Author: Carsery Project
Author URI: http://github.com
License: GPL2
*/

class Carsery_Plugin
{
    public function __construct()
    {
        //Newsletter
        require_once plugin_dir_path( __FILE__ ).'/newsletter.php';
        new Carsery_Newsletter();
        register_activation_hook(__FILE__, array('Carsery_Newsletter', 'install'));
        register_uninstall_hook(__FILE__, array('Carsery_Newsletter', 'uninstall'));
        add_action('admin_menu', array($this, 'add_admin_menu'));

        //Add Car
        require_once plugin_dir_path( __FILE__ ).'/car.php';
        new Carsery_Car();
        register_activation_hook(__FILE__, array('Carsery_Car', 'install'));
        register_uninstall_hook(__FILE__, array('Carsery_Car', 'uninstall'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function add_admin_menu()
    {
        add_menu_page('Carsery plugin', 'Carsery plugin', 'manage_options', 'carsery', array($this, 'menu_html'));
        add_submenu_page('carsery', 'Apercu', 'Apercu', 'manage_options', 'carsery', array($this, 'menu_html'));
    }

    public function menu_html()
    {
        echo '<h1>'.get_admin_page_title().'</h1>';
        echo '<p>Bienvenue sur la page d\'accueil du plugin</p>';
    }
}

new Carsery_Plugin();