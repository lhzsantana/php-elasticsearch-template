<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Telephone Generator</title>
	<link rel="stylesheet" href="css/style.css">
</head>

<body>
	
	<div id="container">
		
		<div style="padding:5px" id="header">
			<div style="float:left">
				<b>Telephone generator</b>
				<br> Hi
				<?php
					session_start();
					echo $_SESSION["user_name"];
				?>
				!
			</div>
			<div style="margin:1px;float:right">
				<form method="POST" action="logout.php">
					<button type="submit">Logout</button>
				</form>
			</div>
			
			<div style="margin:1px;float:right">
				<form method="POST" action="history.php">
					<button type="submit">History</button>
				</form>
			</div>
			<div style="margin:1px;float:right">
				<form method="POST" action="index.php">
					<button type="submit">New list</button>
				</form>
			</div>
			
			<!--
			<div style="margin:1px;float:right">
				<form method="POST" action="upload.php">
					<button type="submit">Upload</button>
				</form>
			</div>
			-->
		</div>
	
		<!--generator-->
		<form class="results cf" action="file_generator.php" method="POST">
			<br>
		<br>
		<br>
			<?php
			
				$execution_time = microtime(); // Start counting
				
				$restrictions= implode (", ", $_POST["restrictions"]);				
					
				require "elasticsearch/vendor/autoload.php";
					
				$ELASTICSEARCH_SERVER = array("localhost:9200");
				$conn = array();
				$conn["hosts"] = $ELASTICSEARCH_SERVER;
					
				$client = new Elasticsearch\Client($conn);
					
				$final_phone_list=array();
				$size = $_POST["size"];
				$code = $_POST["code"];
				$city = $_POST["city"];
				$show_results = false;
				
				//echo $size;
				//echo $code;
				//echo $city;
				if(isset($city) && $city!=""){		
					//echo "city";			
					//search the name of the city in elasticsearch
					
					$searchParams = array(
						"size" => 1,               // how many results *per shard* you want back
						"index" => "kubal",
						"type" => "city",
						"body" => array(
							"query" => array(
							"match" => array("city" => $city)
							)
						)
					);
					
					//echo "A";
					//echo json_encode($searchParams);
					//echo "B";
					//print_r($client);
					//echo "C";
					
					try {
						//chamada ao server
						$results = $client->search($searchParams);						
						
						//echo print_r($results);
						//echo "-->".$results['took'];
						if (!empty($results['hits']['hits'])) {
							
							//echo "D";
							$hits=$results['hits']['hits'];
							$total=$results['hits']['total'];
							//echo print_r($result["size"]);
							//echo print_r($hits);
							//echo $total;
							
							for($i=0; $i < $total; $i++) {
								$hit = $hits[$i];
								//print_r($hit);
								//echo $hit['_source']['city'];
								//echo $hit['_source']['codes'];
								$codes[$i]=$hit['_source']['codes'];
							}
							
						}else{
							echo "No results for this city.";
						}
						
					} catch (Exception $e) {
						echo 'Caught exception: ',  $e->getMessage(), "\n";
					}

				}else{
					//echo "code";
					$codes = array($code);
				}
				
				//print_r($codes);
				//echo $codes[0];
				
				foreach ($codes as &$code) {
					//echo "it1";
					//echo $code;
					
					if(isset($code) && ($code[0]=="0" || $code[0]=="1")){
						echo $code." is a bad code request, the number can not start with 0 or 1.";
					}else{
						
						$show_results=true;
						
						do{
							$generate_more_phones=false;
							
							$pre_phone_list=array();
							$j = 0;
							for ($i = 0; $i<$size ; $i++) {
								
								do{
									$npa = mt_rand(200,999);
									$nxx = mt_rand(100,999);
									$xxxx = sprintf('%04d', mt_rand(0000,9999));
									
									//$phone = $npa."-".$nxx."-".$xxxx;
									//$phone = $code."-".$nxx."-".$xxxx;
									$phone = $code.$nxx.$xxxx;
								
								}while($nxx%100==11);
								
								$pre_phone_list[]=$phone;
							}					
							
							//print_r($pre_phone_list);
							//echo "abc";
							//echo $restrictions;
							
							if(isset($restrictions)){
								
								//echo "def";
							
								foreach ($pre_phone_list as $pre_phone) {
									
									$p1=substr($pre_phone,0,3);
									$p2=substr($pre_phone,3,11);
									
									//echo $pre_phone."<br>";
									//echo $p1."<br>";
									//echo $p2."<br>";
									
									$searchParams = array(
										"size" => 32000,               // how many results *per shard* you want back
										"index" => "kubal",
										"type" => $restrictions,
										"body" => array(									
											"filter" => array(
												"term" => array("complete" => $pre_phone)
											)
										)
									);
									//echo json_encode($searchParams);
									
									try {
										//chamada ao server
										$results = $client->search($searchParams);						

										if (!empty($results['hits']['hits'])) {
											$generate_more_phone=true;
										}else{									
											if(count($final_phone_list<$size)){
												$final_phone_list[]= $pre_phone;										
											}
										}	
									} catch (Exception $e) {
										echo 'Caught exception: ',  $e->getMessage(), "\n";
									}
									$j++;
									//echo '<br>'.$j;
									
									set_time_limit(100);
								}
							}else{
								$final_phone_list=$pre_phone_list;
							}
							
						}while($generate_more_phones);
					
					// Your code

					$execution_time = microtime() - $execution_time;				

					$final_phones_file=implode (",\n", $final_phone_list);
					}
				}
				
				if($show_results){
			?>
					<input name="phones" id="phones" type="hidden" value="<?php echo $final_phones_file; ?>">
					<button type="submit">Download</button> <?php printf(' It took %.5f sec', $execution_time);?>
					<div align="justify" style="height:400px;overflow:auto;" ><?php echo $final_phones_file; ?></div>
			<?php
				}
			?>
			
			</form>
			
	<script src="js/index.js"></script>
	
	</div>
</body>

</html>
<?php

	include 'db_connection.php';
	
	$description = "Request for code ".$code.", size ".$size;
	
	if(isset($restrictions)){
		$description.=" with the following restrictions ".$restrictions;
	}
			
	$sql = "INSERT INTO History (description, type, reg_date, fk_user) VALUES ('".$description."','Request',now(),".$_SESSION["user_id"].")";
	echo $sql;
				
	if (mysqli_query($conn, $sql)) {
					//echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
?>