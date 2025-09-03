<?php

namespace BitCode\BitFormPro\Admin\BfTable;

use BitCode\BitForm\Core\Util\Log;
use BitCode\BitFormPro\Core\Database\FrontendViewModel;
use BitCode\BitForm\Admin\Form\Helpers;
use BitCode\BitForm\Admin\Form\AdminFormHandler;
use BitCode\BitForm\Core\Util\FieldValueHandler;
use BitCode\BitForm\Core\Util\IpTool;
use BitCode\BitForm\Core\Util\FileHandler;
use BitCode\BitForm\Core\Util\FrontendHelpers;
use WP_Error;

final class Table
{
    private $_formId;

    private $_tableId;

    private static $table;

    private $tableModel;

    public function __construct($_tableId, $_formId = null)
    {
        $this->_formId = $_formId;
        $this->_tableId = $_tableId;
        $this->tableModel = new FrontendViewModel();
    }

    private static function getTable($condition = [])
    {
        $tableModel = new FrontendViewModel();
        $result = $tableModel->get(
            [
                'id',
                'form_id',
                'table_name',
                'table_config',
                'table_styles',
                'single_entry_view_config',
                'access_control',
                'created_at',
                'updated_at',
            ],
            $condition
        );

        return $result;
    }
    public static function createView()
    {
        if (wp_verify_nonce(sanitize_text_field($_REQUEST['_ajax_nonce']), 'bitforms_save')) {
            $inputJSON = file_get_contents('php://input');
            $requestsParams = json_decode($inputJSON);
            $formId = $requestsParams->form_id;
            $tableId = $requestsParams->id;
            $tableName = $requestsParams->table_name;
            $tableConfig = wp_json_encode($requestsParams->table_config);
            $tableStyles = wp_json_encode($requestsParams->table_styles);
            $singEntryViewConfig = wp_json_encode($requestsParams->single_entry_view_config) ?? '';
            $accessControl = wp_json_encode($requestsParams->access_control) ?? '';

            $user_details = IpTool::getUserDetail();
            $data = [
                'id' => $tableId,
                'form_id' => $formId,
                'table_name' => $tableName,
                'table_config' => $tableConfig,
                'table_styles' => $tableStyles,
                'single_entry_view_config' => $singEntryViewConfig,
                'access_control' => $accessControl,
                'created_at' => $user_details['time'],
                'updated_at' => $user_details['time'],
            ];

            $tableModel = new FrontendViewModel();
            $savedTableId = $tableModel->insert($data);

            $cssString = self::styleGenerator($requestsParams->table_styles->style, true);

            self::saveScript($savedTableId, $cssString);
            $getTable['table'] = self::gatATable(['id' => $savedTableId], null);
            $getTable['messages'] = __('Table Saved successfully', 'bit-form');

            if (!empty($getTable)) {
                wp_send_json_success($getTable, 200);
            } else {
                wp_send_json_error(
                    __(
                        'Nothing found',
                        'bitform'
                    ),
                    401
                );
            }
        } else {
            wp_send_json_error(
                __(
                    'Token expired',
                    'bitform'
                ),
                401
            );
        }
    }
    public static function updateView()
    {
        if (wp_verify_nonce(sanitize_text_field($_REQUEST['_ajax_nonce']), 'bitforms_save')) {
            $inputJSON = file_get_contents('php://input');
            $requestsParams = json_decode($inputJSON);

            $formId = $requestsParams->form_id;
            $tableId = $requestsParams->id;
            $tableName = $requestsParams->table_name;
            $tableConfig = wp_json_encode($requestsParams->table_config);
            $tableStyles = wp_json_encode($requestsParams->table_styles);
            $singleEntryViewConfig = wp_json_encode($requestsParams->single_entry_view_config) ?? '';
            $accessControl = wp_json_encode($requestsParams->access_control) ?? '';

            $user_details = IpTool::getUserDetail();

            $data = [
                'form_id' => $formId,
                'table_name' => $tableName,
                'table_config' => $tableConfig,
                'table_styles' => $tableStyles,
                'single_entry_view_config' => $singleEntryViewConfig,
                'access_control' => $accessControl,
                'updated_at' => $user_details['time'],
            ];
            $tableModel = new FrontendViewModel();
            $savedTableId = $tableModel->update($data, ['id' => $tableId]);

            $cssString = self::styleGenerator($requestsParams->table_styles->style, true);

            self::saveScript($tableId, $cssString);

            if ($savedTableId) {

                $getTable['table'] = self::gatATable(['id' => $tableId], null);
                $getTable['message'] = __('Table Update successfully', 'bit-form');

            } else {
                wp_send_json_error(['message' => 'Error...', 500]);
            }

            if (!empty($getTable)) {
                wp_send_json_success($getTable, 200);
            } else {
                wp_send_json_error(
                    __(
                        'Nothing found',
                        'bitform'
                    ),
                    401
                );
            }
        } else {
            wp_send_json_error(
                __(
                    'Token expired',
                    'bitform'
                ),
                401
            );
        }
    }

    private static function saveScript($tableId, $contain)
    {
        $path = 'form-styles';
        $fileName = 'bitform-view-' . $tableId . '.css';
        Helpers::saveFile($path, $fileName, $contain, 'w');
    }

    private static function deleteScript($tableId)
    {
        $cssPath = BITFORMS_CONTENT_DIR . DIRECTORY_SEPARATOR . 'form-styles' . DIRECTORY_SEPARATOR;
        $fileName = 'bitform-view-' . $tableId . '.css';

        $link = $cssPath . $fileName;

        if (file_exists($link)) {
            unlink($link);
        }

    }

    public static function gatATable($request, $post)
    {
        if (isset($request['id'])) {
            $tableID = wp_unslash($request['id']);
        } else {
            $tableID = wp_unslash($post->id);
        }

        if (is_null($tableID)) {
            return new WP_Error('empty_table', __('Table id is empty.', 'bit-form'));
        }

        $result = self::getTable(['id' => $tableID])[0];

        if (is_wp_error($result)) {
            return new WP_Error('result_empty', __('Table not found.', 'bit-form'));
        }

        return $result;
    }

    public static function getAllViews()
    {
        if (wp_verify_nonce(sanitize_text_field($_REQUEST['_ajax_nonce']), 'bitforms_save')) {
            $inputJSON = file_get_contents('php://input');
            $requestsParams = json_decode($inputJSON);
            $formId = $requestsParams->formID;

            if (!filter_var($formId, FILTER_VALIDATE_INT)) {
                return new WP_Error('invalid_form', __('Form id is invalid.', 'bit-form'));
            }

            $allTable = self::getTable(['form_id' => $formId]);

            if (!is_wp_error($allTable)) {
                wp_send_json_success($allTable, 200);
            } else {
                wp_send_json_error(
                    __(
                        'No Data View found',
                        'bitform'
                    ),
                    401
                );
            }
        } else {
            wp_send_json_error(
                __(
                    'Token expired',
                    'bitform'
                ),
                401
            );
        }
    }

    /**
     * Get last inserted id
     *
     * @return int
     */
    public static function lastId(): int
    {
        $tableModel = new FrontendViewModel();
        return $tableModel->lastId();
    }

    public static function deleteTable()
    {
        if (wp_verify_nonce(sanitize_text_field($_REQUEST['_ajax_nonce']), 'bitforms_save')) {
            $inputJSON = file_get_contents('php://input');
            $requestsParams = json_decode($inputJSON);
            $tableId = $requestsParams->id;

            if (is_null($tableId)) {
                return new WP_Error('empty_form', __('table id is empty.', 'bit-form'));
            }
            $tableModel = new FrontendViewModel();

            $delete_status = $tableModel->delete(
                [
                    'id' => $tableId,
                ]
            );

            if (is_wp_error($delete_status)) {
                wp_send_json_error($delete_status->get_error_message(), 411);
            } else {
                self::deleteScript($tableId);
                wp_send_json_success(__('Table deleted successfully.', 'bit-form'), 200);
            }

        } else {
            wp_send_json_error(
                __(
                    'Token expired',
                    'bitform'
                ),
                401
            );
        }
    }

    public static function deleteBulkTable()
    {
        if (wp_verify_nonce(sanitize_text_field($_REQUEST['_ajax_nonce']), 'bitforms_save')) {
            $inputJSON = file_get_contents('php://input');
            $input = json_decode($inputJSON);
            $tableModel = new FrontendViewModel();
            $tableId = $input->tblId;

            $delete_status = $tableModel->bulkDelete(
                [
                    'id' => $tableId,
                ]
            );

            if (is_wp_error($delete_status)) {
                wp_send_json_error($delete_status->get_error_message(), 411);
            } else {
                wp_send_json_success(__('Table deleted successfully.', 'bit-form'), 200);
            }
        } else {
            wp_send_json_error(
                __(
                    'Token expired',
                    'bit-form'
                ),
                401
            );
        }
    }

    // table find
    public static function find($tableId)
    {

        $tableModel = new FrontendViewModel();

        $table = $tableModel->get(
            ['id'],
            [
                'id' => $tableId,
            ]
        );
        if (!is_wp_error($table)) {
            return true;
        }
        return false;
    }

    private static function styleGenerator($styleObj, $important = false)
    {
        $important = $important ? '!important' : '';
        $classes = array_keys((array) $styleObj);
        $css = '';
        foreach ($classes as $class) {
            $css .= $class;
            $props = (array) $styleObj->{$class};
            $propsKeys = array_keys($props);
            $styleObject = '{';
            foreach ($propsKeys as $property) {
                $value = $props[$property];
                if (empty($value)) {
                    continue;
                }
                $styleObject .= "{$property}:{$value}{$important};";
            }
            $styleObject = rtrim($styleObject, ';');
            $styleObject .= '}';
            $css .= $styleObject;
        }

        return $css;

    }

    public static function getEntriesByLimit()
    {
        $inputJSON = file_get_contents('php://input');
        $requestsParams = json_decode($inputJSON);
        $formId = $requestsParams->formId;
        $tableId = $requestsParams->tableId;
        $offset = $requestsParams->offset;
        $pageSize = $requestsParams->pageSize;
        $search = isset($requestsParams->search) ? $requestsParams->search : null;

        $table = self::gatATable(['id' => $tableId], null);
        $tableConfig = \json_decode($table->table_config);
        $columnsMap = $tableConfig->columnsMap;

        $entryData = [];

        $obj = new \stdClass();
        $obj->id = $formId;
        $obj->offset = $offset;
        $obj->pageSize = $pageSize;
        $canViewOthers = FrontendHelpers::is_current_user_can_access($formId, 'entryViewAccess', 'othersEntries');
        if (!$canViewOthers) {
            $obj->queryCondition = [
                'form_id' => $formId,
                'user_id' => get_current_user_id(),
            ];
        }
        $adminFormHandler = new AdminFormHandler();
        $getEntries = $adminFormHandler->getFormEntry('', $obj);
        $entries = $getEntries['entries'];
        foreach ($entries as $entry) {
            $row = [];
            foreach ($columnsMap as $column) {
                $data = FieldValueHandler::replaceFieldWithValue($column->fk, (array) $entry);
                $row[$column->fk] = $data;
            }
            if (method_exists(FileHandler::class, 'getEntriesFileUploadURL')) {
                $row['resourcePath'] = FileHandler::getEntriesFileUploadURL($formId, $entry->entry_id) . '/';
            }
            $entryData[$entry->entry_id] = $row;

        }
        wp_send_json_success($entryData, 200);

    }

    public static function deleteTableByFormId($formId)
    {
        $tableModel = new FrontendViewModel();

        $getTable = $tableModel->get([
            'id'
        ], [
            'form_id' => $formId,
        ]);

        $delete_status = $tableModel->delete(
            [
                'form_id' => $formId,
            ]
        );

        if (!is_wp_error($delete_status)) {
            foreach ($getTable as $table) {
                self::deleteScript($table->id);
            }
        }
        return $delete_status;
    }

    public static function deleteTableByBulkFormId(array $formIDs)
    {
        $tableModel = new FrontendViewModel();

        foreach ($formIDs as $formId) {

            $getTables = $tableModel->get([
                'id'
            ], [
                'form_id' => $formId,
            ]);

            $delete_status = $tableModel->delete(
                [
                    'form_id' => $formId,
                ]
            );

            if (!is_wp_error($delete_status)) {
                foreach ($getTables as $table) {
                    self::deleteScript($table->id);
                }
            }
            return $delete_status;
        }

    }
}
