<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Translation_Check
 * @subpackage Wp_Translation_Check/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Translation_Check
 * @subpackage Wp_Translation_Check/admin
 * @author     Your Name <email@example.com>
 */
class Translation_Tester_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $config;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->config = array(
			'prefix'    => '[fr]',
			'file_path' => '/app/public/wp-content/themes/pile/languages/pile.pot',
			'output' => '/app/public/wp-content/themes/pile/languages/fr_FR.po'
		);

	}

	function add_admin_menu() {
		add_submenu_page( 'tools.php', 'Translation Check', 'Translation Check', 'manage_options', 'wp-translation-check', array(
			$this,
			'admin_options_page'
		) );
	}

	function admin_options_page () { ?>
		<div class="wrap">
		<h1><?php echo esc_html__( 'Translation Tester', 'translation-tester' ); ?></h1>
		<p><?php echo esc_html__( 'You need to upload the POT file you would like to check and set a prefix to be added before each string.', 'translation-tester' ); ?></p>
		<form id="pot_upload" action="<?php echo admin_url( 'tools.php?page=wp-translation-check' ); ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="isSubmitted" value="true" />
			<div class="fieldset">
				<label for="my_pot_prefix"><?php echo esc_html__( 'Set a different prefix:', 'translation-tester' ); ?></label>
				<input type="text" name="pot_prefix" id="my_pot_prefix" value="[test]"><br />
			</div>
			<div class="fieldset">
				<label for="my_pot_upload"><?php echo esc_html__( 'Select the POT file:', 'translation-tester' ); ?></label>
				<input type="file" name="pot_upload_file" id="my_pot_upload" multiple="false" /><br />
			</div>
			<input id="submit_pot_file" name="submit" class="button button-primary" type="submit" value="Test" />
		</form>
		</div>
	<?php

	}

	public function handle_upload() {

		if ( empty( $_POST['isSubmitted']) || $_POST['isSubmitted'] !== 'true') {
			return ;
		}

		if ( empty( $_FILES['pot_upload_file'] ) ) {
			return ;
		}

		if ( ! empty( $_FILES['pot_upload_file']['tmp_name'] )) {
			$this->config['file_path'] = $_FILES['pot_upload_file']['tmp_name'];
			$this->config['prefix'] = $_POST['pot_prefix'];
		}

		if ( file_exists( $this->config['file_path'] ) ) {

			// Parse a po file
			$fileHandler = new poParser\FileHandler( $this->config['file_path'] );

			$poParser = new poParser\PoParser($fileHandler);
			$entries  = $poParser->parse();

			foreach ($entries as $key => $entry ) {
				if ( ! empty( $entry['msgstr'] ) ) {
					foreach ( $entry['msgstr'] as $i => $value ) {
						$entry['msgstr'][$i] = $this->config['prefix'] . $entry['msgid'][0];
					}
				}
				if ( ! empty($entry['msgid_plural'] ) ) {
					foreach ( $entry['msgstr[0]'] as $i => $value ) {
						$entry['msgstr[0]'][$i] = $this->config['prefix'] . $entry['msgid'][0];
					}
					foreach ( $entry['msgstr[1]'] as $i => $value ) {
						$entry['msgstr[1]'][$i] = $this->config['prefix'] . $entry['msgid_plural'][0];
					}
				}
				$entries[$key] = $entry;

				$poParser->setEntry($key, $entries[$key]);
			}

			$output = $poParser->compile();
			$file_name = 'file.po';

			// create the output of the archive
			header( 'Content-Description: File Transfer' );
			header( 'CContent-Type: text/plain; charset=utf8' );
			header( 'Content-Disposition: attachment; filename=' . $file_name  );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate' );
			header( 'Pragma: public' );
			echo $output;

			exit;
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Translation_Check_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Translation_Check_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Translation_Check_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Translation_Check_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );

	}

}
