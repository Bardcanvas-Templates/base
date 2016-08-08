<?php
/**
 * Quick post form - Depends on the posts module
 *
 * @var config     $config
 * @var posts_data $posts_data
 * @var template   $template
 * @var module     $this_module posts
 */

use hng2_base\config;
use hng2_base\module;
use hng2_base\template;
use hng2_modules\categories\categories_repository;
use hng2_modules\posts\posts_data;
?>

<script type="text/javascript" src="<?= $template->url ?>/media/quick_post_functions~v<?=$config->scripts_version?>.js"></script>

<div id="post_form_target" style="display: none;"></div>

<form name="post_form" id="post_form" method="post"
      action="<?= $config->full_root_path ?>/posts/scripts/save.php" enctype="multipart/form-data">
    
    <input type="hidden" name="id_post"     value="">
    <input type="hidden" name="status"      value="published">
    <input type="hidden" name="ok_with_url" value="true">
    
    <div class="field" data-field="title">
        <textarea name="title" class="expandible_textarea no_fw_font"
                  placeholder="<?= $this_module->language->form->fields->title->quick_form_placeholder ?>"></textarea>
    </div>
    
    <? if( $template->get("current_category_id") ): ?>
        
        <input type="hidden" name="main_category" value="<?= $template->get("current_category_id") ?>">
        
    <? else:
        $categories_repository = new categories_repository();
        $default_category      = $categories_repository->get("0000000000000");
    
        $tree_filter = array(
            "id_category <> '0000000000000'",
            "( visibility = 'public' or visibility = 'users' or (visibility  = 'level_based' and '{$account->level}' >= min_level) )"
        );
        $categories_count = $categories_repository->get_record_count($tree_filter);
        $categories = $categories_repository->get_as_tree_for_select($tree_filter, "", true);
        $style = $categories_count == 0 ? "display: none" : "";
        ?>
        <div class="field" id="main_category_selector_container" data-field="category" style="<?= $style ?>">
            <select name="main_category" class="fixed_font" style="font-size: 12pt;">
                <option selected value="<?= $default_category->id_category ?>">
                    <?= $default_category->title ?>: <?= $default_category->description ?>
                </option>
                <? foreach($categories as $id_category => $description): ?>
                    <option value="<?= $id_category ?>"><?= $description ?></option>
                <? endforeach; ?>
            </select>
        </div>
    <? endif; ?>
    
    <div class="field tinymce_container" data-field="content">
        <textarea name="content" id="post_content_editor" class="tinymce"
                  placeholder="<?= $this_module->language->form->fields->content->quick_form_placeholder ?>"></textarea>
    </div>
    
    <div class="field attachments"></div>
    
    <div class="post_buttons clearfix">
        <span>
            <button onclick="add_attachment_to_quick_post('image', 'fa-camera'); return false;">
                <span class="fa fa-camera"></span>
                <?= $this_module->language->form->fields->buttons->image ?>
            </button>
            <button onclick="add_attachment_to_quick_post('video', 'fa-video-camera'); return false;">
                <span class="fa fa-video-camera"></span>
                <?= $this_module->language->form->fields->buttons->video ?>
            </button>
        </span>
        
        <span style="float: right">
            <!--
            <button type="submit" data-save-type="save_draft"
                    onclick="$('#post_form').find('input[name=status]').val('draft')">
                <span class="fa fa-save"></span>
                <?= $this_module->language->form->buttons->save_draft ?>
            </button>
            -->
            
            <button type="submit" data-save-type="publish">
                <span class="fa fa-check"></span>
                <?= $this_module->language->form->buttons->publish ?>
            </button>
        </span>
    </div>
    
</form>

        