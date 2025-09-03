<?php

/**
 * class FrontendViewHandler
 *
 */

namespace BitCode\BitFormPro\Admin\BfTable;

use BitCode\BitForm\Core\Util\FrontendHelpers;
use BitCode\BitForm\Admin\Form\AdminFormHandler;
use BitCode\BitFormPro\Admin\BfTable\Table;
use BitCode\BitForm\Core\Util\FieldValueHandler;
use BitCode\BitForm\Core\Form\FormManager;
use WP_Error;

final class FrontendViewHandler
{
    private $_formPermission = null;
    private $uploadUrl = BITFORMS_UPLOAD_BASE_URL . DIRECTORY_SEPARATOR . 'uploads';
    public function __construct()
    {
        // before markup load - table Id [], posts [1,2]
        add_action('wp_enqueue_scripts', [$this, 'loadAssets']);
        // markup loads - table Id []
        add_shortcode('bitform-view', [$this, 'handleFrontendViewRenderRequest']);
        // after markup load - table Id [1,35,3]

    }

    public function loadAssets($viewId)
    {
        if (class_exists('\BitCode\BitForm\Core\Util\FrontendHelpers') && method_exists('\BitCode\BitForm\Core\Util\FrontendHelpers', 'getAllViewIdsInPage')) {
            $bfUniqViewIds = FrontendHelpers::getAllViewIdsInPage();
            $isPageBuilder = FrontendHelpers::$isPageBuilder;
        } else {
            $bfUniqViewIds = [];
            $isPageBuilder = false;
        }

        if (!empty($viewId)) {
            $viewIds = [$viewId];
        } else {
            $viewIds = $bfUniqViewIds;
        }

        // $bfMultipleTableExists = $isPageBuilder ? true : count($bfUniqViewIds) > 1;

        foreach ($viewIds as $viewId) {
            global $bitform_view_dequeued_styles;
            if (is_array($bitform_view_dequeued_styles) && in_array($viewId, $bitform_view_dequeued_styles)) {
                continue;
            }

            $newViewId = 'view-' . $viewId;

            $formUpdateVersion = get_option('bit-form_form_update_version');
            if (!wp_style_is('bitform-style-' . $newViewId) && is_readable(BITFORMS_CONTENT_DIR . '/form-styles/bitform-' . $newViewId . '.css')) {
                wp_enqueue_style(
                    'bitform-style-' . $newViewId,
                    BITFORMS_UPLOAD_BASE_URL . "/form-styles/bitform-{$newViewId}.css",
                    [],
                    $formUpdateVersion
                );
                if ($isPageBuilder) {
                    $formStyle = file_get_contents(BITFORMS_CONTENT_DIR . '/form-styles/bitform-' . $newViewId . '.css');
                    echo sprintf("<style id='bitform-style-{$newViewId}'>%s</style>", $formStyle);
                }
            }
        }

    }

    public function handleFrontendViewRenderRequest($atts)
    {
        if (isset($atts['id'])) {
            $atts = shortcode_atts(['id' => 0], $atts);
            $viewId = intval($atts['id']);
        }
        if (!$viewId) {
            return __('View ID cannot be empty', 'bit-form');
        }

        $bfEntryId = isset($_GET['bf_entry_id']) ? sanitize_text_field($_GET['bf_entry_id']) : null;

        $isPageBuilder = FrontendHelpers::checkIsPageBuilder($_SERVER);

        ob_start();
        if ($isPageBuilder) {
            $this->loadAssets($viewId);
        }

        if ($bfEntryId) {
            if (!is_numeric($bfEntryId)) {
                return new WP_Error('invalid_entry_id', __('Invalid Entry ID', 'bit-form'));
            }
            $this->singleEntryView($viewId, $bfEntryId);
        } else {
            $this->tableView($viewId);
        }

        return ob_get_clean();
    }

    private function tableView($viewId)
    {

        if (!$this->isExist($viewId)) {
            echo sprintf(__('#%s no. View doesn\'t exists', 'bit-form'), $viewId);
            return ;
        }
        $tableMarkup = new TableMarkUp($viewId);

        $formId = $tableMarkup->getFormId();
        // check is FormManager class exists
        if (!class_exists('BitCode\BitForm\Core\Form\FormManager')) {
            echo __('Bit Form FormManager class is not available', 'bit-form');
            return ;
        }
        $formManager = FormManager::getInstance($formId);
        $this->_formPermission = $formManager->getFormPermission();
        $tableMarkup->setFormPermission($this->_formPermission);
        $this->frontendStript($viewId);
        if (FrontendHelpers::is_current_user_can_access($formId, 'entryViewAccess')) {
            echo $tableMarkup->tableViewer();
        }


        $formUpdateVersion = get_option('bit-form_form_update_version');

        wp_enqueue_script(
            'bit-form-view-script',
            $this->getJSFileSrc(),
            [],
            $formUpdateVersion,
            true
        );
    }

    private function singleEntryView($viewId, $entryId)
    {
        $table = Table::gatATable(['id' => $viewId], null);
        $singleViewDetails = \json_decode($table->single_entry_view_config);
        $body = $singleViewDetails->body;
        $formID = $table->form_id;

        if (class_exists('\BitCode\BitForm\Admin\Form\AdminFormHandler') && method_exists('\BitCode\BitForm\Admin\Form\AdminFormHandler', 'getSingleEntry')) {

            $formManager = FormManager::getInstance($formID);
            $this->_formPermission = $formManager->getFormPermission();
            $adminFormHandler = new AdminFormHandler();
            $getEntry = $adminFormHandler->getSingleEntry($formID, $entryId);

            if (is_wp_error($getEntry) || !FrontendHelpers::is_current_user_can_access($formID, 'entryViewAccess', '', $getEntry->__user_id)) {
                $messageMarkup = __('You do not have permission to view this entry', 'bit-form');
                $messageMarkup = apply_filters('bitform_filter_data_view_error_message', $messageMarkup, 'single_view_capability_message');
                echo $messageMarkup;
                return;
            }
            $parsingString = FieldValueHandler::replaceFieldWithValue($body, (array) $getEntry);
            $webUrl = $this->uploadUrl . DIRECTORY_SEPARATOR . $formID . DIRECTORY_SEPARATOR . $entryId . DIRECTORY_SEPARATOR;
            $finalHTMLCode = FieldValueHandler::changeImagePathInHTMLString($parsingString, $webUrl);
            // $finalHTMLCode = esc_html($finalHTMLCode);
            echo $finalHTMLCode;
        } else {
            echo __('Bit Form Single Entry view is not availabe', 'bit-form');
        }
    }

    /**
     * Gat a form with form id
     * @param int $formId
     * @return array
     */
    private function getFormFields($formId)
    {
        $adminFormHandler = new AdminFormHandler();
        $post = new \stdClass();
        $post = (object) [
            'id' => $formId
        ];
        $getForm = $adminFormHandler->getAForm('', $post);
        $formContainer = $getForm['form_content'];

        return $formContainer['fields'];
    }


    private function frontendStript($viewId)
    {
        $getUserData = get_userdata(get_current_user_id());
        $tableInfo = Table::gatATable(['id' => $viewId], '');
        $roles = isset($getUserData->roles) ? $getUserData->roles : [];
        $bitform_table = [
            'ajaxURL' => admin_url('admin-ajax.php'),
            'assetUrl' => BITFORMS_ASSET_URI,
            'uploadUrl' => $this->uploadUrl,
            'bf_tables' => [],
            'userInfo' => [
                'id' => get_current_user_id(),
                'roles' => $roles
            ],
        ];

        $obj = new \stdClass();
        $obj->id = $tableInfo->form_id;
        $obj->queryCondition = [
            'form_id' => $tableInfo->form_id,
            'user_id' => get_current_user_id(),
        ];
        $adminFormHandler = new AdminFormHandler();
        $getEntries = $adminFormHandler->getFormEntry('', $obj);
        if (is_wp_error($getEntries)) {
            return $getEntries->get_error_message();
        }
        $totalEntries = isset($getEntries['count']) ? $getEntries['count'] : 0;

        // $this->getAForm($tableInfo->form_id);
        $bitform_table['bf_tables']["tableId_{$viewId}"] = [
            'viewId' => $viewId,
            'formId' => (int) $tableInfo->form_id,
            'totalRecords' => $totalEntries,
            'tableConfig' => json_decode($tableInfo->table_config),
            'accessControl' => $this->_formPermission,
            'formFields' => $this->getFormFields($tableInfo->form_id),
        ];

        apply_filters(
            'bitforms_table_localized_script',
            $bitform_table
        );

        $frontArr = json_encode($bitform_table);

        $bfGlobals = <<<BFGLOBALS
      if(!window.bf_view_globals) { 
        window.bf_view_globals = {} 
      } 
      window.bf_view_globals = {...window.bf_view_globals, ...{$frontArr}};
BFGLOBALS;

        self::addInlineScript($bfGlobals, 'bit-form-all-script', 'before');

    }

    public static function addInlineScript($code, $handle = '', $position = 'after')
    {
        $scriptHandle = !empty($handle) ? $handle : 'bf-view-inline-script';
        if (!wp_script_is($scriptHandle)) {
            wp_register_script($scriptHandle, '', [], '', true);
            wp_enqueue_script($scriptHandle);
        }
        wp_add_inline_script($scriptHandle, $code, $position);
    }

    private function isExist($viewId)
    {
        return Table::find($viewId);
    }

    private function getJSFileSrc()
    {
        $formUpdateVersion = get_option('bit-form_form_update_version');
        // $formScriptSrc = BITFORMS_UPLOAD_BASE_URL . "/form-scripts/bitform-view-js-{$viewId}.js?bfv=$formUpdateVersion";
        $formScriptSrc = BITFORMS_ASSET_URI . "/bit-data-view.min.js?bfv=$formUpdateVersion";
        return $formScriptSrc;
    }
}
