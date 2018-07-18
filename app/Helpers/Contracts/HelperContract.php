<?php
namespace App\Helpers\Contracts;

Interface HelperContract
{
        public function sendEmail($to,$subject,$data,$view,$type);
        public function addRecord($data);
        public function addPayment($data);
        public function deleteRecords($gg);
        public function deletePayment($gg);
        public function getRecords();
        public function getPayments();
        public function getRecord($gg);
        public function markPayment($gg,$status);
        public function getPaymentStatus($gg);
}
 ?>