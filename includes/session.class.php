<?php
class Session{

	public function setFlash($message,$type = 'error', $modal = false){
		$_SESSION['flash'] = array(
			'message' => $message,
			'type'	  => $type,
			'modal'   => $modal
		);
	}

	public function flash(){
		if(isset($_SESSION['flash'])){
			
			?>
			<div id="alert" class="alert alert-<?php echo $_SESSION['flash']['type']; ?>">
				<a class="close">x</a>
				<?php echo $_SESSION['flash']['message']; ?>
			</div>
			<?php
			unset($_SESSION['flash']); 
		}
	}

}