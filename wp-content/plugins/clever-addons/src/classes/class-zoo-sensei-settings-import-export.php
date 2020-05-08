<?php
/**
 * ZooSenseiSettingsImportExport
 */
class ZooSenseiSettingsImportExport
{
    /**
     * Hook suffix
     *
     * @var  string
     */
    public $hook_suffix;

    /**
     * Woothemes Seensei
     *
     * @var  object
     */
    protected $sensei;

    /**
     * Construtor
     */
     function __construct(Sensei_Main $sensei)
     {
         $this->sensei = $sensei;
     }

     /**
      * Add menu page
      *
      * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
      *
      * @see    https://developer.wordpress.org/reference/hooks/admin_menu/
      */
     function _add($context)
     {
         $this->hook_suffix = add_submenu_page(
             'sensei',
             esc_html__('Import/Export', 'zoo-framework'),
             esc_html__('Import/Export', 'zoo-framework'),
             'manage_options',
             basename(__FILE__),
             array($this, '_render')
        );
     }


     /**
      * Render
      *
      * @internal    Used as a callback. PLEASE DO NOT RECALL THIS METHOD DIRECTLY!
      */
     function _render()
     {
         ?><div class="wrap">
             <h2><?php _e('Import/Export Sensei Settings', 'zoo-framework') ?></h2>
             <table class="form-table">
 				<tbody>
 					<tr>
 						<th scope="row"><strong><?php _e('Import Settings', 'zoo-framework') ?></strong></th>
 						<td>
 							<p><?php _e('Choose an import file from your computer and click "Upload and Import" button.', 'zoo-framework') ?></p>
 							<form enctype="multipart/form-data" method="post" action="<?php echo menu_page_url(basename(__FILE__), 0) ?>">
                                 <?php wp_nonce_field('sensei-settings-import-d4ta', 'sensei-settings-import-n0nc3') ?>
 								<input type="hidden" name="clever-portfolio-import" value="1">
 								<label for="sensei-settings-import-data" class="screen-reader-text">
                                     <?php _e('Upload File', 'zoo-framework') ?>:
                                 </label>
 								<p><input type="file" id="sensei-settings-import-data" name="sensei-settings-import-data"></p>
 								<?php submit_button(esc_html__('Upload and Import', 'zoo-framework'), 'primary', 'upload') ?>
 							</form>

 						</td>
 					</tr>

 					<tr>
 						<th scope="row"><strong><?php esc_html_e('Export Settings', 'zoo-framework') ?></strong></th>
 						<td>
 							<p><?php _e('Once you have saved the export file, you can use the import function to import the settings.', 'zoo-framework') ?></p>
 							<form method="post" action="<?php echo menu_page_url(basename(__FILE__), 0) ?>">
                                 <?php wp_nonce_field('sensei-settings-export-d4ta', 'sensei-settings-export-n0nc3') ?>
 								<?php submit_button(esc_html__('Download Export File', 'zoo-framework'), 'primary');
 								?>
 							</form>
 						</td>
 					</tr>
 				</tbody>
 			</table>
         </div><?php
     }

     /**
      * Import
      */
     function _import()
     {
         if (!$this->validateCurrentPage()) {
 			return;
         }

         if (!isset($_POST['sensei-settings-import-n0nc3']) || !wp_verify_nonce($_POST['sensei-settings-import-n0nc3'], 'sensei-settings-import-d4ta') || empty($_FILES['sensei-settings-import-data']['tmp_name'])) {
             return;
         }

 		 $upload   = file_get_contents($_FILES['sensei-settings-import-data']['tmp_name']);
 		 $settings = json_decode($upload, true);
         $imported = update_option($this->sensei->settings->token, $settings);
         $page_url = html_entity_decode(menu_page_url(basename(__FILE__), 0));

 	 	 if (!$settings || $_FILES['sensei-settings-import-data']['error'] || !$imported) {
             wp_redirect($page_url . '&imported=false');
 		 }

 		 wp_redirect($page_url. '&imported=true');

 		 exit;
     }

     /**
      * Export
      */
     function _export()
     {
         if (!$this->validateCurrentPage()) {
             return;
         }

         if (!isset($_POST['sensei-settings-export-n0nc3']) || !wp_verify_nonce($_POST['sensei-settings-export-n0nc3'], 'sensei-settings-export-d4ta')) {
             return;
         }

         $settings = get_option($this->sensei->settings->token);

 		if (!$settings) {
            return;
        }

 	    $settings = json_encode((array)$settings);

 	    header('Content-Description: File Transfer');
 	    header('Cache-Control: public, must-revalidate');
 	    header('Pragma: hack');
 	    header('Content-Type: application/json');
 	    header('Content-Disposition: attachment; filename="sensei-settings-' . date('Ymd-His') . '.json"');
 	    header('Content-Length: ' . mb_strlen($settings));

 	    exit($settings);
 	 }

     /**
      * Do notification
      *
      * @see    https://developer.wordpress.org/reference/hooks/admin_notices/
      */
     function _notify()
     {
         if (!$this->validateCurrentPage()) {
 			return;
        }

         if (isset($_REQUEST['imported']) && 'true' === $_REQUEST['imported']) :
             ?><div class="updated notice is-dismissible">
                 <p><strong>
                     <?php _e('Settings have been imported successfully!', 'zoo-framework') ?>
                 </strong></p>
                 <button type="button" class="notice-dismiss">
                     <span class="screen-reader-text">
                         <?php _e('Dismiss this notice.') ?>
                     </span>
                 </button>
             </div><?php
         endif;
         if (isset($_REQUEST['imported']) && 'false' === $_REQUEST['imported']) :
             ?><div class="updated error is-dismissible">
                 <p><strong>
                     <?php _e('Failed to import settings. Please try again!', 'zoo-framework') ?>
                 </strong></p>
                 <button type="button" class="notice-dismiss">
                     <span class="screen-reader-text">
                         <?php _e('Dismiss this notice.') ?>
                     </span>
                 </button>
             </div><?php
         endif;
     }

     /**
      * Validate current page
      */
     private function validateCurrentPage()
     {
     	global $page_hook;

     	if (isset($page_hook) && $page_hook === $this->hook_suffix) {
     		return true;
        }

     	if (isset($_REQUEST['page']) && $_REQUEST['page'] === basename(__FILE__)) {
     		return true;
        }

     	return false;
     }
}

$ZooSenseiSettingsImportExport = new ZooSenseiSettingsImportExport(Sensei());
add_action('admin_menu', array($ZooSenseiSettingsImportExport, '_add'), 99);
add_action('admin_init', array($ZooSenseiSettingsImportExport, '_export'), 0, 0);
add_action('admin_init', array($ZooSenseiSettingsImportExport, '_import'), 0, 0);
add_action('admin_notices', array($ZooSenseiSettingsImportExport, '_notify'), 0, 0);
