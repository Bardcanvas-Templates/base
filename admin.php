<?
/**
 * Base template - Admin
 *
 * @package    HNG2
 * @subpackage core
 * @author     Alejandro Caballero - lava.caballero@gmail.com
 *
 * @var template $template
 * @var settings $settings
 * @var config   $config
 * @var account  $account
 */

use hng2_base\account;
use hng2_base\config;
use hng2_base\settings;
use hng2_base\template;
use hng2_tools\internals;

include __DIR__ . "/functions.inc";
$template->init(__FILE__);
$account->ping();

foreach($template->get_includes("pre_rendering") as $module => $include)
{
    $this_module = $modules[$module];
    include "{$this_module->abspath}/contents/{$include}";
}

header("Content-Type: text/html; charset=utf-8"); ?>
<!DOCTYPE html>
<html>
<head>
    <? include __DIR__ . "/segments/common_header.inc"; ?>
    
    <!-- Others -->
    <script type="text/javascript" src="<?= $config->full_root_path ?>/lib/jquery.blockUI.js"></script>
    <script type="text/javascript" src="<?= $config->full_root_path ?>/lib/jquery.form.min.js"></script>
    
    <!-- Noty -->
    <script type="text/javascript" src="<?= $config->full_root_path ?>/lib/noty-2.3.7/js/noty/packaged/jquery.noty.packaged.min.js"></script>
    <script type="text/javascript" src="<?= $config->full_root_path ?>/lib/noty-2.3.7/js/noty/themes/default.js"></script>
    <script type="text/javascript" src="<?= $config->full_root_path ?>/media/noty_defaults~v<?=$config->scripts_version?>.js"></script>
    
    <!-- Core functions and styles -->
    <link rel="stylesheet" type="text/css" href="<?= $config->full_root_path ?>/media/styles~v<?=$config->scripts_version?>.css">
    <? if($account->_is_admin): ?><link rel="stylesheet" type="text/css" href="<?= $config->full_root_path ?>/media/admin~v<?=$config->scripts_version?>.css"><? endif; ?>
    <script type="text/javascript"          src="<?= $config->full_root_path ?>/media/functions~v<?=$config->scripts_version?>.js"></script>
    <script type="text/javascript"          src="<?= $config->full_root_path ?>/media/notification_functions~v<?=$config->scripts_version?>.js"></script>
    
    <!-- This template -->
    <link rel="stylesheet" type="text/css" href="<?= $template->url ?>/media/styles~v<?=$config->scripts_version?>.css">
    <link rel="stylesheet" type="text/css" href="<?= $template->url ?>/media/post_styles~v<?=$config->scripts_version?>.css">
    
    <? if( $settings->get("engine.hide_left_sidebar_on_admin_pages") != "true" && $template->count_left_sidebar_groups() > 0 ): ?>
        <!-- Left sidebar -->
        <link rel="stylesheet" type="text/css" href="<?= $template->url ?>/media/left_sidebar_addon~v<?=$config->scripts_version?>.css">
        <script type="text/javascript"          src="<?= $template->url ?>/media/left_sidebar_addon~v<?=$config->scripts_version?>.js"></script>
    <? endif; ?>
    
    <!-- Always-on -->
    <? $template->render_always_on_files(); ?>
    
    <!-- Per module loads -->
    <?
    foreach($template->get_includes("html_head") as $module => $include)
    {
        $this_module = $modules[$module];
        include "{$this_module->abspath}/contents/{$include}";
    }
    ?>
</head>
<body data-orientation="landscape" data-viewport-class="0" <?=$template->get("additional_body_attributes")?> 
      data-page-tag="<?= $template->get("page_tag") ?>" class="admin">

<div id="body_wrapper">
    
    <?
    foreach($template->get_includes("pre_header") as $module => $include)
    {
        $this_module = $modules[$module];
        include "{$this_module->abspath}/contents/{$include}";
    }
    ?>
    
    <div id="header">
        
        <div class="header_top">
            <?
            if( $account->_is_admin && $settings->get("engine.show_admin_menu_in_header_menu") != "true" )
                include "{$template->abspath}/segments/admin_menu.inc";
            
            foreach($template->get_includes("header_top") as $module => $include)
            {
                $this_module = $modules[$module];
                include "{$this_module->abspath}/contents/{$include}";
            }
            ?>
        </div>
        
        <div class="menu clearfix">
            
            <span id="main_menu_trigger" class="main_menu_item" onclick="toggle_main_menu_items()">
                <span class="fa fa-bars fa-fw"></span>
            </span>
            
            <? if($config->display_performance_details && EMBED_INTERNALS): ?>
                <span class="main_menu_item pseudo_link always_visible pull-right"
                      onclick="$('.internals').show(); $.scrollTo('.internals:first', 500);">
                    <span class="fa fa-info fa-fw"></span>
                </span>
            <? endif; ?>
            
            <? if( $settings->get("engine.hide_left_sidebar_on_admin_pages") != "true" && $template->count_left_sidebar_groups() > 0 ): ?>
                <span id="left_sidebar_trigger" class="main_menu_item pull-left"
                      onclick="toggle_left_sidebar_items()">
                    <span class="fa fa-ellipsis-v fa-fw"></span>
                </span>
            <? endif; ?>
            
            <a id="home_menu_button" class="main_menu_item always_visible pull-left" href="<?= $config->full_root_path ?>/">
                <span class="fa fa-home fa-fw"></span>
            </a>
            
            <?
            if( $account->_is_admin && $settings->get("engine.show_admin_menu_in_header_menu") == "true" )
                add_admin_menu_items_to_header_menu();
            
            foreach($template->get_includes("header_menu") as $module => $include)
            {
                $this_module = $modules[$module];
                include "{$this_module->abspath}/contents/{$include}";
            }
            
            echo $template->build_menu_items("priority");
            ?>
        </div>
        
        <div class="header_bottom">
            <?
            foreach($template->get_includes("header_bottom") as $module => $include)
            {
                $this_module = $modules[$module];
                include "{$this_module->abspath}/contents/{$include}";
            }
            ?>
        </div>
    
    </div><!-- /#header -->
    
    <div id="content_wrapper" class="clearfix">
        
        <? if( $settings->get("engine.hide_left_sidebar_on_admin_pages") != "true" && $template->count_left_sidebar_groups() > 0 ): ?>
            <div id="left_sidebar" class="sidebar">
                <? echo $template->build_left_sidebar_groups(); ?>
            </div>
        <? endif; ?>
        
        <div id="content">
            <? include "{$current_module->abspath}/{$template->page_contents_include}"; ?>
        </div><!-- /#content -->
        
    </div>
    
    <?
    foreach($template->get_includes("post_footer") as $module => $include)
    {
        $this_module = $modules[$module];
        include "{$this_module->abspath}/contents/{$include}";
    }
    ?>
    
</div><!-- /#body_wrapper -->

<!-- These must be at the end of the document -->
<script type="text/javascript" src="<?= $config->full_root_path ?>/lib/tinymce-4.4.0/tinymce.min.js"></script>
<? $template->render_tinymce_additions(); ?>
<script type="text/javascript" src="<?= $config->full_root_path ?>/media/init_tinymce~v<?=$config->scripts_version?>.js"></script>
<script type="text/javascript">
    $(document).ready(function()
    {
        tinymce.init(tinymce_defaults);
        tinymce.init(tinymce_full_defaults);
        tinymce.init(tinymce_minimal_defaults);
    });
</script>

<?
foreach($template->get_includes("pre_eof") as $module => $include)
{
    $this_module = $modules[$module];
    include "{$this_module->abspath}/contents/{$include}";
}

internals::render(__FILE__);
?>

</body>
</html>
