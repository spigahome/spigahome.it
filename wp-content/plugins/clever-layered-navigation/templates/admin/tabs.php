<?php
/**
 */
$array_get = $_GET;
unset($array_get['section']);
$array = array();
foreach ($array_get as $key => $value) {
    $array[] = $key.'='.$value;
}
$str_param = implode('&',$array);
$admin_url = admin_url( 'admin.php').'?'.$str_param;

echo '<div class="zoo-ln-wrap-head-page"><h2><img src="'.ZOO_LN_GALLERYPATH.'cln-40.png'.'"/><span>'.esc_html__('Clever Layered Navigation','clever-layered-navigation').'<i>'.esc_html__('Version','clever-layered-navigation').' '.Zoo\Admin\Hook\zoo_plugin_ver().'</i></span></h2></div>';
?>

<h2 class="nav-tab-wrapper">
    <a href="<?php echo $admin_url.'&section=general'?>" class="nav-tab <?php if($_section == 'general') echo('nav-tab-active');?>"><?php esc_html_e('General Setting','clever-layered-navigation')?></a>
    <a href="<?php echo $admin_url.'&section=style'?>" class="nav-tab <?php if($_section == 'style') echo('nav-tab-active');?>"><?php esc_html_e('Filter Style','clever-layered-navigation')?></a>
    <a href="<?php echo $admin_url.'&section=setting'?>" class="nav-tab <?php if($_section == 'setting') echo('nav-tab-active');?>"><?php esc_html_e('Filter Setting','clever-layered-navigation')?></a>
    <a href="<?php echo $admin_url.'&section=advanced'?>" class="nav-tab <?php if($_section == 'advanced') echo('nav-tab-active');?>"><?php esc_html_e('Advanced Setting','clever-layered-navigation')?></a>
</h2>
