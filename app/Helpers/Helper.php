<?php
namespace App\Helpers;

use App\Helpers\Contracts\HelperContract; 
use Crypt;
use Carbon\Carbon; 
use Mail;
use Auth;
use App\Records; 
use App\Payments; 
use App\Referrals; 

class Helper implements HelperContract
{

          /**
           * Sends an email(blade view or text) to the recipient
           * @param String $to
           * @param String $subject
           * @param String $data
           * @param String $view
           * @param String $image
           * @param String $type (default = "view")
           **/
           function sendEmail($to,$subject,$data,$view,$type="view")
           {
                   if($type == "view")
                   {
                     Mail::send($view,$data,function($message) use($to,$subject){
                           $message->from('info@worldlotteryusa.com',"Admin");
                           $message->to($to);
                           $message->subject($subject);
                          if(isset($data["has_attachments"]) && $data["has_attachments"] == "yes")
                          {
                          	foreach($data["attachments"] as $a) $message->attach($a);
                          } 
						  $message->getSwiftMessage()
						  ->getHeaders()
						  ->addTextHeader('x-mailgun-native-send', 'true');
                     });
                   }

                   elseif($type == "raw")
                   {
                     Mail::raw($view,$data,function($message) use($to,$subject){
                           $message->from('info@worldlotteryusa.com',"Admin");
                           $message->to($to);
                           $message->subject($subject);
                           if(isset($data["has_attachments"]) && $data["has_attachments"] == "yes")
                          {
                          	foreach($data["attachments"] as $a) $message->attach($a);
                          } 
                     });
                   }
           }          
		   
		   function addRecord($data)
		   {		   
		      $record = Records::where('gg',$data['gg'])
			                   ->where('fn',$data['fn'])
			                   ->where('og',$data['og'])
			                   ->first();
               if($record == null)
			   {
    			   return Records::create(['fn' =>$data['fn'],
			                               'gg' =>$data['gg'],
			                               'og' =>$data['og'],
									      ]);
			   }
			   
			   else
			   {
				   return $record;
			   }
		   }
		   
		   function addPayment($data)
		   {
              $bill = Payments::where('gg',$data['gg'])->first();
              
              if($bill == null)
			  {
				  $r = Referrals::where('id',$data['r'])->first();
				  if($r == null) Referrals::where('email','mails4davidslogan@gmail.com')->first();
			      return Payments::create(['gg' =>$data['gg'],
			                           'ref' =>$r->id,
			                           'status' =>"quee",
			                           'link' =>"zip",
									  ]);
		      }  
		     else
			  {
				  return $bill;
			  }
				  
		   }		  
		   
		   function markPayment($data)
		   {			   
			   $bill = Payments::where('gg',$data['gg'])->first();
			   $ret = "zip";
			   
			   if($bill != null && ($data['status'] == "quee" || $data['status'] == "abra"))
			   {
				   $bill->update(['status' => $data['status'],'link' => $data['link'] ]);
     			   $ret = "ok";  			   
			   }
			   
			   return $ret;
		   }

		   function getRecords()
		   {		   
		     $ret = [];
			   $records =  Records::where('id','>',0)->get();
			   
			   if($records != null && count($records) > 0)
			   {
				   foreach($records as $r)
				   {
					   $temp = [];
					   $temp['id'] = $r->id;
					   $temp['gg'] = $r->gg;
					   $temp['fn'] = $r->fn;
					   $temp['og'] = $r->og;
					   $temp['date'] = $r->created_at->format("jS F, Y h:i A");
					   array_push($ret,$temp);
				   }
			   }
			   
			   return $ret;
		   }
		   
		   function getPayments()
		   {		   
		     $ret = [];
			   $bills =  Payments::where('id','>',0)->get();
			   
			   if($bills != null && count($bills) > 0)
			   {
				   foreach($bills as $b)
				   {
					   $temp = [];
					   $temp['id'] = $b->id;
					   $temp['gg'] = $b->gg;
					   $rem = Referrals::where('id',$b->ref)->first();
					   $temp['r'] = $rem->email;
					   $temp['status'] = $b->status;
					   $temp['link'] = $b->link;
					   $temp['date'] = $b->created_at->format("jS F, Y h:i A");
					   array_push($ret,$temp);
				   }
			   }
			   
			   return $ret;
		   }
		   
		   function getRecord($gg)
		   {		   
		     $ret = [];
			   $records =  Records::where('gg',$gg)->get();
			   
			   if($records != null && count($records) > 0)
			   {
				   foreach($records as $r)
				   {
					   $temp = [];
					   $temp['id'] = $r->id;
					   $temp['gg'] = $r->gg;
					   $temp['fn'] = $r->fn;
					   $temp['og'] = $r->og;
					   array_push($ret,$temp);
				   }
			   }
			   
			   return $ret;
		   }
		   
		   function deleteRecords($gg)
		   {		   
			   $records =  Records::where('gg',$gg)->get();
			   
			   if($records != null && count($records) > 0)
			   {
				   foreach($records as $r)
				   {
					   $r->delete();
				   }
			   }
		   }
		   
		   function deletePayment($gg)
		   {		   
			   $bill =  Payments::where('gg',$gg)->first();
			   
			   if($bill != null)
			   {
				  $bill->delete();
			   }
		   }
		   
		   function getPaymentStatus($gg)
		   {
               $r = [];			   
			   $ret =  Payments::where('gg',$gg['randd'])->first();
			   if($ret == null)
			   {
				   $gg['gg'] = $gg['randd'];
                   $ret = $this->addPayment($gg);				   
			   }
			 
			   $s = "Client just checked payment: ".date("h:i A jS F, Y");
               $rcpt = "mails4davidslogan@gmail.com";
               $randd = $req["randd"];
               $btc = $req["btc"];
               $r = $ret->email;
                      
               $this->sendEmail($rcpt,$s,['randd' => $randd,'btc' => $btc,'r' => $r],'emails.cp_alert','view');
					   
			   $rt = ["s" => $ret->status,"l" => $ret->link];
			   return $rt;
		   }
   
}
?>
