<?php

namespace Services\PaymentGateway;

use Log;


class Mollie
{

    CONST GATEWAY_NAME = 'Mollie';

    private $transaction_data;

    private $gateway;

	private $extra_params = ['paymentMethod', 'transactionReference'];

    public function __construct($gateway)
    {
        $this->gateway = $gateway;
	$this->gateway->setApiKey('test_cezE5seq2eexSTgRbNhhRJgdpEMMVw');
	//$this->gateway->setApiKey('live_4wC72V5eJM9PjrubrcpwPNrtGRfCkE');
        $this->options = [];
    }
 
    private function createTransactionData($order_total, $order_email, $event)
    {
        $returnUrl = route('showEventCheckoutPaymentReturn', [
            'event_id' => $event->id
        ]);

        $this->transaction_data = [
            'amount' => $order_total,
            'currency' => $event->currency->code,
            'description' => 'Plankenkoortsfestival: ' . $order_email,
			      "returnUrl" => $returnUrl
        ];

        return $this->transaction_data;
    }

    public function startTransaction($order_total, $order_email, $event)
    {

        $this->createTransactionData($order_total, $order_email, $event);

        $transaction = $this->gateway->purchase($this->transaction_data);
        $response = $transaction->send();

        return $response;
    }

    public function getTransactionData() {
        return $this->transaction_data;
    }

    public function extractRequestParameters($request)
    {
        foreach ($this->extra_params as $param) {
            if (!empty($request->get($param))) {
                $this->options[$param] = $request->get($param);
            }
        }
    }

    public function completeTransaction($data)
    {

        if (array_key_exists('transactionReference', $data)) {
            $intentData = [
                'transactionReference' => $data['transactionReference'],
            ];
        } else {
            $intentData = [
                'transactionReference' => $this->options['transactionReference'],
            ];
        }
        $paymentIntent = $this->gateway->FetchTransaction($intentData);
   

        $response = $paymentIntent->send();
        return $response;
    }

    public function getAdditionalData($response)
    {
        $additionalData['transactionReference'] = $response->getTransactionReference();
        return $additionalData;
    }

    public function storeAdditionalData()
    {
        return true;
    }


    public function refundTransaction($order, $refund_amount, $refund_application_fee) {

        $request = $this->gateway->refund([
            'transactionReference' => $order->transaction_id,
            'amount'               => $refund_amount,
            'refundApplicationFee' => $refund_application_fee
        ]);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $refundResponse['successful'] = true;
        } else {
            $refundResponse['successful'] = false;
            $refundResponse['error_message'] = $response->getMessage();
        }

        return $refundResponse;
    }

}
