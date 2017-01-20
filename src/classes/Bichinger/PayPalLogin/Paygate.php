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
     * @param $payment_id
     * @param $member_id
     */
    public static function updateMemberPaymentId($payment_id, $member_id)
    {
        $res = \Database::getInstance()->prepare('UPDATE tl_member set paypal_payment_id = ? WHERE id = ?')->execute($payment_id, $member_id);
    }

    /**
     * @param $payment
     */
    public static function approveMember(Payment $payment)
    {
        $settings = PayPalSettings::getInstance();
        $member = \Database::getInstance()->prepare('SELECT id, groups FROM tl_member WHERE paypal_payment_id = ?')->execute($payment->getId())->fetchAssoc();
        if (empty($member)) {
            throw new MemberForPaymentNotFoundException($payment);
        }

        if ($member['groups'] == null) {
            $member['groups'] = array();
        } else {
            $member['groups'] = deserialize($member['groups']);
        }

        $member['groups'][] = $settings->getMemberGroup();

        $member['groups'] = serialize($member['groups']);

        $res = \Database::getInstance()->prepare('UPDATE tl_member set groups = ?, disable = "", activation = "" WHERE id = ?')->execute($member['groups'], $member['id']);

        $redirectAfterApprovalUrl = UrlHelper::getUrlByPageId($settings->getRedirectAfterApproval());
        header('Location: ' . $redirectAfterApprovalUrl);
        exit();
    }

    /**
     * @param $intId
     * @param $arrData
     */
    public function redirectMemberToPayPal($intId, $arrData)
    {
        $settings = PayPalSettings::getInstance();
        PayPalRequest::redirectMemeberToPayPal($intId, $settings);
        exit();
    }


}