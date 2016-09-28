<?php
class BASEModel {
	var $dbhost;
	var $dbport;
	var $dbname;
	var $dbchar;
	var $table;
	var $dbms; // 锟斤拷锟捷匡拷锟斤拷锟斤拷
	var $baseuser; // 锟斤拷锟捷匡拷锟斤拷锟斤拷锟矫伙拷锟斤拷
	var $pass; // 锟斤拷应锟斤拷锟斤拷锟斤拷
	var $dsn;
	var $conn;
	function __construct() {
		global $basename;
		global $baseport;
		global $basehost;
		global $baseuser;
		global $basepass;
		global $basechar;
		global $basems;
		global $basedsn;
		
		$this->dbhost = $basehost;
		$this->dbport = $baseport;
		$this->dbname = $basename;
		$this->dbchar = $basechar;
		$this->dbms = $basems;
		$this->baseuser = $baseuser;
		$this->pass = $basepass;
		$this->dsn = $basedsn;
		
		try {
			$this->conn = new PDO ( $this->dsn, $this->baseuser, $this->pass ); // long connect array(PDO::ATTR_PERSISTENT => true)
			$this->conn->query ( 'set names utf8' );
		} catch ( PDOException $e ) {
			die ( "Error!: " . $e->getMessage () . "<br/>" );
		}
	}
	
	// 通锟矫诧拷询锟斤拷锟斤拷
	function getall($sql,$values=array()) {
		$rest = $this->conn->prepare( $sql );
		
		if (!$rest->execute($values)) {
			
			return false;
		}
		$rows = $rest->fetchall( PDO::FETCH_ASSOC );
		
		return empty ( $rows ) ? false : $rows;
	}
	function getrow($sql,$values=array()) {
		$rest = $this->conn->prepare ( $sql );
		if (!$rest->execute($values)) {
			return false;
		}
		$rows = $rest->fetch ( PDO::FETCH_ASSOC );
		
		return empty ( $rows ) ? false : $rows;
	}
	function getone($sql,$values=array()) {
		$rest = $this->conn->prepare ( $sql );
		// var_dump($rest);
		// die();
		if (!$rest->execute($values)) {
			return false;
		}
		$rows = $rest->fetch ();
		
		return empty ( $rows ) || ! isset ( $rows [0] ) ? false : $rows [0];
	}
	function exec($sql,$values=array()) {
		$rest = $this->conn->prepare ( $sql );
		return $rest->execute();
	}
	function findone($name, $table, $wheres = false) {
		if ($wheres === false) {
			$where = '';
		} else {
			if (is_array ( $wheres )) {
				$where = 'where ';
				foreach ( $wheres as $k => $v ) {
					$where .= $k . " = ";
					$where .= "'$v' and ";
				}
				$where = trim ( $where, ' and ' );
			} else {
				$where = '';
			}
		}
		
		$query = "select $name from $table ";
		// echo $query;
		$rest = $this->getone ( $query . $where );
		
		return $rest ? $rest : false;
	}
	function findrow($names = '', $table, $wheres = false) {
		if ($wheres === false) {
			$where = '';
		} else {
			if (is_array ( $wheres )) {
				$where = ' where ';
				foreach ( $wheres as $k => $v ) {
					$where .= $k . " = ";
					$where .= "'$v' and ";
				}
				$where = trim ( $where, ' and ' );
			} else {
				$where = '';
			}
		}
		
		if ($names === '') {
			$name = ' * ';
		} else {
			if (is_array ( $names )) {
				$name = ' ';
				foreach ( $names as $v ) {
					// $where.=$k." = ";
					$name .= "'$v',";
				}
				$name = trim ( $where, ',' ) . ' ';
			} else {
				$name = '';
			}
		}
		
		$query = "select $name from $table $where";
		// echo $query;
		$rest = $this->getrow ( $query . ' limit 1' );
		
		return $rest ? $rest : false;
	}
	function findall($names = false, $table, $wheres = false) {
		if ($wheres === false) {
			$where = '';
		} else {
			if (is_array ( $wheres )) {
				$where = 'where ';
				foreach ( $wheres as $k => $v ) {
					$where .= $k . " = ";
					$where .= "'$v' and ";
				}
				$where = trim ( $where, ' and ' );
			} else {
				$where = '';
			}
		}
		
		if ($names === false) {
			$name = ' * ';
		} else {
			if (is_array ( $names )) {
				$name = ' ';
				foreach ( $names as $k => $v ) {
					// $where.=$k." = ";
					$name .= "$v,";
				}
				$name = trim ( $name, ',' ) . ' ';
			} else {
				$name = '';
			}
		}
		
		$query = "select $name from $table ";
		// echo $query;
		$rest = $this->getall ( $query . $where );
		
		return $rest ? $rest : false;
	}
	function insert($data, $table) {
		if (is_array ( $data )) {
			$key = "(";
			$values = "(";
			foreach ( $data as $k1 => $v1 ) {
				$key .= "$k1,";
				$values .= "'$v1',";
			}
			$key = trim ( $key, ',' ) . ") ";
			$values = trim ( $values, ',' ) . ") ";
		} else {
			return false;
		}
		
		$query = "insert into $table $key values $values";
		// var_dump($query);
		$rest = $this->exec ( $query );
		return $rest;
	}
	function update($data, $table, $wheres = false) {
		if (is_array ( $data )) {
			$sets = 'set ';
			foreach ( $data as $k1 => $v1 ) {
				$sets .= "$k1 = ";
				$sets .= "'$v1',";
			}
			$sets = trim ( $sets, ',' ) . " ";
		} else {
			return false;
		}
		
		if ($wheres === false) {
			$where = '';
		} else {
			if (is_array ( $wheres )) {
				$where = 'where ';
				foreach ( $wheres as $k => $v ) {
					$where .= $k . " = ";
					$where .= "'$v' and ";
				}
				$where = trim ( $where, ' and ' );
			} else {
				$where = '';
			}
		}
		
		$query = "update $table ";
		file_put_contents ( 'test.txt', "\r\n  update璇彞涓� " . $query . $sets . $where, FILE_APPEND );
		// var_dump($query);
		$rest = $this->exec ( $query . $sets . $where );
		return $rest;
	}
	function updates($data, $table, $wheres = false) {
		if (is_array ( $data )) {
			$sets = 'set ';
			foreach ( $data as $k1 => $v1 ) {
				$sets .= "$k1 = ";
				$sets .= "'$v1',";
			}
			$sets = trim ( $sets, ',' ) . " ";
		} else {
			return false;
		}
		
		if ($wheres === false) {
			$where = '';
		} else {
			if (is_array ( $wheres )) {
				$where = 'where ';
				foreach ( $wheres as $k => $v ) {
					$where .= $k . " = ";
					$where .= "'$v' and ";
				}
				$where = trim ( $where, ' and ' );
			} else {
				$where = '';
			}
		}
		
		$query = "update $table ";
		file_put_contents ( 'test.txt', "\r\n  update璇彞涓� " . $query . $sets . $where, FILE_APPEND );
		// var_dump($query);
		$rest = $this->exec ( $query . $sets . $where );
		return $rest;
	}
	
	function test(){
		
		$colour = 'red';
		$sth = $this->conn->prepare("SELECT * from article WHERE title like ?");
		//$sth->bindParam(1, $calories, PDO::PARAM_STR);
		//$calories = '213';
		//$sth->bindParam(':colour', $colour, PDO::PARAM_STR, 12);
	 	$sth->execute(array('%21%'));
		return $sth->fetchAll();
	}
}