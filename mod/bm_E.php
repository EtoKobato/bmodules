<?PHP
	class bm_exception extends Exception {
		
		function __construct(array $message) {
			$this -> a_msg = $message;
		}
		
		function print_e_msg() {
			echo '<div style="position: relative; border: 0.2em solid darkviolet; border-radius: 0.8em; padding: 0.8em; margin: 2.6em 1.8em;">';
			echo '<h2 style="color: darkviolet;">An error occurred while processing your request.</h2>';
			echo '<h3 style="color: darkviolet;">Detail:</h3>';
			echo '<div style="position: relative; border: 0.1em dashed darkviolet; border-radius: 0.8em; padding: 0.6em; margin: 0.5em 0em; color: black;">';
			echo '<label style="color: darkviolet;">Massage context:</label>';
			
			foreach ($this -> a_msg as $value) {
				echo '<p>' . $value . '</p>';
			}
			echo '</div>';
			echo '</div>';
		}
	}
?>