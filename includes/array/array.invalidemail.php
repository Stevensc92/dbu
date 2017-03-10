<?php
if (!isset($_SESSION['membre_id']))
{
	global $ArrayEmailInvalid;

	$ArrayEmailInvalid = array(
			0 => "yopmail"
		);
}
?>