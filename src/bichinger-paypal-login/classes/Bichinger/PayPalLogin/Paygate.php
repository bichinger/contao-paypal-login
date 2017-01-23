<?php


namespace Bichinger\PayPalLogin;


use Bichinger\Helper\Url;
use Bichinger\Helper\UrlHelper;
use Bichinger\PayPalLogin\Exception\MemberForPaymentNotFoundException;
use Bichinger\UniqueId;
use PayPal\Api\Payment;

/**
 * Class Paygate
 * @package Bichinger\PayPalLogin
 */
class Paygate
{

    /**
     * Remember payment_id for member to find him after payment process
     *
     * @param $payment_id
     * @param $member_id
     */
    public static function updateMemberPaymentId($payment_id, $member_id)
    {
        $res = \Database::getInstance()->prepare('UPDATE tl_member set paypal_payment_id = ? WHERE id = ?')->execute($payment_id, $member_id);
    }

    /**
     * Activate/Approve Member after successful payment
     *
     * @param Payment $payment
     */
    public static function approveMember(Payment $payment)
    {
        // get paypal settings
        $settings = PayPalSettings::getInstance();

        // search for member by payment_id remembered before
        $member = \Database::getInstance()->prepare('SELECT id, groups FROM tl_member WHERE paypal_payment_id = ?')->execute($payment->getId())->fetchAssoc();
        if (empty($member)) {
            throw new MemberForPaymentNotFoundException($payment);
        }

        // if member has no groups assignd, init empty
        if ($member['groups'] == null) {
            $member['groups'] = array();
        } else {
            $member['groups'] = deserialize($member['groups']);
        }

        // assign member to paypal-approved-group
        $member['groups'][] = $settings->getMemberGroup();
        $member['groups'] = serialize($member['groups']);
        // update member
        $res = \Database::getInstance()->prepare('UPDATE tl_member set groups = ? WHERE id = ?')->execute($member['groups'], $member['id']);

        // redirect member to configured page
        $redirectAfterApprovalUrl = UrlHelper::getUrlByPageId($settings->getRedirectAfterApproval());
        header('Location: ' . $redirectAfterApprovalUrl);
        exit();
    }

    /**
     * Redirect member after registration to paypal
     *
     * @param integer $member_id
     * @param array $member_data
     */
    public function redirectMemberToPayPal($member_id, $member_data, $module)
    {
        // get paypal settings
        $settings = PayPalSettings::getInstance();
        // do redirect
        PayPalRequest::redirectMemeberToPayPal($member_id, $settings);
        exit();
    }


}