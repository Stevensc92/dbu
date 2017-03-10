<p>
	<?php
	if(isset($f))
	{
		$f = 'f';
	}
	else
	{
		$f = '';
	}
	?>
	<input type="button" value="Visualiser" onclick="javascript:view<?php echo $f; ?>('textarea','viewDiv');" />
</p>                        
<div id="viewDiv"></div>