<?php 
//CONFIGS
$keyword = "Acesse o valor";
$nomeLoja = "APOLLO";
$linksCSV = "icarros.csv";
set_time_limit(1000);

// CONVERTE CSV EM ARRAY
$csv = array_map("str_getcsv", file($linksCSV)); 
$header = array_shift($csv); 

// CAPTURA SOMENTE A PRIMEIRA FILEIRA DO CSV
$col = array_search("Value", $header, true); 
foreach ($csv as $row) {      
	$urlsVeiculos[] = $row[$col]; 
}

function getValue($status=null){
	if(is_null($status))
		return 0;
	foreach ($status as $elemento) {
		foreach ($elemento->childNodes as $nd) {
			if(isset($nd->tagName))
				return $nd->nodeValue;
		}
	}
}

function PuxarInformacoes($url=null,$id=null){
	global $keyword, $nomeLoja;

	// INICIA O CURL
	$ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_USERAGENT, $ua);
	curl_setopt($ch, CURLOPT_COOKIE, 'NID=67=pdjIQN5CUKVn0bRgAlqitBk7WHVivLsbLcr7QOWMn35Pq03N1WMy6kxYBPORtaQUPQrfMK4Yo0vVz8tH97ejX3q7P2lNuPjTOhwqaI2bXCgPGSDKkdFoiYIqXubR0cTJ48hIAaKQqiQi_lpoe6edhMglvOO9ynw; PREF=ID=52aa671013493765:U=0cfb5c96530d04e3:FF=0:LD=en:TM=1370266105:LM=1370341612:GM=1:S=Kcc6KUnZwWfy3cOl; OTZ=1800625_34_34__34_; S=talkgadget=38GaRzFbruDPtFjrghEtRw; SID=DQAAALoAAADHyIbtG3J_u2hwNi4N6UQWgXlwOAQL58VRB_0xQYbDiL2HA5zvefboor5YVmHc8Zt5lcA0LCd2Riv4WsW53ZbNCv8Qu_THhIvtRgdEZfgk26LrKmObye1wU62jESQoNdbapFAfEH_IGHSIA0ZKsZrHiWLGVpujKyUvHHGsZc_XZm4Z4tb2bbYWWYAv02mw2njnf4jiKP2QTxnlnKFK77UvWn4FFcahe-XTk8Jlqblu66AlkTGMZpU0BDlYMValdnU; HSID=A6VT_ZJ0ZSm8NTdFf; SSID=A9_PWUXbZLazoEskE; APISID=RSS_BK5QSEmzBxlS/ApSt2fMy1g36vrYvk; SAPISID=ZIMOP9lJ_E8SLdkL/A32W20hPpwgd5Kg1J');
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);

	$data = curl_exec($ch);
	curl_close($ch);
	//ENCERRA O CURL

	//XPATH		
	$thispage= new DOMDocument;
	libxml_use_internal_errors(true);
	$thispage->loadHTML($data);
	libxml_clear_errors();
	$xpath=new DOMXPath($thispage);

    $posAnuncioSearch = 1;
    while ($posAnuncioSearch <= 20) {
    	$status=$xpath->query("//*[@class='clearfix anuncios']/form[1]/ul[1]/li[".$posAnuncioSearch."]");
    	$infoAnuncio = getValue($status);

    		if ($posAnuncioSearch == 1) {
    			$valorVeiculoPrimeiro = substr(
											$infoAnuncio, 
											(strpos($infoAnuncio,"R$")+3),
											((strpos($infoAnuncio,"preço à vista"))-(strpos($infoAnuncio,"R$")+3))
										);
    		}

    		if(strpos($infoAnuncio, $keyword) == false){
    			$nomeVeiculo = substr($infoAnuncio, 0, strpos($infoAnuncio,"R$"));
				$anoVeiculo = substr(
									$infoAnuncio, 
									(strpos($infoAnuncio,"Ano")+3),
									((strpos($infoAnuncio,"Km"))-(strpos($infoAnuncio,"Ano")+3))
								);
			  	$posAnuncio = "X";
				$lojaVeiculo = "X";
				$valorVeiculoLoja = "X";
			   	$classificacaoAnuncio = 3;
			} else{
			    $posAnuncio = $posAnuncioSearch;

				$nomeVeiculo = substr($infoAnuncio, 0, strpos($infoAnuncio,"R$"));
				$anoVeiculo = substr(
									$infoAnuncio, 
									(strpos($infoAnuncio,"Ano")+3),
									((strpos($infoAnuncio,"Km"))-(strpos($infoAnuncio,"Ano")+3))
								);
				$valorVeiculoLoja = substr(
									$infoAnuncio, 
									(strpos($infoAnuncio,"R$")+3),
									((strpos($infoAnuncio,"preço à vista"))-(strpos($infoAnuncio,"R$")+3))
								);
				$lojaVeiculo = "$nomeLoja";

				switch ($posAnuncioSearch){
				    case 1:
				        $classificacaoAnuncio = 1;
				        break;
				    case 2:
				    case 3:
				        $classificacaoAnuncio = 2;
				        break;
				    default:
				        $classificacaoAnuncio = 3;
				        break;
				}
        		break;
			}

		$posAnuncioSearch++;
    }
	    
    switch ($classificacaoAnuncio){
	    case 1:
	        $tablestyle = "style='background-color:yellowgreen;color:white;'";
	        break;
	    case 2:
	        $tablestyle = "style='background-color:darkgoldenrod;color:white;'";
	        break;
	    case 3:
	        $tablestyle = "style='background-color:tomato;color:white;'";
	        break;
	    default:
	    	$tablestyle = "";
	    	break;
	}

    echo "<tr ".$tablestyle.">
 		  	<td>".$id."</td>
		    <td><a href='".$url."' target='_blank'>".$nomeVeiculo."</a></td>
		    <td>".$anoVeiculo."</td>
		    <td>".$valorVeiculoPrimeiro."</td>
		    <td>".$valorVeiculoLoja."</td>
		    <td>".$lojaVeiculo."</td>
		    <td>".$posAnuncio."</td>
		  </tr>";
};
?>
<!DOCTYPE html>
<html>
	<style>
		table, th, td {
		  border:1px solid black;
		}
	</style>
	<body>
		<h1>Posições iCarros (Apollo)</h1>
		<table style='width:100%'>
		  <tr>
		 	<th>ID</th>
		    <th>CARRO</th>
		    <th>ANO</th>
		    <th>VALOR 1º</th>
		    <th>VALOR LOJA</th>
		    <th>LOJA 1ª</th>
		    <th>POS LOJA</th>
		  </tr>
			<?php 
			$i= 1;
			foreach($urlsVeiculos as $urlVeiculo){
				//echo $i." - ".$urlVeiculo."</br>";
				PuxarInformacoes($urlVeiculo,$i);
				$i++;
				sleep(10);
			}
			?>
		</table>
	</body>
</html>