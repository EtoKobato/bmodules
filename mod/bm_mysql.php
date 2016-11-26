<?PHP
	class sql_toolkit extends mysqli {
		// Important! this class does NOT contain any data cleaner, only income data type restricted.
		// A extend class add more function to mysqli.
		
		// Usage: $a = new sql_toolkit("127.0.0.1", "root", "2WSX-7ujm+3.14", 'aaaaa', 3306, NULL, MYSQLI_CLIENT_SSL);
		
		function __construct(string $host, string $username, string $passwd, string $dbname, int $port, $socket, int $flags) {
			parent :: init();
			
			$this -> ssl_set("D:\OpenSSL-Win64\bin\OUT\mysql.key", "D:\OpenSSL-Win64\bin\OUT\mysql.crt", "D:\OpenSSL-Win64\bin\OUT\mysql.crt", NULL, "AES256-SHA");
			
			if (!parent :: options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {
				die('Setting MYSQLI_INIT_COMMAND failed');
			}

			if (!parent :: options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) {
				die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');
			}

			if (!parent :: options(MYSQLI_SERVER_PUBLIC_KEY, 'D:\OpenSSL-Win64\bin\OUT\mysql.crt')) {
				die('Setting MYSQLI_SERVER_PUBLIC_KEY failed');
			}
			
			if (!parent :: real_connect($host, $username, $passwd, $dbname, $port, $socket, $flags)) {
				die('Connect Error (' . mysqli_connect_errno() . ') '
						. mysqli_connect_error());
			}
		}
		
		function __destruct() {
			unset($this -> result_set);
		}
		
		function add_db(string $db_name, string $char_set = 'utf8') {
			try {
				$this -> real_query("SELECT db FROM `sys`.`schema_object_overview` WHERE `db`='" . $db_name . "';");
				$this -> result_set = $this -> store_result();
				
				// throw new bm_exception(array(var_dump($this -> result_set -> fetch_row()[0])));
				
				if ($this -> result_set -> fetch_row()[0] == $db_name) {
					$this -> result_set -> free();
					throw new bm_exception(array('Database already exist.', 'Your DATABASE: <b>`'. $db_name . '`</b>'));
				}
				if (!$this -> real_query("CREATE SCHEMA `" . $db_name . "` DEFAULT CHARACTER SET " . $char_set . " ;")) {
					throw new bm_exception(array('Wrong character set input.', 'Your CHARACTER SET: <b>`'. $char_set . '`</b>'));
				}
				$this -> result_set -> free();
			}
			catch (bm_exception $e) {
				echo $e -> print_e_msg();
			}
		}
		
		function re_db(string $db_name) {
			try {
				$this -> real_query("SELECT db FROM `sys`.`schema_object_overview` WHERE `db`='" . $db_name . "';");
				$this -> result_set = $this -> store_result();
				
				if ($this -> result_set -> fetch_row()[0] == $db_name) {
					$this -> real_query("DROP DATABASE `" . $db_name . "`;");
				}
				$this -> result_set -> free();
				throw new bm_exception(array('Database does not exist.', 'Your DATABASE: <b>`'. $db_name . '`</b>'));
			}
			catch (bm_exception $e) {
				echo $e -> print_e_msg();
			}
		}
		
		function alt_db(string $db_name, string $char_set) {
			try {
				$this -> real_query("SELECT db FROM `sys`.`schema_object_overview` WHERE `db`='" . $db_name . "';");
				$this -> result_set = $this -> store_result();
				
				// throw new bm_exception(array(var_dump($this -> result_set -> fetch_row()[0])));
				
				if ($this -> result_set -> fetch_row()[0] == $db_name) {
					if (!$this -> real_query("ALTER SCHEMA `" . $db_name . "`  DEFAULT COLLATE " . $char_set . " ;")) {
						$this -> result_set -> free();
						throw new bm_exception(array('Wrong character set input.', 'Your CHARACTER SET: <b>`'. $char_set . '`</b>'));
					}
					$this -> result_set -> free();
					return true;
				}
				$this -> result_set -> free();
				throw new bm_exception(array('Database already exist.', 'Your DATABASE: <b>`'. $db_name . '`</b>'));
			}
			catch (bm_exception $e) {
				echo $e -> print_e_msg();
			}
		}
		
		
		function add_tb(string $db_name, string $tb_name, col_arr $col_str_arr) {
			
			$col_str_arr = $col_str_arr -> show_up();
			$tb_col = '';
			$j = count($col_str_arr);
						
			for ($i = 0; $i < $j - 1; $i++) {
				$tb_col .= $col_str_arr[$i] . ',';
			}
			$tb_col .= $col_str_arr[$j -1];
			
			try {
				$this -> real_query("SELECT db FROM `sys`.`schema_object_overview` WHERE `db`='" . $db_name . "';");
				$this -> result_set = $this -> store_result();
				
				if ($this -> result_set -> fetch_row()[0] == $db_name) {
					if (!$this -> real_query("CREATE TABLE `" . $db_name . "`.`" . $tb_name . "` ( " . $tb_col . " );")) {
							$col_str_arr_x .= '<div style="position: relative; border: 1px dashed black; padding: 0.6em; margin: 0.5em;">';
							$i = 1;
							foreach ($col_str_arr as $value) {
								$col_str_arr_x .= '<h4>COL. ' . $i . '</h4>';
								$col_str_arr_x .= '<p>' . $value . '</p>';
								$i++;
							}
							$col_str_arr_x .= '</div>';
						
						$this -> result_set -> free();
						throw new bm_exception(array('Table already exist or table column prop not setting well.', 'Your TABLE: <b>`'. $tb_name . '`</b>', 'The COLUMN spawn queries:', $col_str_arr_x));
					}
					$this -> result_set -> free();
					return true;
				}
				$this -> result_set -> free();
				throw new bm_exception(array('Database does not exist.', 'Your DATABASE: <b>`'. $db_name . '`</b>'));
			}
			catch (bm_exception $e) {
				echo $e -> print_e_msg();
			}
		}
		
		function re_tb(string $db_name, string $tb_name) {
			try {
				$this -> real_query("SELECT db FROM `sys`.`schema_object_overview` WHERE `db`='" . $db_name . "';");
				$this -> result_set = $this -> store_result();
				
				if ($this -> result_set -> fetch_row()[0] == $db_name) {
					if (!$this -> real_query("DROP TABLE `" . $db_name . "`.`" . $tb_name . "`;")) {
						$this -> result_set -> free();
						throw new bm_exception(array('Table does not exist.', 'Your TABLE: <b>`'. $tb_name . '`</b>'));
					}
					$this -> result_set -> free();
					return true;
				}
				$this -> result_set -> free();
				throw new bm_exception(array('Database does not exist.', 'Your DATABASE: <b>`'. $db_name . '`</b>'));
			}
			catch (bm_exception $e) {
				echo $e -> print_e_msg();
			}
		}
		
		function alt_tb() {
			try {
				throw new bm_exception(array('Does Not Support Yet.'));
			}
			catch (bm_exception $e) {
				echo $e -> print_e_msg();
			}
		}
		
		function add_view() {
			try {
				throw new bm_exception(array('Does Not Support Yet.'));
			}
			catch (bm_exception $e) {
				echo $e -> print_e_msg();
			}
		}
		
		function re_view() {
			try {
				throw new bm_exception(array('Does Not Support Yet.'));
			}
			catch (bm_exception $e) {
				echo $e -> print_e_msg();
			}
		}
		
		function alt_view() {
			try {
				throw new bm_exception(array('Does Not Support Yet.'));
			}
			catch (bm_exception $e) {
				echo $e -> print_e_msg();
			}
		}
		
		function other_query(string $q) {
			try {
				if ($this -> real_query($q)) {
					$this -> result_set = $this -> store_result();
					
					if ($this -> result_set == false) {
						if ($this -> error() === '' && $this -> errno() === 0) {
							// Successed. Query didn't return a result set.
							$this -> result_set -> free();
							return true;
						}
						else {
							// Failed. Has result set ERROR.
							$this -> result_set -> free();
							throw new bm_exception(array('Reading of the result set failed.', 'Maybe the result set was too large.', 'Here\'s some details:', 'ERROR No.: ' . $this -> errno(), 'Error: ' . $this -> error()));
						}
					}
					else {
						// Successed. Has result set.
						for ($i = 0; $i < $this -> result_set -> num_rows; $i++) {
							$this -> result_set -> data_seek($i);
							$row = $this -> result_set -> fetch_row();
							
							$j = 0;
							foreach ($row as $value) {
								// $oqrs is like $other_query_result_set.
								$oqrs[$i][$j] = $value;
								$j++;
							}
						}
						$this -> result_set -> free();
						return $oqrs;
					}
				}
				else {
					// Failed. Has query ERROR.
					throw new bm_exception(array('Query failed:', 'Please check if the syntax right. Or if you have the right privileges.', 'Your query like:', '<p style="position: relative; margin: 1em ;color: red">' . $q . '</p>'));
				}
			}
			catch (bm_exception $e) {
				echo $e -> print_e_msg();
			}
		}	
		
	}
	
	class col_arr {
		// A class to generate speical array for table creation.
		
		// Keep data from changing outside.
		private $fin_arr = array();
		
		function __construct(
				array $col_name, array $d_type, array $pk, array $nn, array $uq, array $b, array $un, array $zf, array $ai, array $g, array $def_exp, array $def_exp_str
			) {
			//Arrays[?] = Number of items $num[?][0], Array Name $num[?][1]     , Original Data $num[?][2]	That\'s all.
			$num[0]		= [count($col_name)			, 'Column Name'				, $col_name              ]		;
			$num[1]		= [count($d_type)			, 'Data Type'				, $d_type                ]		;
			$num[2]		= [count($pk)				, 'Primary Key'				, $pk                    ]		;
			$num[3]		= [count($nn)				, 'Not Null'				, $nn                    ]		;
			$num[4]		= [count($uq)				, 'Unique'					, $uq                    ]		;
			$num[5]		= [count($b)				, 'Binary'					, $b                     ]		;
			$num[6]		= [count($un)				, 'Unsigned'				, $un                    ]		;
			$num[7]		= [count($zf)				, 'Zero Fill'				, $zf                    ]		;
			$num[8]		= [count($ai)				, 'Auto Increment'			, $ai                    ]		;
			$num[9]		= [count($g)				, 'Generated'				, $g                     ]		;
			$num[10]	=[count($def_exp)			, 'Default/Expression'		, $def_exp               ]		;
			$num[11]	=[count($def_exp_str)		, 'Value In D/E'			, $def_exp_str           ]		;
				
			if (
			$num[0][0] > 0 &&
			$num[1][0] == $num[0][0] &&
			$num[2][0] == $num[0][0] &&
			$num[3][0] == $num[0][0] &&
			$num[4][0] == $num[0][0] &&
			$num[5][0] == $num[0][0] &&
			$num[6][0] == $num[0][0] &&
			$num[7][0] == $num[0][0] &&
			$num[8][0] == $num[0][0] &&
			$num[9][0] == $num[0][0] &&
			$num[10][0] == $num[0][0] &&
			$num[11][0] == $num[0][0]
			) {
				for ($i = 0; $i < $num[0][0]; $i++) {
					if ($pk[$i] == true) {
						$pk[$i] = " PRIMARY KEY (`" . $col_name[$i] . "`), ";
					}
					else {
						$pk[$i] = '';
					}
					if ($nn[$i] == true) {
						$nn[$i] = " NOT NULL ";
					}
					else {
						$nn[$i] = ' NULL ';
					}
					if ($uq[$i] == true) {
						$uq[$i] = " UNIQUE INDEX `" . $col_name[$i] . "_UNIQUE` (`" . $col_name[$i] . "` ASC), ";
					}
					else {
						$uq[$i] = '';
					}
					if ($b[$i] == true) {
						$b[$i] = " BINARY ";
					}
					else {
						$b[$i] = '';
					}
					if ($un[$i] == true) {
						$un[$i] = " UNSIGNED ";
					}
					else {
						$un[$i] = '';
					}
					if ($zf[$i] == true) {
						$zf[$i] = " ZEROFILL ";
					}
					else {
						$zf[$i] = '';
					}
					if ($ai[$i] == true) {
						$ai[$i] = " AUTO_INCREMENT ";
						$g[$i] = '';
					}
					else {
						$ai[$i] = '';
					}
					if ($g[$i] == true) {
						$g[$i] = " GENERATED ALWAYS AS (" . $def_exp_str . ") VIRTUAL ";
						$ai[$i] = '';
						$def_exp[$i] = '';
					}
					else {
						$g[$i] = '';
					}
					if ($def_exp[$i] == true) {
						$def_exp[$i] = " DEFAULT '" . $def_exp_str . "' ";
						$g[$i] = '';
					}
					else {
						$def_exp[$i] = '';
					}
					
					$out[$i] = $pk[$i] . $uq[$i] . '`' . $col_name[$i] . '`' . $d_type[$i] . $nn[$i] . $b[$i] . $un[$i] . $zf[$i] . $ai[$i] . $g[$i] . $def_exp[$i];
				}
				
				$this -> fin_arr = $out;
			}
			else {
				try {
					$this -> fin_arr = array('***ERROR***', '***ERROR***', '***ERROR***', 'The important things comes three times.');
					
					$str = '<div style="position: relative; border: 1px dashed black; padding: 0.6em; margin: 0.5em;">';
					$str .= '<table><tbody>';
					for ($i = 0; $i < 12; $i++) {
						$str .= '<tr';
						if ($num[$i][0] != $num[0][0]) $str .= ' style="color: red; font-weight: bold;"';
						$str .= '><td> ' . $num[$i][1] . '</td>';
						$str .= '<td>&nbsp;&nbsp;' . $num[$i][0] . '&nbsp;&nbsp;</td>';
						$str .= '<td>' . ' ITEMS:' . '</td>';
						$str .= '<td>' . ' ' . '</td>';
						
						$str_x = '';
						foreach ($num[$i][2] as $value) {
							if (is_bool($value)) {
								if ($value) {
									$value = 'true';
								}
								else {
									$value = 'false';
								}
							}
 							$str_x .= '<td>' . $value .'</td>';
						}
						
						$str .= '<td>' . $str_x . '</td>';
						$str .= '</tr>';
					}
					$str .= '</tbody></table>';
					$str .= '</div>';
					
					throw new bm_exception(array('The number of each group\'s elements must be equal, that given to CLASS \'col_arr\'', 'Group Detail:', $str));
				}
				catch (bm_exception $e) {
					echo $e -> print_e_msg($e);
				}
			}
		}
		
		function show_up() {
			return $this -> fin_arr;
		}
	}
?>
