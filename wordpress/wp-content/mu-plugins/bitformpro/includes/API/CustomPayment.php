<?php

namespace BitCode\BitFormPro\API;

use BitCode\BitForm\Core\Database\FormEntryMetaModel;
use BitCode\BitFormPro\Core\Database\PaymentInfoModel;
use WP_Error;

class CustomPayment
{
    private $formMetaModel;

    private $paymentModel;
    public function __construct()
    {
        $this->formMetaModel = new FormEntryMetaModel();
        $this->paymentModel = new PaymentInfoModel();
    }


    /**
     * Summary of savePaymentInfo
     * @param int $formId
     * @param int $entryId
     * @param string $fieldKey
     * @param string $paymentType
     * @param string $transactionId
     * @param string $paymentStringData
     * @return mixed
     */
    public function savePaymentInfo($formId, $entryId, $fieldKey, $paymentType, $transactionId, $paymentStringData)
    {
        $existMetaData = $this->formMetaModel->isEntryMetaExist([
          'bitforms_form_entry_id' => $entryId,
          'meta_key' => $fieldKey,
          'meta_value' => $transactionId,
        ]);

        if ($existMetaData) {
            return new WP_Error('payment_exist!', 'Payment transaction id already exist!', ['status' => 409]);
        }

        $this->formMetaModel->insert([
          'bitforms_form_entry_id' => $entryId,
          'meta_key' => $fieldKey,
          'meta_value' => $transactionId,
        ]);

        return $this->paymentModel->paymentInsert(
            $formId,
            $transactionId,
            $paymentType,
            'order',
            $paymentStringData
        );
    }


}
