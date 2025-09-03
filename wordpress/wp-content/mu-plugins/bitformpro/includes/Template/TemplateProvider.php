<?php

namespace BitCode\BitFormPro\Template;

use Exception;
use WP_Error;

class TemplateProvider
{
    public function getTemplate()
    {
        if (wp_verify_nonce(sanitize_text_field($_REQUEST['_ajax_nonce']), 'bitforms_save')) {
            $inputJSON = file_get_contents('php://input');
            $requestsParams = json_decode($inputJSON);
            $templateSlug = $requestsParams->templateSlug;
            if (empty($templateSlug)) {
                wp_send_json_error(
                    __(
                        'Template name is required',
                        'bitformpro'
                    ),
                    400
                );
            }

            $templateSlug = sanitize_text_field($templateSlug);

            if (preg_match('/[\/\\\\]|^\.$|^\.\.$/', $templateSlug)) {
                wp_send_json_error(
                    __(
                        'Invalid template name',
                        'bitformpro'
                    ),
                    400
                );
            }

            $templateName = $this->getTemplateName($templateSlug);

            if (is_wp_error($templateName)) {
                wp_send_json_error(
                    __(
                        $templateName->get_error_message(),
                        'bitformpro'
                    ),
                    400
                );
            }

            $templateJson = $this->getTemplateJson($templateName);

            if (is_wp_error($templateJson)) {
                wp_send_json_error(
                    __(
                        $templateJson->get_error_message(),
                        'bitformpro'
                    ),
                    400
                );
            }
            wp_send_json_success($templateJson, 200);

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


    private function getTemplateName($slug)
    {
        $templateList = [
            'pro_blank_slug' => 'pro_blank_slug',
            'default-2' => 'default-2',
            'bitform_test_confirmation_conditions' => 'form_with_condition',
            'multi_step_form' => 'multi-step-form',
            'buy_me_a_coffee_form' => 'buy_me_a_coffee_form',
            'buy_me_a_coffee_form_atlassian' => 'buy_me_a_coffee_form',
            'wp_user_reg_form' => 'wp_user_reg_form',
            'wp_user_reg_atlassian_form' => 'wp_user_reg_form',
            'job_application_form' => 'job_application_form',
            'job_application_form_atlassian' => 'job_application_form',
        ];
        if (array_key_exists($slug, $templateList)) {
            return $templateList[$slug];
        }
        return new WP_Error('failed', __('Template not found', 'bitformpro'));
    }



    private function getTemplateJson($templateName)
    {
        try {
            $templatePath = BITFORMPRO_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $templateName . '.json';
            if (file_exists($templatePath)) {
                return file_get_contents($templatePath);
            }
        } catch (Exception $e) {
            return new WP_Error('failed', __($e->getMessage(), 'bitformpro'));
        }
    }

}
