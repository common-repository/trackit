<?php 
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

	class trackitUI {

		function __construct(){
			add_action( 'admin_menu', array($this,'trackitMenu'));
			add_action('admin_init', array($this,'loadResources'));
		}

		function trackitMenu() {
			add_submenu_page( 'plugins.php', 'Track It Setting', 'Track It', 'manage_options', 'trackit',  array($this,'render'));
		}

		function loadResources() {
			$stylesFile = plugins_url( 'styles.css', __FILE__ ) ;
			wp_register_style( 'loadStyles', $stylesFile );
			wp_enqueue_style('loadStyles');
		}

		function render() {
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			$trackitController = new trackitController();

			echo '<div class="wrap trackit">';
			echo '<h3>Track It</h3>';
			echo '<p>Trackit is a simple plugin that allows the user to add a tracking code, CSS, Javascript, & ... to anywhere(Header or Footer) in  WordPress.</p>';

			if (empty($_GET['action'])) {
				$results = $trackitController->getAll();
				require_once('view-list.php');
			} else if ($_GET['action'] == 'add') {
				require_once('view-edit.php');
			} else if ($_GET['action'] == 'edit' || $_GET['action'] == 'delete') {
				if (!empty($_GET['id'])) {
					$id = $_GET['id'];
					$result = $trackitController->getByID($id);
					if(!empty($result)){
						$id = stripslashes_deep($result['id']);
						$code = stripslashes_deep($result['code']);
						$location = stripslashes_deep($result['location']);
						$name = stripslashes_deep($result['name']);
						require_once('view-edit.php');
					}
				}
			}

			echo '</div>';

		}
	}

	class trackitList extends WP_List_Table{
		private $columns = array(
			'id'=>'ID',
			'name'=>'Name',
			'location'=>'Location',
			'date_created'=>'Date Created',
			'edit'=>'Edit',
			'delete'=>'Delete'
			);
		
		function get_columns(){
			return $this->columns;
		}

		function prepare($data){
			$columns = $this->get_columns();
			$hidden = array();
			$sortable = array();
			$this->_column_headers = array($columns,$hidden,$sortable);
			$this->items =  $data;
		}

		function column_default( $item, $column_name ) {
		  switch( $column_name ) { 
		    case 'location':
		     	return trackitController::$LOCATIONS[$item[$column_name]];
		     	break;
		    case 'id':
		    case 'name':
		    case 'date_created':
		    case 'edit':
		    case 'delete':
		     	return $item[$column_name];
		     	break;
		    default:
		    	return print_r( $item, true ) ;
		  }
		}
	}

	class trackitIcons {
		static function icon($name){
			switch ($name) {
				case 'edit':
					return '<svg class="ti-icon-edit" viewBox="0 0 512 512"><path d="M432 0c44.182 0 80 35.817 80 80 0 18.010-5.955 34.629-16 48l-32 32-112-112 32-32c13.371-10.045 29.989-16 48-16zM32 368l-32 144 144-32 296-296-112-112-296 296zM357.789 181.789l-224 224-27.578-27.578 224-224 27.578 27.578z"></path></svg>';
					break;
				case 'delete':
					return '<svg class="ti-icon-delete" viewBox="0 0 512 512"><path d="M96 512h320l32-352h-384zM320 64v-64h-128v64h-160v96l32-32h384l32 32v-96h-160zM288 64h-64v-32h64v32z"></path></svg>';
					break;
				default:
					return '';
					break;
			}
		}
	}
?>