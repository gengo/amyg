<?php
  /*
   
	Class myG for textpattern, frog, wordpress, nucleus, joomla!
	
  */
  
	class myG
	{
	  private static $__instance;
	  
	  public static function i() {
		  if (!isset(self::$__instance)) {
			  $c = __CLASS__;
			  self::$__instance = new myG;
		  }
		  return self::$__instance;
	  }
	  
	  private function __clone(){}
  
	  private function __construct()
	  {
		$this->loggedIn = false;
		$this->server = 'http://api.mygengo.com/';
		$this->scriptVersion = '1.0.6';
	  }
	
	  //set the current application ( wordpress, textpattern, frog, etc)
	  function setApplication($application)
	  {
		$this->application = $application;
	  }
	  
	  //display the CSS used by this plugin
	  function css()
	  {
		echo '
		
		  <style>
		  
		  .myGengoJobDetail
		  {
			display:block;
			margin:2px;
			margin-bottom:14px;
			padding-bottom:10px;
			border-bottom:1px solid grey;
			
		  }
		  .myGengoJobDetailTitle
		  {
			font-weigth:bold;
			font-size:15px;
		  }
		  .myGengoJobDetailData
		  {
			font-size:11px;
		  }
		  
		  
			.myGengoJobDetail pre
			{
			  font-family:Arial;
			  font-size:12px;
			  
			  background-color:#EFEFEF;
			  margin:10px;
			  margin-left:20px;
			  margin-right:0px;
			  border-left:4px solid #CFCFCF;
			  padding:10px;
			  color:#2F2F2F;
			}
			
			.myGengoTranslationComment
			{
			  background-color:#DFDFDF;
			  margin:10px;
			  margin-left:20px;
			  margin-right:0px;
			  border-left:4px solid #CFCFCF;
			  padding:10px;
			  color:#2F2F2F;
			}
			
			.clear
			{
			  display:block;
			  clear:both;
			}
			.myGengoActionTitle{
				border-bottom:1px dotted grey;
			}
			.myGengoVerticalAlign
			{
			  vertical-align:middle;
			  font-size:12px;
			}
			.myGengoVerticalAlign img
			{
			  vertical-align:middle;
			}
		  </style>
		';
					  
	  }
	  
	  //display the JavaScript used by this plugin
	  function js()
	  {
		echo '
		
		<script>
		  var myGengoLoadTime = 0;//timeout foreach job to make a request.
		  function myGengoFlip(item)
		  {
			var item = item.getAttribute("name");
			var items = ["mygengo_form_api","mygengo_form_new_job","mygengo_form_jobs"];
			for(var id in items)
			{
				if(items[id] == item)
				{
				  if(
					document.getElementById(items[id]).style &&
					document.getElementById(items[id]).style.display &&
					document.getElementById(items[id]).style.display == "block"
				  )
					document.getElementById(items[id]).style.display = "none";
				  else
					document.getElementById(items[id]).style.display = "block";
				}
				else
				  document.getElementById(items[id]).style.display = "none";
			}
			document.getElementById(item).focus();
		  }
		  function myGengoFlipUtil(id)
		  {
			  if(!document.getElementById(id).style || !document.getElementById(id).style.display || document.getElementById(id).style.display == "block")
				  document.getElementById(id).style.display = "none";
			  else
				  document.getElementById(id).style.display = "block";
		  }
		  function myGengoFlipElement(id)
		  {
			  if(!id.style || !id.style.display || id.style.display == "block")
				  id.style.display = "none";
			  else
				  id.style.display = "block";
		  }
		  function myGengoValidate()
		  {
		   if(
			document.getElementById("mygengo_key_public").value == "" ||
			document.getElementById("mygengo_key_private").value == ""
		   )
		   {
			alert("'.addslashes($this->s('get.valid.api.keys')).'");
			return;
		   }
		   
		   if(
			document.getElementById("mygengo_body").value == "" ||
			document.getElementById("mygengo_title").value == ""
		   )
		   {
			alert("'.addslashes($this->s('please.complete.all.field')).'");
		   }
		  }
		  function myGengoGetPrice()
		  {
			return parseFloat(document.getElementById("mygengo_language").options[document.getElementById("mygengo_language").selectedIndex].getAttribute("unit_price"));
		  }
		  var myGengoLastUpdatePrice;
		  function myGengoUpdatePrice()
		  {
			var price =  "mygengo_action=get_cost"+
			  "&body_src="+encodeURIComponent(document.getElementById("mygengo_body").value)+
			  "&lc_src="+encodeURIComponent(myGengoSelectLanguageGet().getAttribute("lc_src"))+
			  "&lc_tgt="+encodeURIComponent(myGengoSelectLanguageGet().getAttribute("lc_tgt"))+
			  "&tier="+encodeURIComponent(myGengoSelectLanguageGet().getAttribute("tier"));
			  if(price != myGengoLastUpdatePrice)
				myGengoLastUpdatePrice = price;
			  else
				return;//avoid repeat same call to the API
			myGengoReadURL(
			  document.location,
			  price
			,
			function(aData)
			{
			  if(aData.indexOf("unit_count") != -1)
			  {
			  	document.getElementById("myGengoTotalWords").innerHTML = aData.replace(/.*"unit_count"\:([^,]+),.*/, "$1")
				document.getElementById("myGengoTotalCredits").innerHTML = aData.replace(/.*"credits"\:([^\}]+)\}.*/, "$1")
			  }
			  else
			  {
				var text = document.getElementById("mygengo_body").value.split(" ");
				document.getElementById("myGengoTotalWords").innerHTML = text.length-1;
				document.getElementById("myGengoTotalCredits").innerHTML = myGengoRoundNumber((text.length-1)*myGengoGetPrice(), 2);
			  }
			  
			});
			
			
			/*
			  var text = document.getElementById("mygengo_body").value.split(" ");
			  document.getElementById("myGengoTotalWords").innerHTML = text.length-1;
			  document.getElementById("myGengoTotalCredits").innerHTML = myGengoRoundNumber((text.length-1)*myGengoGetPrice(), 2);
			*/
		  }
		  function myGengoRoundNumber(num, rlength)
		  {
			return Math.round(parseFloat(num)*Math.pow(10,rlength))/Math.pow(10,rlength);
		  }
		  function myGengoSelectLanguageGet()
		  {
			return document.getElementById("mygengo_language").options[document.getElementById("mygengo_language").selectedIndex];
		  }
		  var myGengoPriceInterval;
		  function myGengoUpdatePriceRemoveInterval()
		  {
			try{clearInterval(myGengoPriceInterval);}catch(e){}
		  }
		  function myGengoUpdatePriceSetInterval()
		  {
			myGengoUpdatePriceRemoveInterval();
		  	myGengoPriceInterval = setInterval(myGengoUpdatePrice, 700);
		  }
		  function myGengoReadURL(aURL, aVars, aFunction)
		  {
			var Requester;
	
			if (window.XMLHttpRequest) { // Mozilla, Safari, ...
				Requester = new XMLHttpRequest();
			} 
			else if (window.ActiveXObject) { // IE
				try {
					Requester = new ActiveXObject("Msxml2.XMLHTTP");
				} 
				catch (e) {
					try {
						Requester = new ActiveXObject("Microsoft.XMLHTTP");
					} 
					catch (e) {}
				}
			}
			Requester.onreadystatechange = function()
			{
			  if (Requester.readyState == 4)
			  {
				  if (Requester.status == 200)
				  {
					(function()
					{
					  aFunction(Requester.responseText.split("#myGe"+"ngoDATA#")[1]);
					}());
				  }
			  }
			};
			Requester.open("POST", aURL, true);
			Requester.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
			Requester.send(aVars);
		  }
		  function myGengoLoadComment(aID)
		  {
		  	document.getElementById("myGengoTranslationComments"+aID).innerHTML = "<div class=\"myGengoVerticalAlign\"><img src=\"http://titobouzout.github.com/amyg/loading.png\" border=\"0\"/> '.$this->s('loading.please.wait').'</div>";

			myGengoReadURL(
			  document.location,
			  "mygengo_action=print_job_comments&job_id="+aID
			,
			function(aData)
			{
			  document.getElementById("myGengoTranslationComments"+aID).innerHTML = aData;
			});
		  }
			
		</script>';
	  }
	  
	  //display the JavaScript to execute when the page load
	  function jsEnd()
	  {
		echo '<script>myGengoUpdatePrice()</script>';
	  }
	  //process data sent via post
	  function process()
	  {

		//check if Curl is enabled
		
		if(!function_exists('curl_init'))
		  die('<h3 class="myGengoActionTitle" style="color:red">'.$this->s('curl.needed').'</h3>');
		
		//check if there is a need to update HTML on page
		
		  if(myG::i()->p('mygengo_action') == 'print_job')
		  {
			 echo '#myGengoDATA#';
			//get the job
			
			 $response_type = 'json'; // choose your response type; 'json' or 'xml'
			 $header = array('Accept: application/'.$response_type);
			 $query['pre_mt'] = 0;
			 $query = array('api_key' => $this->keyPublic(), 'ts' => gmdate('U'));
			 ksort($query);
			 $query = http_build_query($query);
			 $hmac = hash_hmac('sha1', $query, $this->keyPrivate());
			 $query .= "&api_sig={$hmac}";
			 $url = 'translate/job/' . myG::i()->p('job_id'). '/?'. $query;
			 $ch = curl_init($this->server.$url);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			 curl_setopt($ch, CURLOPT_HEADER, false);
			 $job = curl_exec($ch);
			 curl_close($ch);
		   
			 $job = json_decode($job)->response->job;
		  
		   //print the job by ajax
			 echo '
			 <div onmouseover="if(this.hasAttribute(\'comment_added\')){}else{this.setAttribute(\'comment_added\', true);myGengoLoadComment('.$job->job_id.');}">
				 <div class="myGengoJobDetailTitle"><b>'.$this->e($job->slug).'</b></div>
				   
				 <span class="myGengoJobDetailData">
				 
					 <a href="javascript://" style="font-size:11px;font-weigth:none" onclick="myGengoFlipUtil(\'myGengoTranslation'.$job->job_id.'\')">'.$this->s('translation').'</a> -
					 <a href="javascript://" onclick="myGengoFlipUtil(\'myGengoTranslationComments'.$job->job_id.'\')">'.$this->s('num.comments').'</a> -
					  ID #'.$job->job_id.' - 
					  '.$this->s('job.unit.count').' '.$this->e($job->unit_count).' - 
					  '.$this->s('job.tier.type').' : '.$this->e($this->s('tier.type.'.$job->tier)).' - 
					  '.$this->s('job.total.cost').' '.$this->e($job->credits).' - 
					  '.$this->s('job.status').' : '.$this->e($this->s('status.'.$job->status)).' - 
					  '.$this->s('job.languages',
								 array(
									   'from' => $this->e($this->getLanguageName($job->lc_src)),
									   'to' => $this->e($this->getLanguageName($job->lc_tgt))
									   )
								 ).'
				   <br />
					 
				 </span>
				 
				 <span style="display:none" id="myGengoTranslation'.$job->job_id.'">
				 
					 <div class="clear"></div>
					 
					 <pre wrap="true" style="width:43%;float:left;"><b>'.$this->s('job.original.text').'</b><br/><br/>'.$this->e($job->body_src).'
					 </pre>
					 
					 <pre wrap="true" style="width:44%;float:left;"><b>'.$this->s('job.translated.text').'</b><br/><br/>'.$this->e($job->body_tgt).'
					 </pre>
					 
					 <div class="clear"></div>
					 
				 </span>
				 
				 ';

				 echo '<div id="myGengoTranslationComments'.$job->job_id.'" style="display:none"></div>';
				echo '</div>';
			    echo '#myGengoDATA#';
				exit;
			}
			
			
		  //print job comment by ajax
			if(myG::i()->p('mygengo_action') == 'print_job_comments')
			{
				echo '#myGengoDATA#';

			   //get the comments
			   
				 $response_type = 'json'; // choose your response type; 'json' or 'xml'
				 $header = array('Accept: application/'.$response_type);
				 $query = array('api_key' => $this->keyPublic(), 'ts' => gmdate('U'));
				 ksort($query);
				 $query = http_build_query($query);
				 $hmac = hash_hmac('sha1', $query, $this->keyPrivate());
				 $query .= "&api_sig={$hmac}";
				 $url = 'translate/job/' . myG::i()->p('job_id') . '/comments?' . $query;
				 $ch = curl_init($this->server.$url);
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				 $comments = json_decode(curl_exec($ch))->response;
				 curl_close($ch);
					 
					 for($i=0;$i<count($comments->thread);$i++)
					 {
					   if($comments->thread[$i]->author == 'customer')
						 $comments->thread[$i]->author = $this->s('customer');
						 
					   echo '
								   <div class="myGengoTranslationComment">
									 <div>'.$this->e($comments->thread[$i]->body).'</div>
									 <div>'.$this->s('job.comment.by',
													 array(
													   'user' => $this->e($comments->thread[$i]->author)
														   )).'</div>
								   </div>';
					 }
					 
					 echo '
								  <form method="post"  enctype="application/x-www-form-urlencoded">
								   <div class="myGengoTranslationComment">
									 <textarea name="mygengo_comment" rows="6" class="edit" cols="55" tabindex="1"></textarea>
									 <br/><br/>
									 <input value="'.$this->s('form.submit.comment').'" class="smallerbox" type="submit">
									 <input value="submit_comment" name="mygengo_action" type="hidden">
									 <input value="'.myG::i()->p('job_id').'" name="mygengo_comment_id" type="hidden">
								   </div>
								 </form>';
				   
					echo '#myGengoDATA#';
					exit;
			}
		  //looking for cost?
			if(myG::i()->p('mygengo_action') == 'get_cost')
			{
			  $response_type = 'json'; // choose your response type; 'json' or 'xml'
			  $header = array('Accept: application/'.$response_type);
			  $job1 = array(
				  'body_src' => myG::i()->p('body_src'),
				  'lc_src' => myG::i()->p('lc_src'),
				  'lc_tgt' => myG::i()->p('lc_tgt'),
				  'tier' => myG::i()->p('tier'),
			  );
			  $jobs = array('job_1' => $job1);
			  $data = array('jobs' => $jobs);
			  $params = array('api_key' => $this->keyPublic(), 'ts' => gmdate('U'), 'data' => json_encode($data));
			  ksort($params);
			  $enc_params = json_encode($params);
			  $hmac = hash_hmac('sha1', $enc_params, $this->keyPrivate());
			  $params['api_sig'] = $hmac;
			  $url = 'translate/service/quote';
			  $ch = curl_init($this->server.$url);
			  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			  curl_setopt($ch, CURLOPT_POST, true);
			  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			  $response = curl_exec($ch);
			  echo '#myGengoDATA#';
			  print_r($response);
			  echo '#myGengoDATA#';
			  curl_close($ch);
			  exit;
			}
		
		//check if there is a need to save something.
		
		  //set the keys
		  
			if(myG::i()->p('mygengo_action') == 'update_keys')
			{
			  if($this->application == 'textpattern')
			  {
				set_pref('mygengo_key_public', myG::i()->p('mygengo_key_public'), 'myGengo', 2/*hiddden preference*/);
				set_pref('mygengo_key_private', myG::i()->p('mygengo_key_private'), 'myGengo', 2/*hiddden preference*/);
			  }
			  else if($this->application == 'wordpress')
			  {
				update_option('mygengo_key_public', myG::i()->p('mygengo_key_public'));
				update_option('mygengo_key_private', myG::i()->p('mygengo_key_private'));
			  }
			  else if($this->application == 'frog' || $this->application == 'nucleus' || $this->application == 'joomla')
			  {
				file_put_contents(
								  dirname(__FILE__).'/nonpublic/'.md5('keys').'.txt',
								  serialize(
											array(
												  myG::i()->p('mygengo_key_public'),
												  myG::i()->p('mygengo_key_private')
												  )
											)
								  );
			  }
			  
			  $this->processAPIMSG = '<em style="color:green">'.$this->s('keys.updated.sucessfully').'</em><br/><br/>';
			}
		
		//check if the API keys are valid and get the balance
			
			$this->getBalance();
					
		//check if there is a need to save a new comment to a job

		  if($this->loggedIn && myG::i()->p('mygengo_action')  == 'submit_comment')
		  {
			$response_type = 'json'; // choose your response type; 'json' or 'xml'
			$header = array('Accept: application/'.$response_type);
			$data = array('body' => myG::i()->p('mygengo_comment'));
			$params = array('api_key' => $this->keyPublic(), 'ts' => gmdate('U'), 'data' => json_encode($data));
			ksort($params);
			$enc_params = json_encode($params);
			$hmac = hash_hmac('sha1', $enc_params, $this->keyPrivate());
			$params['api_sig'] = $hmac;
			$url = 'translate/job/' . myG::i()->p('mygengo_comment_id') . '/comment';
			$ch = curl_init($this->server.$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			$response = curl_exec($ch);
			curl_close($ch);
			
			if (false === $response)
			  $this->processNEWCommentToJOBMSG = '<em style="color:ref">'.$this->s('comment.unable.to.post').'</em><br/><br/>';
			else
			  $this->processNEWCommentToJOBMSG = '<em style="color:green">'.$this->s('comment.to.job.sucessfully').'</em><br/><br/>';
		  }
		  
		//check if there is a need to save a new job
 
		  if($this->loggedIn && myG::i()->p('mygengo_action')  == 'submit_job')
		  {
					
			if(!$data = @unserialize(myG::i()->p('mygengo_language')))
			  $data = @unserialize(stripslashes(myG::i()->p('mygengo_language')));
			  
			  /* USE POST JOBS NOT JOB */
			  $response_type = 'json'; // choose your response type; 'json' or 'xml'
			  $header = array('Accept: application/'.$response_type);
			  $job1 = array(
				  'type' => 'text',
				  'slug' => myG::i()->p('mygengo_title'),
				  'body_src' => myG::i()->p('mygengo_body'),
				  'lc_src' => $data->lc_src,
				  'lc_tgt' => $data->lc_tgt,
				  'tier' =>  $data->tier,
				  'auto_approve' => 'true',
				  'custom_data' => 'n'
			  );
			  $jobs = array('job_1' => $job1);
			  $data = array('jobs' => $jobs, 'as_group' => 0);
			  $params = array('api_key' => $this->keyPublic(), 'ts' => gmdate('U'), 'data' => json_encode($data));
			  ksort($params);
			  $enc_params = json_encode($params);
			  $hmac = hash_hmac('sha1', $enc_params, $this->keyPrivate());
			  $params['api_sig'] = $hmac;
			  $url = 'translate/jobs';
			  $ch = curl_init($this->server.$url);
			  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			  curl_setopt($ch, CURLOPT_POST, true);
			  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			  $response = curl_exec($ch);
			  curl_close($ch);


					/*
					 
					 USE POST JOB NOT JOBS
			$response_type = 'json'; // choose your response type; 'json' or 'xml'
			$header = array('Accept: application/'.$response_type);
			if(!$data = @unserialize(myG::i()->p('mygengo_language')))
			  $data = @unserialize(stripslashes(myG::i()->p('mygengo_language')));

			$job = array(
				'slug' => myG::i()->p('mygengo_title'),
				'body_src' => myG::i()->p('mygengo_body'),
				'lc_src' => $data->lc_src,
				'lc_tgt' => $data->lc_tgt,
				'tier' => $data->tier
			);
			$data = array('job' => $job);
			$params = array('api_key' => $this->keyPublic(), 'ts' => gmdate('U'), 'data' => json_encode($data));
			ksort($params);
			$enc_params = json_encode($params);
			$hmac = hash_hmac('sha1', $enc_params, $this->keyPrivate());
			$params['api_sig'] = $hmac;
			$url = 'translate/job';
			$ch = curl_init($this->server.$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			$response = curl_exec($ch);
			curl_close($ch);*/
			
			if (false === $response) {
				$this->processNEWJOB->body = myG::i()->p('mygengo_body');
				$this->processNEWJOB->title = myG::i()->p('mygengo_title');
				$this->processNEWJOB->comment = myG::i()->p('mygengo_comment');
				$this->processNEWJOB->msg = '<em style="color:red">'.$this->s('job.post.failed').'</em><br/>';
			} else {
			  
				$this->processNEWJOB->msg = '<em style="color:green">'.$this->s('job.post.sucessfully').'</em><br/><br/>';
			

				  $response_type = 'json'; // choose your response type; 'json' or 'xml'
				  $header = array('Accept: application/'.$response_type);
				  $data = array('body' => myG::i()->p('mygengo_comment'));
				  $params = array('api_key' => $this->keyPublic(), 'ts' => gmdate('U'), 'data' => json_encode($data));
				  ksort($params);
				  $enc_params = json_encode($params);
				  $hmac = hash_hmac('sha1', $enc_params, $this->keyPrivate());
				  $params['api_sig'] = $hmac;
				  $url = 'translate/job/' . json_decode($response)->response->jobs[0]->job_1->job_id . '/comment';
				  $ch = curl_init($this->server.$url);
				  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				  curl_setopt($ch, CURLOPT_POST, true);
				  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
				  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				  $response = curl_exec($ch);
				  curl_close($ch);
			}
			//update the balance on a new job post
			$this->getBalance();
		  }
	  }
	  
	  //get the balance value
	  function getBalance()
	  {
		$response_type = 'json'; // choose your response type; 'json' or 'xml'
		$header = array('Accept: application/'.$response_type);
		$query = array('api_key' => $this->keyPublic(), 'ts' => gmdate('U'));
		ksort($query);$query = http_build_query($query);$hmac = hash_hmac('sha1', $query, $this->keyPrivate());
		$query .= "&api_sig={$hmac}";$url = 'account/balance?' . $query;
		
		$ch = curl_init();curl_setopt($ch, CURLOPT_URL, $this->server.$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HEADER, false);$response = curl_exec($ch);
		
		if (false === $response)
		  $this->loggedIn = false;
		else
		  $this->loggedIn = true;
		curl_close($ch);
		
		
		$this->balanceValue = @json_decode($response);
		
		//print_r($this->balanceValue );
	  }
	  
	  //display the balance of mygengo account
	  function formBalance()
	  {
		if(!isset($this->balanceValue->response->credits))
		  $this->loggedIn = false;
		else
		  $this->loggedIn = true;
		
		if(
		   !isset($this->balanceValue->response->credits) ||
		   $this->balanceValue->response->credits < 0)
		  $this->balanceValue->response->credits = 0;
		
		echo '<h2>plugGengo : '.$this->s('my.balance').' : '.$this->s('my.credits', array('credits' => $this->balanceValue->response->credits)).'</h2>';
		
		if(!$this->loggedIn)
		  echo '<em style="color:red">'.$this->s('first.get.an.account').'<br/><br/></em>';
	  }
	  
	  //display the form that contains the API keys
	  function formAPI()
	  {
		  return '
			
			<h3 class="myGengoActionTitle" style="cursor:pointer;" name="mygengo_form_api" onclick="myGengoFlip(this)">'.$this->s('provide.your.api.keys').' <a>+</a></h3>
			'.$this->processAPIMSG.'
			<form method="post" id="mygengo_form_api" style="marging-left:20px;"  enctype="application/x-www-form-urlencoded">
			  <em style="font-size:11px">
			  '.$this->s('api.keys.explanation').'
				
			  </em>
			  
			  <br/> <br/>
			  '.$this->s('form.key.public').'
			  <br/>
			
			  <input value="'.self::e($this->keyPublic()).'" name="mygengo_key_public" id="mygengo_key_public" size="55" class="edit" tabindex="1" type="text">
			  <br/>
			<br/>
			  '.$this->s('form.key.private').'
			  <br/>
			 
			  <input value="'.self::e($this->keyPrivate()).'" name="mygengo_key_private" id="mygengo_key_private" size="55" class="edit" tabindex="1" type="text">
			  <br/>
		<br/>
			  <input value="'.$this->s('form.submit.update.keys').'" class="smallerbox" type="submit">
			  
			  <input value="update_keys" name="mygengo_action" type="hidden">
			  <br><br>
			</form>
		  ';
	  }

	  //display the jobs details and comments, also allows to publish a new comment
	  function formJobs()
	  {
		if(!$this->loggedIn)
		  return;
		
		  //get jobs
		  
		  
		  $response_type = 'json'; // choose your response type; 'json' or 'xml'
		  $header = array('Accept: application/'.$response_type);
		  $query = array('api_key' => $this->keyPublic(), 'ts' => gmdate('U'));
		  ksort($query);
		  $query = http_build_query($query);
		  $hmac = hash_hmac('sha1', $query, $this->keyPrivate());
		  $query .= "&api_sig={$hmac}";
		  $url = 'translate/jobs/?' . $query;
		  $ch = curl_init($this->server.$url);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		  $jobs = json_decode(curl_exec($ch));
		  curl_close($ch);
		  
		 
		 echo '<h3 class="myGengoActionTitle" style="cursor:pointer;" name="mygengo_form_jobs" onclick="myGengoFlip(this)">'.$this->s('your.jobs.count', array('count' => count($jobs->response ))).'<a> +</a></h3>';
		echo $this->processNEWCommentToJOBMSG;
		 echo '<div id="mygengo_form_jobs" style="display:none">';

		  if(count($jobs->response )< 1)
		  {
			echo '<em style="color:green;">'.$this->s('your.jobs.count.zero').'</em><br/><br/>';
		  }
		  else
		  {
			for($a=0;$a<count($jobs->response);$a++)
			{
			  echo '<div class="myGengoJobDetail" id="myGengoJobDetail'.$jobs->response[$a]->job_id.'"><div class="myGengoVerticalAlign"><img src="http://titobouzout.github.com/amyg/loading.png" border="0"/> '.$this->s('loading.please.wait').'</div></div>';//end translation
			  $this->XMLHTTPRequesterJobID(
										   "mygengo_action=print_job&job_id=".$jobs->response[$a]->job_id,
										   "myGengoJobDetail".$jobs->response[$a]->job_id
										  );
			}
		  }
		  echo '</div>';
		  
		
	  }
	  
	  //display the form that contains the required data for a new Job
	  function formNewJob()
	  {
		  if(!$this->loggedIn)
			return;
		  
		  return '
			
			<h3 class="myGengoActionTitle" style="cursor:pointer;" name="mygengo_form_new_job" onclick="myGengoFlip(this)">'.$this->s('request.a.new.job').' <a>+</a></h3>
			
			'.$this->processNEWJOB->msg.'
			
			<form method="post" id="mygengo_form_new_job" style="marging-left:20px;display:none"  enctype="application/x-www-form-urlencoded">
			  <em style="font-size:11px">
				'.$this->s('request.a.new.job.description').'
			  </em>
			  
			  <br/> <br/>
			  '.$this->s('languaje.and.quality.options').'
			  <br/>
			  '.$this->selectLanguagesHTML.'
			  <br/>
			  <br/>
			  '.$this->s('job.title').'
			  <br/>
			 
			  <input value="'.$this->e($this->processNEWJOB->title).'" name="mygengo_title" id="mygengo_title" value=""  onblur="myGengoUpdatePrice();" onfocus="myGengoUpdatePrice();" size="55" class="edit" tabindex="1" type="text">
			  <br/>
			  <br/>
			  '.$this->s('content.to.translate').'
			  <br/>
			 
			  <textarea name="mygengo_body" id="mygengo_body" onblur="myGengoUpdatePriceRemoveInterval();" onfocus="myGengoUpdatePriceSetInterval();"  rows="20" class="edit" cols="55" tabindex="1">'.$this->e($this->processNEWJOB->body).'</textarea>
			  <br/>
			  <br/>
			  '.$this->s('comment.to.translator').'
			  <br/>
			 
			  <textarea name="mygengo_comment" id="mygengo_comment" rows="5" class="edit" cols="55" tabindex="1">'.$this->e($this->processNEWJOB->comment).'</textarea>
			  <br/>
			  <br/>
			  '.$this->s('total.words').'
			  <br/>
			   '.$this->s('total.unit').'
			  <br/>
			  <br/>
			  <input value="'.$this->s('form.submit.job').'" class="smallerbox" type="submit">
			  <input value="submit_job" name="mygengo_action" type="hidden">
			  <br><br><br>
			</form>
		  ';
	  }
	  
	  //from a lang code gets the lang name
	  function getLanguageName($id)
	  {
		return $this->languageNames[$id];
	  }
	  
	  //api call that sets to memory the lang information
	  function getLanguages()
	  {
		//request language pairs
		  
		  $response_type = 'json'; // choose your response type; 'json' or 'xml'
		  $header = array('Accept: application/'.$response_type);
		  $query = array('api_key' => $this->keyPublic(), 'ts' => gmdate('U'));
		  ksort($query);
		  $query = http_build_query($query);
		  $hmac = hash_hmac('sha1', $query, $this->keyPrivate());
		  $query .= "&api_sig={$hmac}";
		  $url = 'translate/service/language_pairs?' . $query;
		  $ch = curl_init($this->server.$url);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		  $response = curl_exec($ch);
		  curl_close($ch);
		  $languagePairs = @json_decode($response);
		  
		//request language names
		
		  $response_type = 'json';
		  $header = array('Accept: application/'.$response_type);
		  $query = array('api_key' => $this->keyPublic(), 'ts' => gmdate('U'));
		  ksort($query);
		  $query = http_build_query($query);
		  $hmac = hash_hmac('sha1', $query, $this->keyPrivate());
		  $query .= "&api_sig={$hmac}";
		  $url = 'translate/service/languages?' . $query;
		  $ch = curl_init($this->server.$url);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		  $response = curl_exec($ch);
		  curl_close($ch);
		  $languages = @json_decode($response);

		//put language names to language pairs
		
		  $this->languageNames = array();

		  for($a=0;$a<count($languagePairs->response);$a++)
		  {
			for($i=0;$i<count($languages->response);$i++)
			{
			  if($languages->response[$i]->lc == $languagePairs->response[$a]->lc_src)
			  {
				$languagePairs->response[$a]->lc_src_name = $languages->response[$i]->localized_name;
				$this->languageNames[$languagePairs->response[$a]->lc_src] = $languages->response[$i]->localized_name;
			  }
			  if($languages->response[$i]->lc == $languagePairs->response[$a]->lc_tgt)
			  {
				$languagePairs->response[$a]->lc_tgt_name = $languages->response[$i]->localized_name;
				$this->languageNames[$languagePairs->response[$a]->lc_tgt] = $languages->response[$i]->localized_name;
			  }
			}
		  }
		
		  $this->selectLanguagesHTML = '';
		  $this->selectLanguagesHTML .= '<select name="mygengo_language"  id="mygengo_language" onchange="myGengoUpdatePrice();" onblur="myGengoUpdatePrice();" onfocus="myGengoUpdatePrice();">';
		  for($i=0;$i< count($languagePairs->response);$i++)
		  {
			$this->selectLanguagesHTML .= (
	
								'<option
								unit_price="'.$this->e($languagePairs->response[$i]->unit_price).'"
								value="'.$this->e(serialize($languagePairs->response[$i])).'"  
								lc_src="'.$this->e($languagePairs->response[$i]->lc_src).'"  
								lc_tgt="'.$this->e($languagePairs->response[$i]->lc_tgt).'"  
								tier="'.$this->e($languagePairs->response[$i]->tier).'">  '
								.$this->e($languagePairs->response[$i]->lc_src_name
								. ' > '
								.$languagePairs->response[$i]->lc_tgt_name
								. ' ( '
								.$this->s('cost.per.unit')
								.' '
								.$languagePairs->response[$i]->unit_price
								. ' ) '
								.$this->s('tier.type.'.$languagePairs->response[$i]->tier))
								.'</option>');
		  }
		  $this->selectLanguagesHTML .= '</select>';
		  
		  return  $this->selectLanguagesHTML;
	  }
	  //display the form to change the language of this interface.
	  function formChangeLanguage()
	  {
		return '
		  <div>
		  <br/><br/>
			'.$this->s('change.interface.language').'
			<form method="post" style="display:inline !important;">
				<a href="javascript://" onclick="document.cookie =
  \'myGengoLanguage=en; expires=Thu, 2 Aug 2036 20:47:11 UTC; path=/\';
this.parentNode.submit();">English</a>
				<input type="hidden" id="myGengoLanguage" name="myGengoLanguage" value="en" />
			</form>
			 - 
			<form method="post" style="display:inline !important;">
				<a href="javascript://" onclick="document.cookie =
  \'myGengoLanguage=es; expires=Thu, 2 Aug 2036 20:47:11 UTC; path=/\';this.parentNode.submit();">Español</a>
				<input type="hidden" id="myGengoLanguage" name="myGengoLanguage"  value="es" />
			</form>
		  </div>
		  <div>
			'.$this->s('plugin.home.page', array('version' => $this->scriptVersion)).'
		  </div>
		';
	  }
	  //gets the public key
	  function keyPublic()
	  {
		if($this->application == 'textpattern')
		{
		  //there is a delay in textpattern when saving preferences
		  if($this->p('mygengo_key_public') != '')
			return $this->p('mygengo_key_public');
		  else
			return get_pref('mygengo_key_public');
		}
		else if($this->application == 'wordpress')
		{
		  if($this->p('mygengo_key_public') != '')
			return $this->p('mygengo_key_public');
		  else
			return get_option('mygengo_key_public');
		}
		else if($this->application == 'frog' || $this->application == 'nucleus' || $this->application == 'joomla')
		{
		  if($this->p('mygengo_key_public') != '')
			return $this->p('mygengo_key_public');
		  else
		  {
			$array = @unserialize(@file_get_contents(dirname(__FILE__).'/nonpublic/'.md5('keys').'.txt'));
			return $array[0];
		  }
		}
		
	  }
	  
	  //gets the private key
	  function keyPrivate()
	  {
		if($this->application == 'textpattern')
		{
		  //there is a delay in textpattern when saving preferences
		  if($this->p('mygengo_key_private') != '')
			return $this->p('mygengo_key_private');
		  else
			return get_pref('mygengo_key_private');
		}
		else if($this->application == 'wordpress')
		{
		  if($this->p('mygengo_key_private') != '')
			return $this->p('mygengo_key_private');
		  else
			return get_option('mygengo_key_private');
		}
		else if($this->application == 'frog'|| $this->application == 'nucleus' || $this->application == 'joomla')
		{
		  if(!is_dir(dirname(__FILE__).'/nonpublic/'))
		  {
			@umask(0);
			@mkdir(dirname(__FILE__).'/nonpublic/', intval(0777, 8));
			file_put_contents(dirname(__FILE__).'/nonpublic/.htaccess', 'AuthUserFile .htpasswd
AuthGroupFile /dev/null
AuthName "Password Protected Area"
AuthType Basic

<limit GET POST>
require valid-user
</limit>
');
		  }
		  
		  if($this->p('mygengo_key_private') != '')
			return $this->p('mygengo_key_private');
		  else
		  {
			$array = @unserialize(@file_get_contents(dirname(__FILE__).'/nonpublic/'.md5('keys').'.txt'));
			return $array[1];
		  }
		}
	  }
	  
	  //returns localized string of this interface
	  function s($s, $atts = array())
	  {
		return myGengoLanguage::i()->getString($s, $atts);
	  }
	  
	  //escapes html tags
	  function e($string)
	  {
		// init
        $aTransSpecchar = array(
            '&' => '&amp;',
			'"' => '&quot;',
            '<' => '&lt;',
            '>' => '&gt;'
            );
		// ENT_COMPAT set
        // return translated string
        return strtr($string,$aTransSpecchar);
	  }	  
	  //resolves the post, avoid warnings
	  function p($e)
	  {
		if(isset($_POST) && isset($_POST[$e]))
		  return $_POST[$e];
		else
		  return '';
	  }
		function XMLHTTPRequesterJobID($action, $div)
		{
		  echo '<script>
		  setTimeout(function ()
		  {
			var Requester;
	
			if (window.XMLHttpRequest) { // Mozilla, Safari, ...
				Requester = new XMLHttpRequest();
			} 
			else if (window.ActiveXObject) { // IE
				try {
					Requester = new ActiveXObject("Msxml2.XMLHTTP");
				} 
				catch (e) {
					try {
						Requester = new ActiveXObject("Microsoft.XMLHTTP");
					} 
					catch (e) {}
				}
			}
			Requester.onreadystatechange = function()
			{
			  if (Requester.readyState == 4)
			  {
				  if (Requester.status == 200)
				  {
				  // alert(Requester.responseText.split("#myGeng"+"oDATA#")[1]);
					  document.getElementById("'.$div.'").innerHTML = Requester.responseText.split("#myGen"+"goDATA#")[1];
				  }
			  }
			};
			Requester.open("POST", document.location, true);
			Requester.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
			Requester.send("'.$action.'");
		  }, myGengoLoadTime);
		  myGengoLoadTime+=500;
		  </script>
		  ';
		}
	}
	
	class myGengoLanguage 
	{
		private static $__instance;
	
		public static function i() { 
			if (!isset(self::$__instance)) {
				$c = __CLASS__;
				self::$__instance = new $c;
			}
			return self::$__instance;
		}
		
		private function __clone(){}
		
		private function __construct(){
		  
		  $this->validLanguages = array('es', 'en');
		  
		  //if user wants to change language
		  if(isset($_POST['myGengoLanguage']) && $this->isValid($_POST['myGengoLanguage']) )
		  {
			$this->cookieAdd('myGengoLanguage', $_POST['myGengoLanguage']);
			$this->language = $_POST['myGengoLanguage'];
		  }
		  //if user has selected language
		  else if(isset($_COOKIE['myGengoLanguage']) && $this->isValid($_COOKIE['myGengoLanguage']) )
		  {
			$this->language = $_COOKIE['myGengoLanguage'];
		  }
		  //default language
		  else
		  {
			$this->language = 'en';
		  }
		  $this->cookieAdd('language', $this->language);
		}
		function loadStrings($code)
		{
		  if(myG::i()->application == 'textpattern')
		  {
			
			//__TEXTPATTERN_LOCALIZATION__
			
			$dtd = trim($language[$code]);
		  }
		  else
		  {
			$dtd = file_get_contents(dirname(__FILE__).'/myGengoLanguages/'.$code.'.dtd');
		  }
		  
		  $dtd = explode('<!ENTITY myGengo.', $dtd);
		  foreach($dtd as $k => $v)
		  {
			$dtd[$k] = trim($v);
		  }
		  foreach($dtd as $k => $v)
		  {
			$key = explode(' ', $v);
			$key = $key[0];
			$this->strings[$code][$key] = preg_replace('~^'.preg_quote($key, '~').' "~', '', $v);
			$this->strings[$code][$key] = preg_replace('~">$~', '', $this->strings[$code][$key]);
		  }
		}
		function get($k)
		{
		  if(isset($this->strings[$this->language][$k]))
			return $this->strings[$this->language][$k];
		  else
			return '__'.$k.'__';
		}
		function getString($k, $array = false)
		{
		  if(!$this->strings[$this->language])
			$this->loadStrings($this->language);
			
		  $s = $this->get($k);
		  
		  if(is_array($array) && sizeof($array) > 0)
		  {
			  foreach($array as $k => $v)
			  {
				  $s = preg_replace('~\{'.preg_quote($k, '~').'\}~i', $v, $s);
			  }
		  }
		  return $this->d($s);
		}
		function isValid($code)
		{
		  if(in_array($code, $this->validLanguages))
			return true;
		  else
			return false;
		}
		//decode html special chars
		function d($string)
		{
		  // init
		  $aTransSpecchar = array(
			  '&amp;' => '&',
			  '&quot;' => '"',
			  '&lt;' => '<',
			  '&gt;' => '>'
			  );
		  return strtr($string,$aTransSpecchar);
		}
		function cookieAdd($name, $value, $time = 360200)
		{
			@setcookie($name, $value, time()+$time, '', $_SERVER['SERVER_NAME']);
			@setcookie($name, $value, time()+$time, '', $_SERVER['HTTP_HOST']);
			$_COOKIE[$name] = $value;
		}
	}
?>