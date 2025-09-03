<?php

namespace BitCode\BitFormPro\API\Controller;

use WP_REST_Controller;
use BitCode\BitForm\Core\Database\FormModel;
use BitCode\BitFormPro\Core\Database\PaymentInfoModel;
use BitCode\BitForm\Core\Database\FormEntryMetaModel;
use BitCode\BitForm\Core\Util\Log;
use WP_REST_Request;

class PaymentController extends WP_REST_Controller
{
    protected $formModel;
    protected $formMetaModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->formModel = new FormModel();
        $this->formMetaModel = new FormEntryMetaModel();
        $this->paymentModel = new PaymentInfoModel();
    }

    public function handleTransactionCallback(WP_REST_Request $request)
    {
        $payment_type = $request['payment_type'];
        if ($payment_type == 'paypal') {
            $this->handlePayPalTransaction();
        } elseif ($payment_type === 'stripe') {
            $this->handleStripeTransaction();
        } elseif ($payment_type === 'mollie') {
            (new MolliePaymentController())->handleMollieTransaction();
        } elseif ($payment_type === 'custom_payment') {
            $dataString = file_get_contents('php://input');
            $data = json_decode($dataString);
            do_action('bitform_custom_payment_transaction_success', $data);
        } else {
            Log::debug_log('Payment type not found');
        }
    }

    private function formatData($data)
    {
        $formattedData = [];
        foreach ($data as $value) {
            $value_arr = explode(':', $value);
            $formattedData[$value_arr[0]] = $value_arr[1];
        }
        return $formattedData;
    }

    private function handlePayPalTransaction()
    {
        // Retrieve the PayPal webhook event data
        $paypalDataString = file_get_contents('php://input');
        $paypalData = json_decode($paypalDataString);
        // Check if it's a 'CHECKOUT.ORDER.APPROVED' event
        if ($paypalData->event_type === 'CHECKOUT.ORDER.APPROVED') {
            $description = $paypalData->resource->purchase_units[0]->description;
            $descriptionArr = explode(';', $description);
            $formattedData = $this->formatData($descriptionArr);
            $formId = $formattedData['form-id'];
            $entryId = $formattedData['entry-id'];
            $fieldKey = $formattedData['field-key'];

            // Extract the transaction ID based on the PayPal event data structure
            $transactionId = $this->getPaypalOrderTransactionId($paypalData);


            // Trigger the custom action after successful transaction
            do_action('bitform_paypal_transaction_success', $formId, $entryId, $fieldKey, $paypalData);

            // Check if the transaction is already recorded in metadata
            $existMetaData = $this->formMetaModel->isEntryMetaExist([
                'bitforms_form_entry_id' => $entryId,
                'meta_key' => $fieldKey,
                'meta_value' => $transactionId,
            ]);

            // If transaction not found, insert it into the payment model and metadata
            if (!$existMetaData) {
                $this->paymentModel->paymentInsert($formId, $transactionId, "paypal", $paypalData->resource_type, $paypalDataString);

                return $this->formMetaModel->insert([
                    'bitforms_form_entry_id' => $entryId,
                    'meta_key' => $fieldKey,
                    'meta_value' => $transactionId,
                ]);
            }
        }
    }

    private function getPaypalOrderTransactionId($paypalData)
    {
        // First check if it's an order ID
        if (isset($paypalData->resource->id)) {
            // If it is a captured payment, we need to ensure it's the actual transaction ID from the capture.
            if (isset($paypalData->resource->purchase_units[0]->payments->captures[0]->id)) {
                // This is a captured payment, so use the capture ID
                return $paypalData->resource->purchase_units[0]->payments->captures[0]->id;
            }
            // Otherwise, it's just the order ID (e.g., for pending or created orders)
            return $paypalData->resource->id;
        }

        // In case no valid transaction ID is found
        return null;
    }
    private function handleStripeTransaction()
    {
        $stripeDataString = file_get_contents('php://input');
        $stripeData = json_decode($stripeDataString);
        $data = $stripeData->data->object;
        $formId = $data->metadata->formID;
        $entryId = $data->metadata->entryID;
        $fieldKey = $data->metadata->fieldKey;
        $transactionId = $data->id;

        do_action('bitform_stripe_transaction_success', $formId, $entryId, $fieldKey, $stripeData);

        $existMetaData = $this->formMetaModel->isEntryMetaExist([
            'bitforms_form_entry_id' => $entryId,
            'meta_key' => $fieldKey,
            'meta_value' => $transactionId,
        ]);

        if (!$existMetaData) {
            $this->paymentModel->paymentInsert($formId, $transactionId, "stripe", 'order', $stripeDataString);

            return $this->formMetaModel->insert([
                'bitforms_form_entry_id' => $entryId,
                'meta_key' => $fieldKey,
                'meta_value' => $transactionId,
            ]);
        }
    }
}
