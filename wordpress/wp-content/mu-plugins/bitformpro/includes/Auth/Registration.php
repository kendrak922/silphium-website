<?php

/**
 * User Registratoion
 *
 */

namespace BitCode\BitFormPro\Auth;

use BitCode\BitForm\Core\Util\MailConfig;
use BitCode\BitForm\Core\Util\FieldValueHandler;
use BitCode\BitForm\Core\Util\Log;

/**
 * Provide functionality for USER Registration
 */
class Registration
{
    private $_formID;

    private $_wpdb;

    private $_mailConfig;

    public function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_mailConfig = new MailConfig();
        add_action('set_logged_in_cookie', [$this, 'updateSessionCookie'], 10, 1);
    }


    private function sendHtmlMail($to, $subject, $body)
    {
        add_filter('wp_mail_content_type', [$this, 'filterMailContentType']);
        $this->_mailConfig->sendMail();
        if (!wp_mail($to, $subject, $body)) {
            Log::debug_log('Error: Failed to send email to ' . $to);
        }
        remove_filter('wp_mail_content_type', [$this, 'filterMailContentType']);
    }

    private function parseTemplateString($template, $replacements)
    {
        foreach ($replacements as $key => $value) {
            $template = str_replace($key, $value, $template);
        }
        return $template;
    }

    // Helper function to prepare email body with replacements
    private function prepareMailBody($templateBody, $replacements, $fieldValues, $formID)
    {
        // Replace placeholders with actual values
        $body = $this->parseTemplateString($templateBody, $replacements);
        if (class_exists('BitCode\BitForm\Core\Util\FieldValueHandler')) {
            $body = FieldValueHandler::replaceFieldWithValue($body, $fieldValues, $formID);
        }
        return $body;
    }

    private function generateSecureKey()
    {
        return bin2hex(random_bytes(16)); // Replaces uniqid()
    }

    // Helper function to prepare approval and rejection URLs for admin email
    private function prepareApprovalUrls($intDetail)
    {
        $approveUrl = add_query_arg(
            array(
                'bf_user_approve_key' => $intDetail->key,
                'bf_f_id' => $intDetail->form_id,
                'bf_user_id' => $intDetail->user_id,
            ),
            home_url('/')
        );

        $rejectUrl = add_query_arg(
            array(
                'bf_user_reject_key' => $intDetail->key,
                'bf_f_id' => $intDetail->form_id,
                'bf_user_id' => $intDetail->user_id,
            ),
            home_url('/')
        );

        return [
            'approveUrl' => $approveUrl,
            'rejectUrl' => $rejectUrl
        ];
    }

    /**
     * Helps to register ajax function's with wp
     *
     * @return null
     */
    private function userFieldMapping($user_map, $fieldValues)
    {
        $fieldData = [];
        foreach ($user_map as $fieldPair) {
            if (!empty($fieldPair->userField) && !empty($fieldPair->formField)) {
                if ($fieldPair->formField === 'custom' && isset($fieldPair->customValue)) {
                    $fieldData[$fieldPair->userField] = $fieldPair->customValue;
                } else {
                    $fieldData[$fieldPair->userField] = sanitize_text_field($fieldValues[$fieldPair->formField]);
                }
            }
        }

        if (!empty($fieldData['user_email'])) {
            if (isset($fieldData['user_login']) && empty($fieldData['user_login']) || !isset($fieldData['user_login'])) {
                $fieldData['user_login'] = $fieldData['user_email'];
            }
        }
        if (isset($fieldData['user_pass']) && empty($fieldData['user_pass']) || !isset($fieldData['user_pass'])) {
            $fieldData['user_pass'] = wp_generate_password(12, true);
        }
        return $fieldData;
    }

    public function filterMailContentType()
    {
        return 'text/html; charset=UTF-8';
    }

    public function mailSend($intDetail, $fieldValues, $formID)
    {

        $user = get_user_by("ID", $intDetail->user_id);
        $mailSubject = $intDetail->sub;
        $mailBody = $intDetail->body;
        $userLogin = $user->data->user_login;

        $activationUrl = add_query_arg(
            array(
                'bf_activation_key' => $intDetail->key,
                'bf_f_id' => $intDetail->form_id,
                'bf_user_id' => $intDetail->user_id,
            ),
            home_url('/')
        );

        // Prepare replacements for the email body
        $replacements = [
            '{customer_name}' => $userLogin,
            '{email}' => $user->data->user_email,
            '{activation_url}' => $activationUrl
        ];

        // Prepare the email body
        $mailBody = $this->prepareMailBody($mailBody, $replacements, $fieldValues, $formID);


        // Send the email to the user
        $this->sendHtmlMail($user->data->user_email, $mailSubject, $mailBody);
    }

    private function insertUserMeta($user_map, $fieldValues, $userId)
    {
        $mappingField = [];

        foreach ($user_map as $fieldKey => $fieldPair) {
            if (property_exists($fieldPair, "metaField")) {
                $mappingField[$fieldKey]['name'] = $fieldPair->metaField;
                if (!empty($fieldPair->metaField) && !empty($fieldPair->formField)) {
                    if ($fieldPair->formField === 'custom' && isset($fieldPair->customValue)) {
                        $mappingField[$fieldKey]['value'] = $fieldPair->customValue;
                    } else {
                        $mappingField[$fieldKey]['value'] = $fieldValues[$fieldPair->formField];
                    }
                }
            }
        }

        foreach ($mappingField as $userMeta) {
            if (isset($userMeta['name']) && isset($userMeta['value'])) {
                add_user_meta($userId, $userMeta['name'], $userMeta['value'], true);
            }
        }
    }

    public static function notification($intDetails, $userId)
    {

        (new MailConfig())->sendMail();
        if (isset($intDetails->user_notify)) {
            wp_send_new_user_notifications($userId, 'user');
        }

        if (isset($intDetails->admin_notify)) {
            wp_send_new_user_notifications($userId, 'admin');
        }
    }

    public function register($integrationDetails, $fieldValues, $formId)
    {
        $response = [];

        if (is_user_logged_in()) {
            $response['success'] = false;
            $response['message'] = "You are already logged in.";
            return $response;
        }

        $intDetails = is_string($integrationDetails) ? json_decode($integrationDetails) : $integrationDetails;
        $userData = $this->userFieldMapping($intDetails->user_map, $fieldValues);
        $userData['role'] = isset($intDetails->user_role) ? $intDetails->user_role : '';
        $userId = wp_insert_user($userData);

        if (is_wp_error($userId) || !$userId) {
            $response['message'] = is_wp_error($userId) ? $userId->get_error_message() : 'error';
            $response['success'] = false;
        } else {
            $response['message'] = !empty($intDetails->succ_msg) ? $intDetails->succ_msg : '';
            $response['success'] = true;
            $response['auth_type'] = 'register';
            $response['redirectPage'] = !empty($intDetails->redirect_url) ? $intDetails->redirect_url : '';
        }

        $this->insertUserMeta($intDetails->meta_map, $fieldValues, $userId);

        if (isset($intDetails->activation)) {
            $key = $this->generateSecureKey();
            $intDetails->user_id = $userId;
            $intDetails->key = $key;
            $intDetails->form_id = $formId;
            if ($intDetails->activation == "admin_review") {
                add_user_meta($userId, 'bf_activation_code', $key, true);
                add_user_meta($userId, 'bf_activation', 0);
                $this->sendPendingUserEmail($intDetails, $fieldValues, $formId);
                $this->sendAdminApprovalEmail($intDetails, $fieldValues, $formId);
            } elseif ($intDetails->activation == "email_verify") {
                add_user_meta($userId, 'bf_activation_code', $key, true);
                add_user_meta($userId, 'bf_activation', 0);
                $this->mailSend($intDetails, $fieldValues, $formId);
            } else {
                add_user_meta($userId, 'bf_activation', 1);

                if (isset($intDetails->auto_login)) {
                    wp_set_current_user($userId);
                    wp_set_auth_cookie($userId);
                }
            }
        } else {
            add_user_meta($userId, 'bf_activation', 1);
        }

        $activation = (bool) get_user_meta($userId, 'bf_activation', true);
        if ($activation) {
            $this->notification($intDetails, $userId);
        }

        return $response;
    }

    private function sendPendingUserEmail($intDetail, $fieldValues, $formID)
    {
        if (empty($intDetail->pendingUserSub) || empty($intDetail->pendingUserBody)) {
            Log::debug_log('Warning: Pending user email subject or body is empty.');
            return;
        }
        $user = get_userdata($intDetail->user_id);
        $replacements = [
            '{customer_name}' => $user->user_login,
            '{username}' => $user->user_login,
            '{email}' => $user->user_email,
        ];

        $mailBody = $this->prepareMailBody($intDetail->pendingUserBody, $replacements, $fieldValues, $formID);

        $this->sendHtmlMail($user->user_email, $intDetail->pendingUserSub, $mailBody);
    }

    private function sendAdminApprovalEmail($intDetail, $fieldValues, $formID)
    {
        if (empty($intDetail->adminApprovalSub) || empty($intDetail->adminApprovalBody)) {
            Log::debug_log('Warning: Admin approval email subject or body is empty.');
            return;
        }
        $admin_email = get_option('admin_email');
        $user = get_userdata($intDetail->user_id);

        // Prepare approval/rejection URLs
        $urls = $this->prepareApprovalUrls($intDetail);
        $approveUrl = $urls['approveUrl'];
        $rejectUrl = $urls['rejectUrl'];

        $replacements = [
            '{customer_name}' => $user->user_login,
            '{username}' => $user->user_login,
            '{email}' => $user->user_email,
            '{activation_url}' => $approveUrl,
            '{reject_url}' => $rejectUrl,
        ];
        $mailBody = $this->prepareMailBody($intDetail->adminApprovalBody, $replacements, $fieldValues, $formID);

        $this->sendHtmlMail($admin_email, $intDetail->adminApprovalSub, $mailBody);
    }

    public static function sendRejectionEmailToUser($intDetail, $user_id)
    {
        if (empty($intDetail->userRejectionSub) || empty($intDetail->userRejectionBody)) {
            Log::debug_log('Warning: Rejection email subject or body is empty.');
            return;
        }
        $user = get_userdata($user_id);
        $userEmail = $user->user_email;
        $userLogin = $user->user_login;
        $mailBody = $intDetail->userRejectionBody;
        // Prepare the replacements for the email
        $replacements = [
            '{customer_name}' => $userLogin,
            '{username}' => $userLogin,
            '{email}' => $userEmail,
        ];

        $thisInstance = new self();
        // Prepare the email body using the helper function
        $mailBody = $thisInstance->prepareMailBody($intDetail->userRejectionBody, $replacements, [], 0);

        // Send the rejection email to the user
        $thisInstance->sendHtmlMail($userEmail, $intDetail->userRejectionSub, $mailBody);
    }

    public function updateSessionCookie($logged_in_cookie)
    {
        $_COOKIE[LOGGED_IN_COOKIE] = $logged_in_cookie;
    }

}
