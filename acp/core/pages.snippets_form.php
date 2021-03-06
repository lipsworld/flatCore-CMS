<?php

//prohibit unauthorized access
require 'core/access.php';

if($_REQUEST['snip_id'] == 'n') {
	$modus = 'new';
} else {
	$modus = 'update';
}

/**
 * open snippet
 */

if(!isset($snip_id)) {
	$snip_id = (int) $_REQUEST['snip_id'];
}
  
$dbh = new PDO("sqlite:".CONTENT_DB);
$sql = "SELECT * FROM fc_textlib WHERE textlib_id = $snip_id ";

$result = $dbh->query($sql);
$result = $result->fetch(PDO::FETCH_ASSOC);

$dbh = null;

if(is_array($result)) {
	foreach($result as $k => $v) {
				$$k = htmlspecialchars(stripslashes($v));
	}
}





echo "<form action='acp.php?tn=pages&sub=snippets' method='POST'>";

echo '<div class="row">';
echo '<div class="col-md-9">';

echo '<div class="card">';
echo '<div class="card-header">';

echo '<ul class="nav nav-tabs card-header-tabs" id="bsTabs" role="tablist">';
echo '<li class="nav-item"><a class="nav-link active" href="#content" data-toggle="tab">'.$lang['tab_content'].'</a></li>';
echo '<li class="nav-item"><a class="nav-link" href="#images" data-toggle="tab">'.$lang['images'].'</a></li>';
echo '<li class="nav-item"><a class="nav-link" href="#link" data-toggle="tab">'.$lang['label_url'].'</a></li>';
echo '</ul>';

echo '</div>';
echo '<div class="card-body">';

echo '<div class="tab-content">';

echo '<div class="tab-pane fade show active" id="content">';

echo '<div class="row">';
echo '<div class="col-md-4">';

echo '<div class="form-group">';
echo '<label>'.$lang['filename'].' <small>(a-z,0-9)</small></label>';
echo '<input class="form-control" type="text" name="snippet_name" value="'.$textlib_name.'">';
echo '</div>';

echo '</div>';
echo '<div class="col-md-8">';

echo '<div class="form-group">';
echo '<label>'.$lang['label_title'].'</label>';
echo '<input class="form-control" type="text" name="snippet_title" value="'.$textlib_title.'">';
echo '</div>';

echo '</div>';
echo '</div>';





echo '<textarea class="form-control mceEditor switchEditor" id="textEditor" name="textlib_content">'.$textlib_content.'</textarea>';
echo '<input type="hidden" name="text" value="'.$text.'">';



echo '<div class="row">';
echo '<div class="col-md-6">';

echo '<div class="form-group">';
echo '<label>'.$lang['label_keywords'].'</label>';
echo '<input class="form-control" type="text" name="snippet_keywords" value="'.$textlib_keywords.'" data-role="tagsinput" />';
echo '</div>';

echo '</div>';
echo '<div class="col-md-6">';

echo '<div class="form-group">';
echo '<label>'.$lang['label_classes'].'</label>';
echo '<input class="form-control" type="text" name="snippet_classes" value="'.$textlib_classes.'" />';
echo '</div>';

echo '</div>';
echo '</div>';



echo '</div>';
echo '<div class="tab-pane fade" id="images">';

$arr_Images = fc_get_all_images_rec("$prefs_pagethumbnail_prefix",NULL);
$snippet_thumbnail_array = explode("&lt;-&gt;", $textlib_images);

echo '<div class="scroll-container">';
echo '<select multiple="multiple" name="snippet_thumbnail[]" class="form-control image-picker">';

/* if we have selected images, show them first */
if(count($snippet_thumbnail_array) > 0) {
	echo '<optgroup label="SELECTED">';
	foreach($snippet_thumbnail_array as $sel_images) {
		if(is_file('..'.$sel_images)) {
			echo '<option selected data-img-src="'.$sel_images.'" title="'.$sel_images.'" class="masonry-item" value="'.$sel_images.'">'.basename($sel_images).'</option>';
		}		
	}
	echo '</optgroup>'."\r\n";
}

echo '<optgroup label="NO SELECTED">';
echo '<option value="">'.$lang['page_thumbnail'].'</option>';
	foreach($arr_Images as $page_thumbnails) {
		$selected = "";
		$page_thumbnails = str_replace('../', '/', $page_thumbnails);
		if(strpos($page_thumbnail, $page_thumbnails) !== false) {
			$selected = "selected";
		}
		if(!in_array($page_thumbnails, $page_thumbnail_array)) {
			echo '<option '.$selected.' data-img-src="'.$page_thumbnails.'" title="'.$page_thumbnails.'" class="masonry-item" value="'.$page_thumbnails.'">'.basename($page_thumbnails).'</option>';
		}
}
echo '</optgroup>'."\r\n";
echo '</select>';
echo '</div>';

echo '</div>'; // images
echo '<div class="tab-pane fade" id="link">';

echo '<div class="form-group mt-2">';
echo '<label>'.$lang['label_url'].'</label>';
echo '<input class="form-control" type="text" name="snippet_permalink" value="'.$textlib_permalink.'" />';
echo '</div>';

echo '<div class="form-group mt-2">';
echo '<label>'.$lang['label_url_name'].'</label>';
echo '<input class="form-control" type="text" name="snippet_permalink_name" value="'.$textlib_permalink_name.'" />';
echo '</div>';

echo '<div class="form-group mt-2">';
echo '<label>'.$lang['label_url_title'].'</label>';
echo '<input class="form-control" type="text" name="snippet_permalink_title" value="'.$textlib_permalink_title.'" />';
echo '</div>';

echo '<div class="form-group mt-2">';
echo '<label>'.$lang['label_url_classes'].'</label>';
echo '<input class="form-control" type="text" name="snippet_permalink_classes" value="'.$textlib_permalink_classes.'" />';
echo '</div>';

echo '</div>'; // link





echo '</div>';
echo '</div>';
echo '</div>';



if($textlib_name != '') {
	$get_snip_name_editor = '[snippet]'.$textlib_name.'[/snippet]';
	echo '<hr><div class="form-group">';
	echo '<label>Snippet</label>';
	echo '<input type="text" class="form-control" placeholder="[snippet]...[/snippet]" value="'.$get_snip_name_editor.'" readonly>';
	echo '</div>';
}

echo '</div>';
echo '<div class="col-md-3">';


echo '<div class="card">';
echo '<div class="card-header">'.$lang['tab_page_preferences'].'</div>';
echo '<div class="card-body" style="padding-left:20px;padding-right:20px;">';



echo '<div class="form-group">';
echo '<div class="btn-group btn-group-toggle d-flex" data-toggle="buttons" role="flex">';
echo '<label class="btn btn-sm btn-dark w-100"><input type="radio" name="optEditor" value="optE1"> WYSIWYG</label>';
echo '<label class="btn btn-sm btn-dark w-100"><input type="radio" name="optEditor" value="optE2"> Text</label>';
echo '<label class="btn btn-sm btn-dark w-100"><input type="radio" name="optEditor" value="optE3"> Code</label>';
echo '</div>';
echo '</div>';


$select_textlib_language  = '<select name="sel_language" class="custom-select form-control">';
for($i=0;$i<count($arr_lang);$i++) {
	$lang_sign = $arr_lang[$i]['lang_sign'];
	$lang_desc = $arr_lang[$i]['lang_desc'];
	$lang_folder = $arr_lang[$i]['lang_folder'];
	$select_textlib_language .= "<option value='$lang_folder'".($textlib_lang == "$lang_folder" ? 'selected="selected"' :'').">$lang_sign</option>";	
}
$select_textlib_language .= '</select>';

echo '<div class="row">';
echo '<div class="col-md-6">';

echo '<div class="form-group">';
echo '<label>'.$lang['f_page_language'].'</label>';
echo $select_textlib_language;
echo '</div>';

echo '</div>';
echo '<div class="col-md-6">';

echo '<div class="form-group">';
echo '<label>'.$lang['label_priority'].'</label>';
echo '<input class="form-control" type="text" name="snippet_priority" value="'.$textlib_priority.'">';
echo '</div>';

echo '</div>';
echo '</div>';

/* Select Template */

$arr_Styles = get_all_templates();

$select_select_template = '<select id="select_template" name="select_template"  class="custom-select form-control">';

if($textlib_template == '') {
	$selected_standard = 'selected';
}

$select_select_template .= "<option value='use_standard<|-|>use_standard' $selected_standard>$lang[use_standard]</option>";

/* templates list */
foreach($arr_Styles as $template) {

	$arr_layout_tpl = glob("../styles/$template/templates/snippet*.tpl");
	
	$select_select_template .= "<optgroup label='$template'>";
	
	foreach($arr_layout_tpl as $layout_tpl) {
		$layout_tpl = basename($layout_tpl);
	
		$selected = '';
		if($template == "$textlib_theme" && $layout_tpl == "$textlib_template") {
			$selected = 'selected';
		}
		
		$select_select_template .=  "<option $selected value='$template<|-|>$layout_tpl'>$template » $layout_tpl</option>";
	}
	
	$select_select_template .= '</optgroup>';

}

$select_select_template .= '</select>';

echo '<div class="form-group">';
echo '<label>'.$lang['f_page_template'].'</label>';
echo $select_select_template;
echo '</div>';


$cnt_labels = count($fc_labels);
$arr_checked_labels = explode(",", $textlib_labels);

for($i=0;$i<$cnt_labels;$i++) {
	$label_title = $fc_labels[$i]['label_title'];
	$label_id = $fc_labels[$i]['label_id'];
	$label_color = $fc_labels[$i]['label_color'];
	
  if(in_array("$label_id", $arr_checked_labels)) {
		$checked_label = "checked";
	} else {
		$checked_label = "";
	}
	
	$checkbox_set_labels .= '<div class="form-check form-check-inline">';
 	$checkbox_set_labels .= '<input class="form-check-input" id="label'.$label_id.'" type="checkbox" '.$checked_label.' name="snippet_labels[]" value="'.$label_id.'">';
 	$checkbox_set_labels .= '<label class="form-check-label" for="label'.$label_id.'">'.$label_title.'</label>';
	$checkbox_set_labels .= '</div>';
}

echo '<div class="form-group">';
echo '<p>'.$lang['labels'].'</p>';
echo $checkbox_set_labels;
echo '</div>';


echo '<div class="form-group">';
echo '<label>'.$lang['label_groups'].'</label>';
echo '<input class="form-control" type="text" name="snippet_groups" value="'.$textlib_groups.'" />';
echo '</div>';

echo '<div class="alert alert-dark" style="padding:2px 3px;">';
echo '<strong>'.$lang['label_notes'].':</strong>';
echo '<textarea class="masked-textarea" name="textlib_notes" rows="5">'.$textlib_notes.'</textarea>';
echo '</div>';

echo '<div class="well well-sm">';
if($modus == 'new') {
	echo '<input type="submit" name="save_snippet" class="btn btn-save btn-block" value="'.$lang['save'].'">';
} else {
	echo '<input type="hidden" name="snip_id" value="'.$snip_id.'">';
	echo '<input type="submit" name="save_snippet" class="btn btn-save btn-block" value="'.$lang['update'].'"> ';
	echo '<div class="mt-1 d-flex">';
	echo '<a class="btn btn-dark w-100 mr-1" href="acp.php?tn=pages&sub=snippets">'.$lang['discard_changes'].'</a> ';
	echo '<input type="submit" name="delete_snippet" class="btn btn-dark text-danger" value="'.$lang['delete'].'" onclick="return confirm(\''.$lang['confirm_delete_data'].'\')">';
	echo '</div>';
}
echo '<input  type="hidden" name="csrf_token" value="'.$_SESSION['token'].'">';
echo '</div>';


echo '</div>';
echo '</div>';

echo '</div>';
echo '</div>';


echo '</form>';

?>