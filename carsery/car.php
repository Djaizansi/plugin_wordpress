<?php
include_once plugin_dir_path( __FILE__ ).'/carwidget.php';

class Carsery_Car
{
    public function __construct()
    {
        add_action('widgets_init', function(){register_widget('Carsery_Car_Widget');});
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
        add_action('admin_init', array($this, 'register_settings'));
    }

    public static function install()
    {
        global $wpdb;

        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}carsery_car (id INT AUTO_INCREMENT PRIMARY KEY, marque VARCHAR(50) NOT NULL, modele VARCHAR(50) NOT NULL, dates DATE NOT NULL);");
    }

    public static function uninstall()
    {
        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}carsery_car;");
    }

    public function add_admin_menu()
    {
        $hook = add_submenu_page('carsery', 'Car Parameters', 'Car', 'manage_options', 'carsery_car', array($this, 'menu_html'));
        add_action('load-'.$hook, array($this, 'process_action'));
        add_action('load-'.$hook, array($this, 'process_supp_action'));
        add_action('load-'.$hook, array($this, 'process_modif_action'));
    }

    public function menu_html()
    {
        echo '<h1>'.get_admin_page_title().'</h1>';
        ?>
        <form method="post" action="#">
            <?php do_settings_sections('carsery_car_settings') ?>
            <input type="hidden" name="save_car" value="1"/>
            <?php submit_button("Enregistrer véhicule"); ?>
        </form>


        <form method="post" action="#">
            <?php global $wpdb;
            $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}carsery_car");
            echo '<h2>Delete car</h2>';
            ?>
            <?php if(empty($results)): ?>
                <p>Aucun véhicule trouver</p>
                <br>
            <?php else: ?>
                <?= 'Choisissez le véhicule à supprimer.' ?>
                <br><br>
                <label style="padding-right: 200px; font-weight: bold; font-size: 14px;">Id </label>
                <select name="carsery_car_id" id="carsery_car_id" >
                        <option>-- Choisissez un véhicule --</option>
                        <?php foreach($results as $row):?>
                            <?php $date = new DateTime($row->dates) ?>
                            <option value="<?= $row->id ?>"> <?= $row->marque ?> - <?= $row->modele ?> - <?= $date->format('d/m/Y') ?></option>
                        <?php endforeach ?>
                </select>
                <input type="hidden" name="supp_car" value="1"/>
                <?php submit_button('Supprimer le véhicule') ?>
            <?php endif ?>
        </form>

        <?php if(!empty($_POST['carsery_car_id'])): ?>
            <?php $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}carsery_car WHERE id = {$_POST['carsery_car_id']}") ?>
                <form method="post" action="#">
                    <?php
                    echo '<h2>Update car</h2>';
                    echo 'Modifier le véhicule choisi.';
                    ?>
                    <br><br>
                    <?php foreach($results as $row): ?>

                        <div style="padding-bottom: 20px;">
                            <label style="padding-right: 239px; font-weight: bold; font-size: 14px;">Id </label>
                            <input type="text" name="carsery_car_id" value="<?= $row->id ?>" readonly/>
                        </div>

                        <div style="padding-bottom: 20px;">
                            <label style="padding-right: 200px; font-weight: bold; font-size: 14px;">Marque </label>
                            <input type="text" name="carsery_car_marque" value="<?= $row->marque ?>"/>
                        </div>
                        
                        <div style="padding-bottom: 20px;">
                            <label style="padding-right: 200px; font-weight: bold; font-size: 14px;">Modele </label>
                            <input type="text" name="carsery_car_modele" value="<?= $row->modele ?>"/>
                        </div>

                        <div style="padding-bottom: 20px;">
                            <label style="padding-right: 93px; font-weight: bold; font-size: 14px;">Date d'immatriculation </label>
                            <input type="date" name="carsery_car_dates" value="<?= $row->dates ?>"/>
                        </div>

                        
                    <?php endforeach ?>

                    <input type="hidden" name="modif_car" value="1"/>
                    <?php submit_button('Modifier le véhicule') ?>

                </form>
            <?php elseif(empty($_POST['carsery_car_id'])): ?>
                <form method="post" action="#">
                    <?='<h2>Update car</h2>' ?>
                    <?php if(empty($results) || !isset($results)): ?>
                        <p>Aucun véhicule n'a été trouver</p>
                    <?php else: ?>
                        <?php
                        echo 'Choisissez le véhicule à modifier.';
                        ?>
                        <br><br>
                        <label style="padding-right: 200px; font-weight: bold; font-size: 14px;">Id </label>
                        <select name="carsery_car_id" id="carsery_car_id" >
                                <option value="">-- Choisissez un véhicule --</option>
                                <?php foreach($results as $row):?>
                                    <?php $date = new DateTime($row->dates) ?>
                                    <option value="<?= $row->id ?>"> <?= $row->marque ?> - <?= $row->modele ?> - <?= $date->format('d/m/Y') ?></option>
                                <?php endforeach ?>
                        </select>
                        <?php submit_button('Modifier le véhicule') ?>
                    <?php endif ?>
                </form>
        <?php endif ?>
        <?php
    }

    public function register_settings()
    {

        add_settings_section('carsery_car_section', 'Add Car', array($this, 'section_html'), 'carsery_car_settings');
        add_settings_field('carsery_car_marque', 'Marque', array($this, 'marque_html'), 'carsery_car_settings', 'carsery_car_section');
        add_settings_field('carsery_car_modele', 'Modèle', array($this, 'modele_html'), 'carsery_car_settings', 'carsery_car_section');
        add_settings_field('carsery_car_dates', 'Date d\'immatriculation', array($this, 'dates_html'), 'carsery_car_settings', 'carsery_car_section');
    }

    public function section_html()
    {
        echo 'Renseignez le véhicule à ajouter dans la base de donnée.';
    }

    public function marque_html()
    {?>
        <input type="text" name="carsery_car_marque" />
        <?php
    }

    public function modele_html()
    {?>
        <input type="text" name="carsery_car_modele" />
        <?php
    }

    public function dates_html()
    {?>
        <input type="date" name="carsery_car_dates" />
        <?php
    }

    public function process_action()
    {
        if (isset($_POST['save_car'])) {
            $this->save_car();
        }
    }

    public function process_supp_action()
    {
        if (isset($_POST['supp_car'])) {
            $this->delete_car();
        }
    }

    public function process_modif_action()
    {
        if (isset($_POST['modif_car'])) {
            $this->update_car();
        }
    }

    public function save_car()
    {
        if (isset($_POST) && !empty($_POST)) {
            global $wpdb;
            $marque = $_POST['carsery_car_marque'];
            $modele = $_POST['carsery_car_modele'];
            $dates = $_POST['carsery_car_dates'];  
            $wpdb->insert("{$wpdb->prefix}carsery_car", array('marque' => $marque, 'modele' => $modele, 'dates' => $dates));
        }
    }

    public function delete_car()
    {
        if (isset($_POST) && !empty($_POST)) {
            global $wpdb;
            $idVehicule = $_POST['carsery_car_id'];
            $wpdb->delete("{$wpdb->prefix}carsery_car", array('id' => $idVehicule));
        }
    }

    public function update_car()
    {
        if (isset($_POST) && !empty($_POST)) {
            global $wpdb;
            $idVehicule = $_POST['carsery_car_id'];
            $marque = $_POST['carsery_car_marque'];
            $modele = $_POST['carsery_car_modele'];
            $dates = $_POST['carsery_car_dates']; 
            $wpdb->update("{$wpdb->prefix}carsery_car", array('marque' => $marque, 'modele' => $modele, 'dates' => $dates), array('id' => $idVehicule));
            unset($_POST);
        }
    }
}