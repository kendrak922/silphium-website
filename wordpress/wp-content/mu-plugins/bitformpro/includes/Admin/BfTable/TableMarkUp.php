<?php

namespace BitCode\BitFormPro\Admin\BfTable;

use BitCode\BitForm\Admin\Form\AdminFormHandler;
use BitCode\BitFormPro\Admin\BfTable\Table;
use BitCode\BitForm\Core\Util\FieldValueHandler;
use BitCode\BitFormPro\Admin\BfTable\FrontendViewHandler;
use BitCode\BitForm\Core\Util\FileHandler;
use BitCode\BitForm\Core\Util\FrontendHelpers;

final class TableMarkUp
{
    private $_table;

    private $_tableId;
    private $_tableConfig;
    private $_formID;

    private $_actionsBtn;
    private $_accessControl;

    private $_fromPermission;

    private $_current_user_id;
    private $_current_user_roles;

    private $_uploadUrl = BITFORMS_UPLOAD_BASE_URL . '/' . 'uploads';

    // private $
    public function __construct($tableId)
    {
        $this->_tableId = $tableId;
        $this->_table = Table::gatATable(['id' => $tableId], null);
        $this->_tableConfig = \json_decode($this->_table->table_config);
        $this->_formID = $this->_table->form_id;
        $this->_accessControl = \json_decode($this->_table->access_control);
        $this->getCurrentUserInfo();
    }

    public function getFormId()
    {
        return $this->_formID;
    }

    public function setFormPermission($formPermission)
    {
        $this->_fromPermission = $formPermission;
    }

    private function getFormFields()
    {
        $adminFormHandler = new AdminFormHandler();
        $post = new \stdClass();
        $post = (object) [
            'id' => $this->_formID
        ];
        $getForm = $adminFormHandler->getAForm('', $post);
        $formContainer = $getForm['form_content'];

        return $formContainer['fields'];
    }

    private function getCurrentUserInfo()
    {
        $current_user_id = get_current_user_id();
        $current_user = get_userdata($current_user_id);
        $this->_current_user_roles = isset($current_user->roles) ? $current_user->roles : [];
        $this->_current_user_id = $current_user_id;
    }
    public function tableViewer()
    {
        // if (!$this->_accessControl->allowViewToPublic && !$this->_current_user_id) {
        //     $messageMarkup = sprintf(__('Yor cannot access #%s no. Table View', 'bit-form'), $this->_tableId);
        //     $messageMarkup = apply_filters('bitform_filter_data_view_error_message', $messageMarkup, 'allowViewToPublic');
        //     return $messageMarkup;
        // }

        // if ($this->_current_user_id && (current_user_can('prevent_bitform_data_view') || !(current_user_can('read') || current_user_can('bitform_data_view')))) {
        //     $messageMarkup = sprintf(__('You do not have permission to access #%s no. Table View', 'bit-form'), $this->_tableId);
        //     $messageMarkup = apply_filters('bitform_filter_data_view_error_message', $messageMarkup, 'checkViewCapability');
        //     return $messageMarkup;
        // }

        $tableConfig = $this->_tableConfig;
        $formID = $this->_formID;
        $tableId = $this->_tableId;

        $captionHTML = '';
        $theadHTML = '';
        $theadActionsHTML = '';
        $searchBarHTML = '';
        $paginationHTML = '';

        $columnsMap = $tableConfig->columnsMap;
        $this->_actionsBtn = $tableConfig->actionsBtn;
        $head = $this->_actionsBtn->head;

        $obj = new \stdClass();
        $obj->id = $formID;

        $entryRow = '';
        $canViewOthers = FrontendHelpers::is_current_user_can_access($formID, 'entryViewAccess', 'othersEntries');
        if (!$canViewOthers) {
            $obj->queryCondition = [
                'form_id' => $formID,
                'user_id' => get_current_user_id(),
            ];
        }
        $adminFormHandler = new AdminFormHandler();
        $getEntries = $adminFormHandler->getFormEntry('', $obj);
        if (is_wp_error($getEntries)) {
            return $getEntries->get_error_message();
        }
        $entries = $getEntries['entries'];
        $totalEntries = $getEntries['count'];

        // $search = 'K';
        // $filterArray = array_filter($entries, function ($entry) use ($search) {
        //   foreach ((array) $entry as $key => $value) {
        //     // partially match the search string regex, case insensitive search, return the row
        //     if (preg_match("/{$search}/i", $value)) {
        //       return $entry;
        //     }
        //   }
        // });


        $entryData = [];

        $this->rowMarkup($entries, $columnsMap, $entryRow, $entryData);

        $this->captionMarkup($captionHTML);

        $this->searchBarMarkup($searchBarHTML);

        $this->pagination($totalEntries, $paginationHTML);

        $formEntries = json_encode($entryData);

        $bfGlobals = <<<BFGLOBALS
      if(!window.bf_view_globals) { 
        window.bf_view_globals = {} 
      } 
      window.bf_view_globals = {...window.bf_view_globals, entries: {$formEntries}};

BFGLOBALS;

        FrontendViewHandler::addInlineScript($bfGlobals, 'bit-form-all-script', 'before');
        if (
            isset($head->show)
            && $head->show
            && ($this->isUserAllowedToEdit() || $this->isUserAllowedToView())
        ) {
            $theadActionsHTML = <<<THEAD_ACTIONS
      <th width="{$head->w}">
        {$head->thead}
      </th>
THEAD_ACTIONS;
        }

        foreach ($columnsMap as $column) {
            $theadHTML .= <<<THEAD
      <th width="{$head->w}">
        {$column->thead}
      </th>
THEAD;
        }

        $tableHTML = <<<HTML
      <div class="bf{$formID}-{$tableId}-tbl-wrp bf-tbl-wrp">
        {$captionHTML}
        {$searchBarHTML}
        <table class="bf{$formID}-{$tableId}-tbl bf-tbl">
          <thead class="bf{$formID}-{$tableId}-thead bf-thead">
            <tr>
              {$theadHTML}
              {$theadActionsHTML}
            </tr>
          </thead>
          <tbody class="bf{$formID}-{$tableId}-tbody bf-tbody">
          {$entryRow}
          </tbody>
        </table>
        {$paginationHTML}
      </div>
HTML;

        return $tableHTML;

    }

    private function isUserAllowedToEdit()
    {

        if (!$this->_current_user_id || current_user_can('prevent_bitform_data_edit')) {
            return false;
        }
        if (current_user_can('edit_posts') || current_user_can('bitform_data_edit')) {
            return true;
        }

        return false;
    }

    private function isUserAllowedToView()
    {
        if (isset($this->_accessControl->allowViewToPublic) && $this->_accessControl->allowViewToPublic) {
            return true;
        }

        if ($this->_current_user_id && (current_user_can('prevent_bitform_data_view') || !(current_user_can('read') || current_user_can('bitform_data_view')))) {
            return false;
        }

        return true;
    }

    private function actionHadrShowOrNotForAll()
    {
        $accessControl = $this->_accessControl;
        if (
            $accessControl->accessFor === 'all'
            && in_array($this->_current_user_roles[0], $accessControl->all->entryEdit)
        ) {
            return true;
        }
        return false;
    }

    private function actionHadrShowOrNotForUserIds()
    {
        $accessControl = $this->_accessControl;
        if (
            $accessControl->accessFor === 'user'
            && in_array($this->_current_user_id, $accessControl->user->ids)
        ) {
            return true;
        }
        return false;
    }

    public function isImgFldType($fk)
    {
        $fldType = ['advanced-file-up', 'signature', 'file-up'];

        $fields = $this->getFormFields();

        if (property_exists($fields, $fk) && in_array($fields->$fk->typ, $fldType)) {
            return true;
        }
        return false;
    }

    public function rowMarkup($entries, $columnsMap, &$entryRow, &$entryData)
    {
        $this->isImgFldType('signature');
        foreach ($entries as $entry) {
            $row = [];
            $entryRow .= "<tr>";
            foreach ($columnsMap as $column) {
                $value = FieldValueHandler::replaceFieldWithValue($column->fk, (array) $entry);

                $cleanedFieldKey = preg_replace('/\${([a-zA-Z0-9]+-\d+)}/', '$1', $column->fk);
                if ($this->isImgFldType($cleanedFieldKey)) {
                    $entryRow .= $this->imageMarkup($value, $entry->entry_id);
                } else {
                    $entryRow .= "<td>{$value}</td>";
                }
                $row[$column->fk] = $value;
            }
            $entryRow .= $this->actionButtonMarkup($entry->entry_id);
            $entryData[$entry->entry_id] = $row;
            $entryRow .= "</tr>";
        }
    }

    private function imageMarkup($value, $entry_id)
    {
        $newVal = json_decode($value);

        if (method_exists(FileHandler::class, 'getEntriesFileUploadURL')) {
            $resourceUrl = FileHandler::getEntriesFileUploadURL($this->_formID, $entry_id) . '/';
        } else {
            $resourceUrl = $this->_uploadUrl . '/' . $this->_formID . '/' . $entry_id . '/';
        }

        if (is_array($newVal)) {
            $imageMarkup = '<td>';
            foreach ($newVal as $img) {
                $image = $resourceUrl . $img;
                $imageMarkup .= "<img src='{$image}' alt='{$img}' style='width: 100%' />";
            }
            return $imageMarkup .= '</td>';

        } else {
            $image = $resourceUrl . $newVal;
            return "<td><img src='{$image}' alt='{$newVal}' style='width: 100%' /></td>";
        }
    }

    public function actionButtonMarkup($entryId)
    {
        $actionsBtn = $this->_actionsBtn;
        $accessControl = $this->_accessControl;

        $html = '';
        if (isset($actionsBtn->head->show) && $actionsBtn->head->show && ($this->isUserAllowedToEdit() || $this->isUserAllowedToView())) {
            $html .= "<td><div class='bf{$this->_formID}-{$this->_tableId}-tbl-action-btns bf-tbl-action-btns'>";
            $editButton = $actionsBtn->editButton;
            $viewButton = $actionsBtn->viewButton;

            if (
                $editButton->show &&
                $this->isUserAllowedToEdit()
            ) {
                $html .= <<<EDIT_BTN
                          <a class="bf{$this->_formID}-{$this->_tableId}-tbl-edit-btn bf-tbl-edit-btn" href="{$editButton->slug}?bf_entry_id={$entryId}">
                            {$actionsBtn->editButton->btnTxt}
                          </a>
EDIT_BTN;
            }

            if (
                $viewButton->show &&
                $this->isUserAllowedToView()
            ) {
                $html .= <<<VIEW_BTN
                            <a class="bf{$this->_formID}-{$this->_tableId}-tbl-view-btn bf-tbl-view-btn" href="{$viewButton->slug}?bf_entry_id={$entryId}">
                              {$actionsBtn->viewButton->btnTxt}
                            </a>
VIEW_BTN;
            }
            //       if (
            //         $editButton->show
            //         && $accessControl->accessFor === 'all' &&
            //         in_array($this->_current_user_roles[0], $accessControl->all->entryEdit)
            //       ) {
            //         $html .= <<<EDIT_BTN
            //         <a class="bf{$this->_formID}-{$this->_tableId}-tbl-edit-btn bf-tbl-edit-btn" href="{$editButton->slug}?bf_entry_id={$entryId}">
            //           {$actionsBtn->editButton->btnTxt}
            //         </a>
            // EDIT_BTN;
            //       }
            //             if (
            //                 $viewButton->show
            //                 && $accessControl->accessFor === 'all' &&
            //                 in_array($this->_current_user_roles[0], $accessControl->all->singleEntryDetailsView)
            //             ) {
            //                 $html .= <<<VIEW_BTN
            //         <a class="bf{$this->_formID}-{$this->_tableId}-tbl-view-btn bf-tbl-view-btn" href="{$viewButton->slug}?bf_entry_id={$entryId}">
            //           {$actionsBtn->viewButton->btnTxt}
            //         </a>
            // VIEW_BTN;
            //             }
            $html .= '</div></td>';
        }
        return $html;
    }

    private function captionMarkup(&$captionHTML)
    {
        $tableConfig = $this->_tableConfig;
        if (isset($tableConfig->caption)) {
            $caption = $tableConfig->caption;
            $captionHTML = <<<CAPTION
        <div class="bf{$this->_formID}-{$this->_tableId}-tbl-caption bt-tbl-caption">
         {$caption}
        </div>
CAPTION;
        }

    }

    private function searchBarMarkup(&$searchBarHTML)
    {
        $searchBarHTML = <<<HTML
      <div class="bf{$this->_formID}-{$this->_tableId}-tbl-top-bar bf-tbl-top-bar">
        <label aria-label="search-bar" htmlFor="search-box bf-tbl-serc-lbl">
          Search
          <input
            id="search-box"
            class="bf{$this->_formID}-{$this->_tableId}-serc-bx -bf-tbl-serc-bx"
            type="text"
            name="search"
          />
        </label>
      </div>
HTML;

    }

    private function pagination($totalEntries, &$paginationHTML)
    {
        $formID = $this->_formID;
        $tableId = $this->_tableId;

        $paginationHTML = <<<HTML
      <div class="bf{$formID}-{$tableId}-tbl-footer bf-tbl-footer">
        <span>Total Response {$totalEntries}</span>
        <div class="gn">
          <button class="bf{$formID}-{$tableId}-pgn-btn bf-tbl-pgn-btn" data-page="start" type="button">&#171;</button>
          <button class="bf{$formID}-{$tableId}-pgn-btn bf-tbl-pgn-btn" data-page="previous" type="button">&#8249;</button>
          <button class="bf{$formID}-{$tableId}-pgn-btn bf-tbl-pgn-btn" data-page="next" type="button">&#8250;</button>
          <button class="bf{$formID}-{$tableId}-pgn-btn bf-tbl-pgn-btn" data-page="end" type="button">&#187;</button>
          <span>
            Page 
            <span class="bf{$formID}-{$tableId}-curr-page bf-tbl-curr-page">1</span> of 
            <span class="bf{$formID}-{$tableId}-total-page bf-tbl-total-page">10</span>
          </span>
          <select class="bf{$formID}-{$tableId}-tbl-pgn-slt bf-tbl-pgn-slt" name="limit" id="limit">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
          </select>
        </div>
      </div>
HTML;
    }


}
