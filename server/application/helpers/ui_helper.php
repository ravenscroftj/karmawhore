<?php
function ui_set_message ($content) {
    $ci =& get_instance();
    $ci->db_session->set_flashdata("UI-MSG", $content);
}

function ui_set_error ($content) {
    $ci =& get_instance();
    $ci->db_session->set_flashdata("UI-ERR", $content);
}

function ui_set_notice ($content) {
    $ci =& get_instance();
    $ci->db_session->set_flashdata("UI-NTC", $content);
}

function ui_render ($type = "UI-ALL") {
    $ci =& get_instance();
    $message = $ci->db_session->flashdata('UI-MSG');
    $error = $ci->db_session->flashdata('UI-ERR');
    $notice = $ci->db_session->flashdata('UI-NTC');

    if ($type == "UI-MSG" and !empty($message)) {
	echo '<div class="clear"></div><center><div class="message">'.$message.'</div></center><div class="clear"></div>';
    } elseif ($type == "UI-ERR" and !empty($error)) {
	echo '<div class="clear"></div><center><div class="error">'.$error.'</div></center><div class="clear"></div>';
    } elseif ($type == "UI-NTC" and !empty($notice)) {
	echo '<div class="clear"></div><center><div class="notice">'.$notice.'</div></center><div class="clear"></div>';
    } else {
	if (!empty($message)) {
	    echo '<div class="clear"></div><center><div class="message">'.$message.'</div></center><div class="clear"></div>';
	} elseif (!empty($error)) {
	    echo '<div class="clear"></div><center><div class="error">'.$error.'</div></center><div class="clear"></div>';
	} elseif (!empty($notice)) {
	    echo '<div class="clear"></div><center><div class="notice">'.$notice.'</div></center><div class="clear"></div>';
	}
    }
}

?>
