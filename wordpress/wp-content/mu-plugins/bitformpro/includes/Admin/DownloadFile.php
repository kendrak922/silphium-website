<?php

namespace BitCode\BitFormPro\Admin;

use BitCode\BitForm\Admin\Form\AdminFormHandler;
use BitCode\BitForm\Admin\Form\Helpers;
use BitCode\BitForm\Core\Messages\PdfTemplateHandler;
use BitCode\BitForm\Core\Util\FieldValueHandler;
use BitCode\BitForm\Core\Util\Log;
use BitCode\BitForm\Core\Util\Utilities;
use BitCode\BitFormPro\Admin\AppSetting\Pdf;
use WP_Error;

class DownloadFile
{
    public function __construct()
    {
    }

    public static function download()
    {
        if (!Utilities::isPro()) {
            wp_send_json_error('This feature not available!', 401);
        }
        $requestUri = $_SERVER['REQUEST_URI'];

        // https://bitform.xyz/bitform-download-file/1__bf__1__bf__14

        if (self::parse_url($requestUri)) {
            $params = self::parse_url($requestUri);

            $formID = Helpers::sanitizeUrlParam($params['formID']);
            $templateID = Helpers::sanitizeUrlParam($params['templateID']);
            $entryID = Helpers::sanitizeUrlParam($params['entryID']);

            if (is_wp_error($formID) || is_wp_error($templateID) || is_wp_error($entryID)) {
                wp_send_json_error('Maybe missing some parameter!', 400);
            }
            self::downloadPDF($formID, $templateID, $entryID);

        } else {
            return new WP_Error('Parameter_error', __('Maybe missing some parameter!', 'bit-form'));
        }
    }

    /**
     * Parse the URL and extract the form ID, entry ID and file ID
     *
     * @param string $url
     * @return array|json
     */
    private static function parse_url($url)
    {
        $url_parse = wp_parse_url($url);
        if (isset($url_parse['query'])) {
            parse_str($url_parse['query'], $query);

            if (isset($query['token'])) {
                $token = Helpers::sanitizeUrlParam($query['token']);

                $decryptEntryId = Helpers::decryptBinaryData($token);

                if (is_wp_error($decryptEntryId)) {
                    wp_send_json_error(__('Decryption Failled!'), 400);
                }

                $valuesArr = explode(BITFORMS_BF_SEPARATOR, $decryptEntryId);
                if (3 === count($valuesArr)) {
                    $arrIndex = ['formID', 'templateID', 'entryID'];
                    $result = array_combine($arrIndex, $valuesArr);
                    return $result;
                }
                wp_send_json_error(__('Maybe your token is wrong!'), 400);
            }
            wp_send_json_error(__('Token dose not exist in URL'), 400);
        }
        wp_send_json_error(__('Query param dose not exist in URL'), 400);
    }

    public static function adminDownloadPDF()
    {
        if (!Utilities::isPro()) {
            wp_send_json_error(__('This feature not available!'), 401);
        }
        $requestUri = $_SERVER['REQUEST_URI'];

        $url_parse = wp_parse_url($requestUri);

        if (isset($url_parse['query'])) {
            parse_str($url_parse['query'], $query);
            if (isset($query['formID'], $query['pdftemp'], $query['entryId'])) {
                $formID = Helpers::sanitizeUrlParam($query['formID']);
                $templateID = Helpers::sanitizeUrlParam($query['pdftemp']);
                $entryID = Helpers::sanitizeUrlParam($query['entryId']);

                if (is_wp_error($formID) || is_wp_error($templateID) || is_wp_error($entryID)) {
                    wp_send_json_error(__('Maybe missing some parameter!'), 400);
                }
                self::downloadPDF($formID, $templateID, $entryID);
            }
            wp_send_json_error(__('Maybe missing some parameter!'), 400);
        } else {
            wp_send_json_error(__('Query param dose not exist in URL'), 400);
        }
    }

    private static function downloadPDF($formID, $templateID, $entryID)
    {
        $pdfTemplateHandler = new PdfTemplateHandler($formID);
        $pdfTemplate = $pdfTemplateHandler->getById($templateID);


        if (is_wp_error($pdfTemplate)) {
            wp_send_json_error($pdfTemplate, 411);
        }

        $pdfSetting = json_decode($pdfTemplate[0]->setting ?? '', true);

        if (is_wp_error($pdfSetting)) {
            wp_send_json_error($pdfSetting, 411);
        }

        if (isset($pdfSetting->allowDownload) && $pdfSetting->allowDownload === 'loggedIn') {
            if (!(current_user_can('manage_options') || current_user_can('manage_bitform') || current_user_can('bitform_entry_edit'))) {
                auth_redirect();
                return;
            }
        }
        $body = $pdfTemplate[0]->body;

        $adminFormHandler = new AdminFormHandler();
        $entry = (array) $adminFormHandler->getSingleEntry($formID, $entryID);

        if (is_wp_error($entry)) {
            wp_send_json_error($entry, 411);
        }

        $serverPath = BITFORMS_UPLOAD_DIR . DIRECTORY_SEPARATOR;
        // TODO: this password only for user, don't need to admin, so, we need to check the user role
        // Remember: before implement this feature, need to discuss with team
        if (isset($pdfSetting->password->static) && $pdfSetting->password->static && !empty($pdfSetting->password->pass)) {
            $pass = FieldValueHandler::replaceFieldWithValue($pdfSetting->password->pass, $entry);
            $pdfSetting->password->pass = $pass;
        }
        // dynamic password are temporary disabled
        // elseif (isset($pdfSetting->password->dynamic)) {
        //   $pass = Helpers::PDFPassHash($entryID);
        //   $pdfSetting->password->pass = $pass;
        // }
        $pdfBody = FieldValueHandler::replaceFieldWithValue($body, $entry, $formID);
        $pdfBody = FieldValueHandler::changeImagePathInHTMLString($pdfBody, $serverPath);
        try {
            Pdf::getInstance()->generator($pdfSetting, $pdfBody, '', $entry['entry_id'], 'I');
        } catch (\Exception $e) {
            Log::debug_log('PDF download error => ' . $e->getMessage());
            wp_send_json_error($e->getMessage(), 411);
        }

        wp_send_json_error(__('Failed to generate or download PDF.'), 411);
    }

    public function generateDownloadPdfLink($formID, $templateID, $entryID)
    {
        $combinedIdentifier = (int) $formID . BITFORMS_BF_SEPARATOR . (int) $templateID . BITFORMS_BF_SEPARATOR . (int) $entryID;
        $token = Helpers::encryptBinaryData($combinedIdentifier);
        return site_url('/bitform-download-file/?token=' . $token);
    }

    public function replacePdfShortCodeToLink($content, $formID, $entryID)
    {
        $pattern = '/\${bf_pdf_download\.(\d+)}/';
        preg_match_all($pattern, $content, $matches);

        if (!empty($matches)) {
            foreach ($matches[1] as $id) {
                $pdfLink = $this->generateDownloadPdfLink($formID, $id, $entryID);
                $content = str_replace('${bf_pdf_download.' . $id . '}', $pdfLink, $content);
            }
        }
        return $content;
    }

    public function replaceShortCodeToPdfPassword($content, $formID, $entryID)
    {
        $pattern = '/\${bf_pdf_password\.(\d+)}/';
        preg_match_all($pattern, $content, $matches);

        if (!empty($matches[1])) {
            try {
                $pdfTemplateHandler = new PdfTemplateHandler($formID);
                $adminFormHandler = new AdminFormHandler();
                $entry = (array) $adminFormHandler->getSingleEntry($formID, $entryID);

                foreach ($matches[1] as $templateID) {
                    $pdfTemplate = $pdfTemplateHandler->getById($templateID);
                    $pdfSetting = json_decode($pdfTemplate[0]->setting);

                    if (isset($pdfSetting->password->static) && $pdfSetting->password->static && !empty($pdfSetting->password->pass)) {
                        $pdfPassword = FieldValueHandler::replaceFieldWithValue($pdfSetting->password->pass, $entry);
                    }
                    // dynamic password are temporary disabled
                    elseif (isset($pdfSetting->password->dynamic)) {
                        $pdfPassword = self::generatePassword($formID, $entryID);
                    }

                    $content = str_replace('${bf_pdf_password.' . $templateID . '}', $pdfPassword, $content);
                }
            } catch (\Exception $e) {
                // wp_send_json_error($e->getMessage(), 411);
                Log::debug_log('PDF password generate error => ' . $e->getMessage());
            }
        }

        return $content;
    }

    private static function generatePassword($formID, $entryID)
    {
        return Helpers::PDFPassHash($entryID);
    }
}