<?php
//. sample1.php
//. 以下の Insights for Twitter サービスの接続情報を自身の環境に併せて編集する

//. 
$i4t_apiurl = 'https://USERNAME:PASSWORD@cdeservice.mybluemix.net';
?>

<html>
<head>
<title>Insights for Twitter in PHP</title>
</head>
<body>
<?php
$vcap = getenv( 'VCAP_SERVICES' );
if( $vcap ){
  $bluemixenv = json_decode( $vcap );
  $i4t_apiurl = $bluemixenv->twitterinsights[0]->credentials->url;
}
if( isset( $_GET['q'] ) ){
  $q = $_GET['q'];
  if( $q ){
?>
<h1><?php echo( $q ); ?></h1>
<table border='1'>
<?php
    $i4t_url = $i4t_apiurl . '/api/v1/messages/count?q=' . $q . '%20sentiment%3a';
    $sentiments = array( 'positive', 'neutral', 'ambivalent', 'negative' );
    foreach( $sentiments as $sentiment ){
      $url = $i4t_url . $sentiment;
      $text = file_get_contents( $url );
      $json = json_decode( $text );
      $results = $json->search->results;
?>
<tr>
 <td><?php echo( $sentiment ); ?></td>
 <td><?php echo( $results ); ?></td>
</tr>
<?php
    }
?>
</table>
<?php
  }
}
?>

</body>
</html>



