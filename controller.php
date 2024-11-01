<?php
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
	class trackitController {
		public static $LOCATION_HEADER  = 0;
		public static $LOCATION_FOOTER  = 1;
		public static $LOCATIONS = array(
				0 => 'Header',
				1 => 'Footer'
			);


		public function databaseInstall() {
			global $wpdb;
			global $trackit_db_version;

			$table_name = $wpdb->prefix . 'trackit';
			
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name tinytext NOT NULL,
				code text NOT NULL,
				location int NOT NULL,
				date_created datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			add_option( 'trackit_db_version', '1.0' );
		}

		public function getAll() {
			global $wpdb;
			$results = $wpdb->get_results('SELECT * FROM `'. $wpdb->prefix . 'trackit`','ARRAY_A');
			$newResults = array();
			foreach ($results as $key => $value) {
				$value['edit'] = '<a href="'.$_SERVER["REQUEST_URI"].'&action=edit&id='.$value['id'].'">'.trackitIcons::icon('edit').'</a>';
				$value['delete'] = '<a href="'.$_SERVER["REQUEST_URI"].'&action=delete&id='.$value['id'].'">'.trackitIcons::icon('delete').'</a>';
				$newResults[$key] = $value;
			}
			return $newResults;
		}

		public function getByID($id) {
			global $wpdb;
			return $wpdb->get_row($wpdb->prepare("SELECT * FROM `". $wpdb->prefix . "trackit` WHERE `id` = %d",$id),'ARRAY_A');
		}

		public function add($name, $location, $code){
			global $wpdb;
			return  $wpdb->query($wpdb->prepare("INSERT INTO `". $wpdb->prefix . "trackit` (`name`,`location`,`code`)VALUES(%s,%d,%s)",$name,$location,$code));
		}

		public function update($id, $name, $location, $code){
			global $wpdb;
			return  $wpdb->query($wpdb->prepare("UPDATE `". $wpdb->prefix . "trackit` SET `name`=%s,`location`=%d,`code`=%s WHERE `id`= %d",$name,$location,$code,$id));
		}

		public function delete($id){
			global $wpdb;
			return  $wpdb->query($wpdb->prepare("DELETE FROM `". $wpdb->prefix . "trackit` WHERE `id`= %d",$id));
		}

		public function processForm(){
			if (!empty($_GET['action'])) {
				switch ($_GET['action']) {
					case 'add':
						if (count($_POST)>0) {
							$this->add($_POST['name'], $_POST['location'], $_POST['code']);
							header('location: '.self::getPluginURL());
							exit();
						}
						break;
					case 'edit':
						if (!empty($_POST['confirm']) && $_POST['confirm'] == '1'){
							$this->update($_GET['id'],$_POST['name'], $_POST['location'], $_POST['code']);
							header('location: '.self::getPluginURL());
							exit();
						}
						break;
					case 'delete':
						if (!empty($_GET['confirm']) && $_GET['confirm'] == '1'){
							$this->delete($_GET['id']);
							header('location: '.self::getPluginURL());
							exit();
						}
						break;
				}
			}
		}

		public function injectHeaderTrackers(){
			$trackers = $this->getAll();
			foreach ($trackers as $tracker) {
				if($tracker['location'] == self::$LOCATION_HEADER){
					echo  stripslashes_deep($tracker['code']);
				}
			}
		}

		public function injectFooterTrackers(){
			$trackers = $this->getAll();
			foreach ($trackers as $tracker) {
				if($tracker['location'] == self::$LOCATION_FOOTER){
					echo  stripslashes_deep($tracker['code']);
				}
			}
		}

		public function addSettingLink($links){
			$theLink = '<a href="plugins.php?page=trackit">' . __( 'Settings' ) . '</a>';
		    array_push( $links, $theLink );
		  	return $links;
		}

		static function getPluginURL(){
			return admin_url('plugins.php?page=trackit');
		}
	}

?>