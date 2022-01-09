 <!DOCTYPE html>
 <html>
 <head>
 	<title>bim arşiv</title>
 	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

 </head>
 <body class="bg-secondary">
 	<?php 
 	$url = 'http://www.bim.com.tr/Categories/680/afisler.aspx';

 	$curl = curl_init();

 	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
 	curl_setopt($curl, CURLOPT_URL, $url);
 	curl_setopt($curl, CURLOPT_REFERER, $url);
 	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
 	curl_setopt($curl, CURLOPT_HEADER, false);

 	$rawData = curl_exec($curl);

 	preg_match_all('@<a class="subTabArea triangle">\s*<span class="text">(.*?)<.*?<div class="smallArea col-4 col-md-3">(.*?)</div>@si', $rawData, $matches, PREG_SET_ORDER);

 	$data = array_map(fn($i) => [$i[1],$i[2]], $matches);


 	foreach ($data as &$value) {
    	//  lookahead (?=..) and a lookbehind (?<=..) //
 		preg_match_all('~(?<=data-bigimg=")(.*?)(?=")~si', $value[1], $value[1]);
 		$value[1] = array_map(fn($i) => 'https://www.bim.com.tr'.$i, $value[1][0]);
 	}
 	unset($value);

 	$data = array_chunk($data, 2);

 	curl_close($curl);

 	$categories = ["GEÇEN HAFTA","BU HAFTA","GELECEK HAFTA", "BİM'DEN SİZE"];

 	?>


 	<?php foreach ($data as $key => $value) { ?>
 		<div class="row justify-content-center text-center p-4 gy-5" id="gallery">
 			<h1><b><?= $categories[$key] ?></b></h1>
 			<?php foreach ($value as $key2 => $value2) { ?>
 				<div class="col-6">
 					<h1><?= $value2[0] ?></h1>
 					<?php foreach ($value2[1] as $key3 => $value3) { ?> 
 						<img class="mb-3 w-100" src="<?= $value3 ?>" data-bs-toggle="modal" data-bs-target="#afis-modal" onclick="changeModalImage(this)"> 
 					<?php } ?>
 				</div>
 			<?php } ?>
 		</div>
 	<?php } ?>
 	<!-- Modal -->
 	<div class="modal fade" id="afis-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
 		<div class="modal-dialog  modal-dialog-centered modal-fullscreen-lg-down">
 			<div class="modal-content h-auto">
 				<div class="modal-body">
 					<img class="d-block w-100" id="modalImage" src="">
 				</div>
 			</div>
 		</div>
 	</div>

 	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
 	<script type="text/javascript">
 		function changeModalImage(el) {
 			document.getElementById("modalImage").src = el.src
 		};
 	</script>
 </body>
 </html>
