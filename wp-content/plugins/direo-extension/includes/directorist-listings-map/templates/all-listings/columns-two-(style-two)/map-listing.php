<?php
if(isset($_POST['view_as']) && !empty($_POST['view_as'])) {
    $view_as = $_POST['view_as'];
} else {
    $view_as = get_directorist_option('listing_map_view', 'grid');
}
$view = !empty($view_as) ? $view_as : 'grid'
?>
    <div class="bdmv-listing">
        <?php
        include BDM_TEMPLATES_DIR . "view/listings/$view.php";
        do_action('bdmv-after-listing');
        ?>
    </div>
<?php wp_reset_query();?>
    <div class="bdmv-map-right">
        <?php
        include BDM_TEMPLATES_DIR . 'view/map.php';
        ?>
    </div>
<?php wp_reset_query();?>
