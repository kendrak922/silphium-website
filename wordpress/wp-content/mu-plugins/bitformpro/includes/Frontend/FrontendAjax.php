<?php

namespace BitCode\BitFormPro\Frontend;

use BitCode\BitForm\Core\Integration\IntegrationHandler;
use BitCode\BitForm\Core\Util\HttpHelper;
use BitCode\BitFormPro\Admin\BfTable\Table;
use BitCode\BitFormPro\Admin\FormSettings\FormAbandonment;
use BitCode\BitForm\Core\Util\FileHandler;
use BitCode\BitFormPro\API\Controller\MolliePaymentController;
use BitCode\BitForm\Core\Form\FormManager;
use BitCode\BitForm\Core\Database\FormEntryMetaModel;
use BitCode\BitFormPro\Core\Database\PaymentInfoModel;

// use BitCode\BitForm\Core\Util\Log;

final class FrontendAjax
{
    public function register()
    {
        add_action('wp_ajax_bitforms_get_stripe_secret_key', array($this, 'getStripeSecretKey'));
        add_action('wp_ajax_nopriv_bitforms_get_stripe_secret_key', array($this, 'getStripeSecretKey'));
        add_action('wp_ajax_bitforms_mollie_create_payment', array($this, 'createMolliePayment'));
        add_action('wp_ajax_nopriv_bitforms_mollie_create_payment', array($this, 'createMolliePayment'));
        add_action('wp_ajax_bitforms_save_partial_form_progress', array(FormAbandonment::class, 'savePartialFormProgress'));
        add_action('wp_ajax_nopriv_bitforms_save_partial_form_progress', array(FormAbandonment::class, 'savePartialFormProgress'));
        add_action('wp_ajax_bitforms_get_entries', array(Table::class, 'getEntriesByLimit'));
        add_action('wp_ajax_bitforms_file_upload', array($this, 'instandFileUpload'));
        add_action('wp_ajax_nopriv_bitforms_file_upload', array($this, 'instandFileUpload'));
        add_action('wp_ajax_bitforms_file_delete', array($this, 'fileRemove'));
        add_action('wp_ajax_nopriv_bitforms_file_delete', array($this, 'fileRemove'));
        add_action('wp_ajax_bitforms_create_razorpay_order', array($this, 'createRazorpayOrder'));
        add_action('wp_ajax_nopriv_bitforms_create_razorpay_order', array($this, 'createRazorpayOrder'));
        add_action('wp_ajax_bitforms_save_razorpay_details', array($this, 'saveRazorpayDetails'));
        add_action('wp_ajax_nopriv_bitforms_save_razorpay_details', array($this, 'saveRazorpayDetails'));
        add_action('wp_ajax_bitforms_payment_insert', array($this, 'insertPayment'));
        add_action('wp_ajax_nopriv_bitforms_payment_insert', array($this, 'insertPayment'));
    }

    public function getStripeSecretKey()
    {
        $request = file_get_contents('php://input');
        $description = "";
        $shipping = null;

        if ($request) {
            $data = is_string($request) ? \json_decode($request) : $request;
            $payIntegID = $data->payIntegID;
            $amount = $data->amount;
            $currency = $data->currency;
            $meta = $data->metadata;
            $paymentMethodType = $data->payment_method_types;
            $description = isset($data->description) ? $data->description : "";
            $shipping = isset($data->shipping) ? $data->shipping : null;
        }

        $integrationHandler = new IntegrationHandler(0);
        $integrationDetails = $integrationHandler->getAllIntegration('app', 'payments', '', $payIntegID);
        $integrationDetails = json_decode($integrationDetails[0]->integration_details);
        $apiEndPoint = 'https://api.stripe.com/v1/payment_intents';
        $clientSecret = $integrationDetails->clientSecret;

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => "Bearer $clientSecret",
        ];
        $data = [
            'amount' => $amount,
            'currency' => $currency,
            'payment_method_types' => $paymentMethodType,
            'metadata' => [
                "formID" => $meta->formID,
                "entryID" => $meta->entryID,
                "fieldKey" => $meta->fieldKey
            ],
            'description' => $description
        ];
        if (!is_null($shipping)) {
            $data['shipping'] = $shipping;
        }
        if (empty($paymentMethodType)) {
            unset($data['payment_method_types']);
            $data['automatic_payment_methods'] = ['enabled' => 'true'];
        }

        $apiResponse = HttpHelper::post($apiEndPoint, $data, $headers);

        if (isset($apiResponse->error) && $apiResponse->error) {
            wp_send_json_error($apiResponse, 400);
        } else {
            $apiData = (object) [
                'clientSecret' => $apiResponse->client_secret,
            ];
            wp_send_json_success($apiData, 200);
        }
    }
    public function createMolliePayment()
    {
        $request = file_get_contents('php://input');

        (new MolliePaymentController())->createPayment($request);

    }

    public function instandFileUpload()
    {
        $formID = sanitize_text_field($_REQUEST['formID']);
        $user = wp_get_current_user();
        if (wp_verify_nonce(sanitize_text_field($_REQUEST['_ajax_nonce']), "bitforms_$formID") || in_array('administrator', $user->roles) || current_user_can('manage_bitform')) {
            $uploadDirInfo = wp_upload_dir();
            $wpUploadbaseDir = $uploadDirInfo['basedir'];
            $tmpDir = $wpUploadbaseDir . DIRECTORY_SEPARATOR . 'bitforms' . DIRECTORY_SEPARATOR . 'temp';
            if (!is_dir($tmpDir)) {
                mkdir($tmpDir);
            }

            $fieldKey = sanitize_text_field($_REQUEST['fieldKey']);

            $file_details = $_FILES[$fieldKey];

            $fileHandler = new FileHandler();
            $validation = $fileHandler->validation($fieldKey, $file_details, $formID);

            if (!empty($validation['error_type']) && !empty($validation['message'])) {
                wp_send_json_error(
                    __(
                        $validation['message'],
                        'bit-form'
                    ),
                    411
                );
            }

            $fileName = time() . '-' . sanitize_file_name($file_details['name']);
            $src = $file_details['tmp_name'];
            $destination = $tmpDir . DIRECTORY_SEPARATOR . $fileName;

            $uploaded = \move_uploaded_file($src, $destination);
            if ($uploaded) {
                $data = [
                    'file_name' => $fileName,
                    'path' => $destination,
                ];
                wp_send_json_success($data, 200);
            } else {
                $errorMsg = FileHandler::getFileUploadError($file_details['error']);
                wp_send_json_error(
                    __(
                        $errorMsg
                    ),
                    411
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

    public function fileRemove()
    {
        $formID = sanitize_text_field($_REQUEST['formID']);
        $user = wp_get_current_user();
        if (wp_verify_nonce(sanitize_text_field($_REQUEST['_ajax_nonce']), "bitforms_$formID") || in_array('administrator', $user->roles) || current_user_can('manage_bitform')) {
            $fileName = sanitize_file_name($_GET['file_name']);

            if ($fileName === null || $fileName === '') {
                wp_send_json_error(__('File name is required', 'bit-form'), 411);
            }
            if (strpos($fileName, '..') !== false) {
                wp_send_json_error(__('Invalid file name', 'bit-form'), 411);
            }

            $uploadDirInfo = wp_upload_dir();
            $wpUploadbaseDir = $uploadDirInfo['basedir'];

            $tempFile = $wpUploadbaseDir . DIRECTORY_SEPARATOR . 'bitforms' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . $fileName;
            if (file_exists($tempFile)) {
                if (unlink($tempFile) !== true) {
                    wp_send_json_error(__(" Could not delete file because unknown file locat $tempFile"), 411);
                }
                wp_send_json_success(__("File deleted successfully"), 200);
            } else {
                wp_send_json_error(__('File not found'), 411);
            }
        } else {
            wp_send_json_error(__('Token expired'), 401);
        }
    }

    public function createRazorpayOrder()
    {
        $request = file_get_contents('php://input');
        if ($request) {
            $data = is_string($request) ? \json_decode($request) : $request;
            $payIntegID = $data->payIntegID;
            $amount = $data->amount;
            $currency = $data->currency;
            $notes = isset($data->notes) ? $data->notes : (object) [];
        }

        $integrationHandler = new IntegrationHandler(0);
        $integrationDetails = $integrationHandler->getAllIntegration('app', 'payments', '', $payIntegID);
        $integrationDetails = json_decode($integrationDetails[0]->integration_details);
        $apiEndPoint = 'https://api.razorpay.com/v1/orders';
        $keyId = $integrationDetails->apiKey;
        $keySecret = $integrationDetails->apiSecret;

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Basic" . base64_encode("{$keyId}:{$keySecret}"),
        ];
        $data = [
            'amount' => $amount,
            'currency' => $currency,
            'notes' => $notes,
        ];
        $apiResponse = HttpHelper::post($apiEndPoint, json_encode($data), $headers);

        if (isset($apiResponse->error) && $apiResponse->error) {
            wp_send_json_error($apiResponse, 400);
        } else {
            wp_send_json_success($apiResponse, 200);
        }
    }

    public function saveRazorpayDetails()
    {
        $inputJSON = file_get_contents('php://input');
        $queryParams = json_decode($inputJSON);

        if (
            wp_verify_nonce(
                sanitize_text_field($_REQUEST['_ajax_nonce']),
                "bitforms_{$queryParams->formID}"
            )
        ) {
            $formManager = FormManager::getInstance($queryParams->formID);
            $integrationHandler = new IntegrationHandler(0);
            $allFields = $formManager->getFormContent();
            $razorpayField = $allFields->fields->{$queryParams->fieldKey};
            $payIntegId = isset($razorpayField->payIntegID) ? $razorpayField->payIntegID : $razorpayField->options->payIntegID;
            $integration = $integrationHandler->getAIntegration($payIntegId, 'app', 'payments');
            $integration_details = json_decode($integration[0]->integration_details);
            $defaultHeader = [];
            $token = base64_encode("{$integration_details->apiKey}:{$integration_details->apiSecret}");
            $defaultHeader['Authorization'] = "Basic {$token}";

            $trnasientData = get_transient("bitform_trigger_transient_{$queryParams->entry_id}");
            $triggerData = is_string($trnasientData) ? json_decode($trnasientData) : $trnasientData;
            $triggerData['fields'][$queryParams->fieldKey] = $queryParams->transactionID;
            set_transient("bitform_trigger_transient_{$queryParams->entry_id}", $triggerData, HOUR_IN_SECONDS);

            $requestEndpoint = "https://api.razorpay.com/v1/payments/{$queryParams->transactionID}";
            $razorpayResponse = HttpHelper::get($requestEndpoint, null, $defaultHeader);
            $captureRequestEndpoint = "https://api.razorpay.com/v1/payments/{$queryParams->transactionID}/capture?amount={$razorpayResponse->amount}&currency={$razorpayResponse->currency}";
            HttpHelper::post($captureRequestEndpoint, null, $defaultHeader);

            // invoice
            // if (isset($razorpayField->options->invoice) && $razorpayField->options->invoice->generate) {
            //     $data = [
            //         'type' => 'invoice',
            //         'currency' => $razorpayResponse->currency,
            //         'customer' => [
            //             'contact' => $razorpayResponse->contact,
            //             'email' => $razorpayResponse->email,
            //             'name' => ''
            //         ],
            //         'line_items'=> [
            //             (object) [
            //                 'name' => !empty($razorpayField->options->invoice->itemName) ? $razorpayField->options->invoice->itemName : 'Due Amount',
            //                 'description' => !empty($razorpayField->options->invoice->description) ?$razorpayField->options->invoice->description : '',
            //                 'amount' => $razorpayResponse->amount,
            //                 'currency' => $razorpayResponse->currency,
            //                 'quantity' => 1
            //             ]
            //         ]
            //     ];

            //     if (!isset($razorpayField->options->invoice->sendSMS)) {
            //         $data['sms_notify'] = 0;
            //     }
            //     if (!isset($razorpayField->options->invoice->sendEmail)) {
            //         $data['email_notify'] = 0;
            //     }
            //     $invoiceEndpoint = 'https://api.razorpay.com/v1/invoices?' . http_build_query($data);
            //     HttpHelper::post($invoiceEndpoint, null, $defaultHeader);
            // }

            $queryParams->payment_name = 'razorpay';
            $queryParams->payment_response = $razorpayResponse;
            if (isset($queryParams->entry_id)) {
                $this->saveMetaData($queryParams);
            }
            $this->savePaymentLog($queryParams);
        }
    }

    public function insertPayment()
    {
        \ignore_user_abort();
        $inputJSON = file_get_contents('php://input');
        $queryParams = json_decode($inputJSON);
        if (wp_verify_nonce(sanitize_text_field($_REQUEST['_ajax_nonce']), "bitforms_{$queryParams->formID}")) {
            if (isset($queryParams->entry_id)) {
                $this->saveMetaData($queryParams);
            }
            if (isset($queryParams->formID) && isset($queryParams->transactionID)) {
                $trnasientData = get_transient("bitform_trigger_transient_{$queryParams->entry_id}");
                $triggerData = is_string($trnasientData) ? json_decode($trnasientData) : $trnasientData;
                $triggerData['fields'][$queryParams->fieldKey] = $queryParams->transactionID;
                set_transient("bitform_trigger_transient_{$queryParams->entry_id}", $triggerData, HOUR_IN_SECONDS);

                $result = $this->savePaymentLog($queryParams);
                wp_send_json_success($result, 200);
            } else {
                wp_send_json_error('FormId & EntryId is required', 400);
            }
        } else {
            wp_send_json_error(
                __(
                    'Token expired',
                    'bitformpro'
                ),
                401
            );
        }
    }

    private function saveMetaData($data)
    {
        $metaModel = new FormEntryMetaModel();
        $entyData = [
            'bitforms_form_entry_id' => $data->entry_id,
            'meta_key' => $data->fieldKey,
            'meta_value' => $data->transactionID,
        ];
        $isExist = $metaModel->isEntryMetaExist($entyData);
        if (!$isExist) {
            $metaModel->insert($entyData);
        }
    }

    private function savePaymentLog($data)
    {

        $formID = $data->formID;
        $transactionID = $data->transactionID;
        $paymentType = $data->payment_type;
        $paymentName = $data->payment_name;
        $response = wp_json_encode($data->payment_response);
        $paymentModel = new PaymentInfoModel();
        return $paymentModel->paymentInsert($formID, $transactionID, $paymentName, $paymentType, $response);
    }
}
