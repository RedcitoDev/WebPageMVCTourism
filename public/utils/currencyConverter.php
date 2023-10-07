<?php
class currencyConverter
{
	public $fromCurr 	= 'EUR';
	public $toCurr 		= 'MXN'; //por defecto

	function __construct($amount, $to, $from)
	{
		if(intval($amount) > 0) {
			$this->amount = intval($amount);
		}
		
		if(trim($to) != '') {
			$this->toCurr = $to;
		}
		
		if(trim($from) != '') {
			$this->fromCurr = $from;
		}
	}

	function getUpdate()
	{
		$page = 'https://www.google.com/search?&q='  .$this->amount . '+' . $this->fromCurr . '+in+' . $this->toCurr;

		$curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $page,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        
        $returnRawHtml = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            $returnRawHtml = "cURL Error #:" . $err;
        }

		$returnRawHtml = explode('<div class="BNeawe iBp4i AP7Wnd">',$returnRawHtml);
		$returnRawHtml = explode("</div>", $returnRawHtml[2]);
		$returnRawHtml = $returnRawHtml[0];
		$returnRawHtml = explode(" ", $returnRawHtml);
		$returnRawHtml = $returnRawHtml[0];
        
		return $returnRawHtml;
	}
}
?>