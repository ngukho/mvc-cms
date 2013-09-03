<?php

if(!defined('_ROOT')) {
	exit('Access Denied');
}
class bSession extends CaoBox{
	var $timeout = 30;
	var $enable = false;
	var $db;
	var $prefix;
	function bSession($db){
		$this->db = $db;
		$this->prefix = $db->prefix;
		
	}
	
	function load(){
		if($this->enable){
			ini_set('session.save_handler', 'user');
			session_set_save_handler(
				array($this, 'open'),
				array($this, 'close'),
				array($this, 'read'),
				array($this, 'write'),
				array($this, 'destroy'),
				array($this, 'gc')
			);
		}else{
			$ini_session = ini_get('session.save_path');
			if(!is_writable($ini_session)) $this->getError('We cannot create session, please set  <strong>WRITE</strong> access for folder <strong>'.$ini_session.'</strong>');

		}
		session_start();
	
	}
	
	function open() {
	}
	function close() {
	}
	function read($id) {
		$sql = $this->db->query("SELECT `data` FROM `".$this->prefix."session` WHERE `session_id` = '".addslashes($id)."'");
		$data =  $sql->fetch();
		return $data['data'];
	}
	function write($id, $data) {
		$sql = "REPLACE INTO `".$this->prefix."session`(`session_id`,`expires`,`data`) VALUES('".addslashes($id)."', '".addslashes(time())."', '".addslashes($data)."')";
		return $this->db->query($sql) or die($sql);
	}
	
	function destroy($id) {
		return $this->db->query("DELETE FROM `".$this->prefix."session` WHERE `session_id` = '".addslashes($id)."'");
	}
	function gc($max) {
		return $this->db->query("DELETE FROM `".$this->prefix."session` WHERE `expires` < '".(time() - $this->timeout)."'");
	}
}

$session = new bSession($model);
$session->enable = true;
$session->load();
/*//ini_set('session.gc_probability', 50);
ini_set('session.save_handler', 'user');
$session = new bSession();
session_set_save_handler(array($session, 'open'),
                         array($session, 'close'),
                         array($session, 'read'),
                         array($session, 'write'),
                         array($session, 'destroy'),
                         array($session, 'gc'));
*/						 

// below sample main


?>