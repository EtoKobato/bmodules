<?PHP

class write_to_xml {
	
	function __construct() {
		$l0 = <<<XML
<?xml version='1.0' encoding='utf-8'?>
<module_list/>
XML;
		$this -> xml = simplexml_load_string($l0);
	}
	
	public function add($m_id, $m_name, $m_path) {
		$this -> l1 = $this -> xml -> addChild('module');
		$this -> l1 -> addAttribute('id', $m_id);
		$this -> l1 -> addAttribute('name', $m_name);
		$this -> l1 -> addAttribute('path', $m_path);
	}
	
	public function out($o_path) {
		$this -> xml -> asXML($o_path.'.xml');
	}
	
}

class read_from_xml {
	
	function __construct() {
		$this -> l0 = "bm_mlist.xml";
	}
	
	function change_target($file) {
		$this -> l0 = $file;
	}
	
	public function length($l0) {
		$this -> xml = simplexml_load_file($l0);
		return count($this -> xml -> children());
	}
	
}

function read_file($file = "bm_mlist.xml") {

	$xml_ele = new read_from_xml($file);
	
	$xml_iterator = new SimpleXMLIterator($file, 0, true);
	$xml_iterator -> rewind();
	for ($i = 0; $i < ($xml_ele -> length($file)); $i++) {
		
		$xml_attr = $xml_iterator -> current() -> attributes();
		
		printf('<div id="' . 'd' . ($i + 1) . '" style="border: 0.1em solid; border-radius: 0.8em; padding: 1em; margin: 0.5em 0em">');
		printf('<label>No.' . ($i + 1) . ' </label>');
		printf('<br>');
		printf('<label>Name: </label>');
		printf('<br>');
		printf('<input type="text" name="field_name' . ($i + 1) . '" value="' . $xml_attr["name"] . '" />');
		printf('<br>');
		printf('<label>Path: </label>');
		printf('<br>');
		printf('<input type="text" name="field_path' . ($i + 1) . '" value="' . $xml_attr["path"] . '" />');
		printf('<br>');
		printf('</div>');
		
		$xml_iterator -> next();
	}
	
	echo "<input type=\"hidden\" name=\"field_count\" value=" . $i . " />";
}

function save() {
	$module_list = new write_to_xml();
	
	for ($i = 0; $i < $_POST['field_count']; $i++) {
		printf('<div id="' . 'd' . ($i + 1) . '" style="border: 0.1em solid; border-radius: 0.8em; padding: 1em; margin: 0.5em 0em">');
		printf('<label>No.' . ($i + 1) . ' </label>');
		printf('<br>');
		printf('<label>Name: </label>');
		printf('<br>');
		printf('<input type="text" name="field_name' . ($i + 1) . '" />');
		printf('<br>');
		printf('<label>Path: </label>');
		printf('<br>');
		printf('<input type="text" name="field_path' . ($i + 1) . '" />');
		printf('<br>');
		printf('</div>');
		
		$module_list -> add($i, $_POST["field_name" . ($i + 1) . ""], $_POST["field_path" . ($i + 1) . ""]);
		
	}
	
	$module_list -> out('bm_mlist');
}

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<form method="post" action="bm_init.php">
	<label>Save Mode: </label>
	<input type="checkbox" name="field_type" <?PHP if ($_POST['field_type'] == 'on') {echo 'checked';} ?> >
	<br>
	<br>
	<?PHP
	if ($_POST['field_type'] == 'on') {
		echo '<label>Count: </label>';
		echo '<br>';
		echo "<input type=\"number\" name=\"field_count\" value=" . $_POST['field_count'] . " />";
		echo '<br>';
		
		save();
	}
	else {
		read_file();
	}
	?>
	<input type="submit" />
</form>
</body>
</html>