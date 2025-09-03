<?php

namespace BitCode\BitFormPro\Frontend;

use BitCode\BitFormPro\Core\Util\Utility;
use BitCode\BitForm\Core\Integration\IntegrationHandler;
use BitCode\BitFormPro\Auth\Registration;
use BitCode\BitForm\Core\Util\Log;

class UserActivation
{
    protected $formId;

    protected $userId;

    protected $token;

    public function __construct($token, $formId, $userId)
    {

        $this->token = $token;
        $this->userId = $userId;
        $this->formId = $formId;
    }

    protected function getActivationDetails()
    {
        $exist = (new IntegrationHandler($this->formId))->getAllIntegration('wp_user_auth', 'wp_auth', 1);

        if (is_wp_error($exist)) {
            Log::debug_log('Error: Failed to retrieve integration details for formId ' . $this->formId);
            wp_redirect(home_url());
            exit();
        }

        $code = '';
        if (metadata_exists('user', $this->userId, 'bf_activation_code')) {
            $code = get_user_meta($this->userId, 'bf_activation_code', true);
        }

        $intDetails = json_decode($exist[0]->integration_details);
        return [$code, $intDetails];
    }

    public function emailVerified()
    {
        list($code, $intDetails) = $this->getActivationDetails();

        $activation = (bool) get_user_meta($this->userId, 'bf_activation', true);

        if ($code === $this->token) {
            update_user_meta($this->userId, 'bf_activation', 1);
            delete_user_meta($this->userId, 'bf_activation_code');
            Registration::notification($intDetails, $this->userId);
            $this->redirect((object) $intDetails, 1);
        } elseif (empty($code) && $activation === true) {
            $this->redirect((object) $intDetails, 2);
        } elseif ($activation === false || $code !== $this->token) {
            $this->redirect((object) $intDetails, 0);
        }
    }

    public function userApproveByAdmin()
    {
        list($code, $intDetails) = $this->getActivationDetails();

        $activation = (bool) get_user_meta($this->userId, 'bf_activation', true);
        if ($code === $this->token) {
            update_user_meta($this->userId, 'bf_activation', 1);
            delete_user_meta($this->userId, 'bf_activation_code');
            Registration::notification($intDetails, $this->userId);
            $this->redirect((object) $intDetails, 1);
        } elseif (empty($code) && $activation === true) {
            $this->redirect((object) $intDetails, 2);
        } elseif ($activation === false || $code !== $this->token) {
            $this->redirect((object) $intDetails, 0);
        }
    }

    // User Reject by Admin (New function)
    public function userRejectByAdmin()
    {
        list($code, $intDetails) = $this->getActivationDetails();

        if ($code === $this->token) {
            delete_user_meta($this->userId, 'bf_activation_code');
            // Mark the user as rejected (deactivate the user)
            update_user_meta($this->userId, 'bf_activation', 0);

            // Send a rejection notification to the user
            Registration::sendRejectionEmailToUser($intDetails, $this->userId);
            $this->redirect((object) $intDetails, 3);
        } else {
            // Redirect Invalied Key URL
            $this->redirect((object) $intDetails, 0);
        }

    }

    public function redirect($config, $index)
    {
        $redirectPages = [
            (isset($config->invalid_key_url)) ? $config->invalid_key_url : '',
            (isset($config->succ_url)) ? $config->succ_url : '',
            (isset($config->already_activated_url)) ? $config->already_activated_url : '',
            (isset($config->reject_success_url)) ? $config->reject_success_url : '',
        ];

        $customMessages = [
            (isset($config->invalid_key_msg)) ? $config->invalid_key_msg : __('Sorry! Your URL Is Invalid!'),
            (isset($config->acti_succ_msg)) ? $config->acti_succ_msg : __('Your account has been activated successfully, You can now login.'),
            (isset($config->already_activated_msg)) ? $config->already_activated_msg : __('Your account is already activated!'),
            (isset($config->reject_success_msg)) ? $config->reject_success_msg : __('Requested account has been rejected successfully.'),
        ];

        if (!empty($redirectPages[$index]) || !empty($customMessages[$index])) {
            if (isset($config->custom_redirect) && (string) $config->custom_redirect === '1') {
                wp_redirect($redirectPages[$index]);
                exit();
            } else {
                Utility::view('views/confirmation/email_confirmation', ['data' => [
                    'title'   => 'Account Activation',
                    'message' => $customMessages[$index],
                ]]);
            }
        } else {
            // Fallback URL or error message
            wp_redirect(home_url());
            exit();
        }
    }
}
