<?php

class Carsery_Car_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('carsery_car', 'Car', array('description' => 'Un tableau de véhicules.'));
    }
    
    public function widget($args, $instance)
    {
        //Ajout du tableau -> récupération des données
        echo $args['before_widget'];
        echo $args['before_title'];
        echo apply_filters('widget_title', $instance['title']);
        echo $args['after_title'];

        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}carsery_car");
        ?>

        <table>
            <thead>
                <th>Id</th>
                <th>Marque</th>
                <th>Modele</th>
                <th>Date Immatriculation</th>
            </thead>
            <tbody>
            <?php if(!empty($results)): ?>
                <?php foreach($results as $row): ?>
                    <tr>
                        <?php $date = new DateTime($row->dates) ?>
                        <td><?= $row->id ?></td>
                        <td><?= $row->marque ?></td>
                        <td><?= $row->modele ?></td>
                        <td><?= $date->format('d/m/Y') ?></td>
                    </tr>
                <?php endforeach ?>
                <?php else: ?>
                    <tr>Aucun véhicule n'a été trouvé.</tr>
                    
            <?php endif ?>
            </tbody>
        </table>
        <?php
        
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo  $title; ?>" />
        </p>
        <?php
    }
}