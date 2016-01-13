<?php
//. sample2.php
//. 以下の Insights for Twitter サービスの接続情報を自身の環境に併せて編集する

//. 
$i4t_apiurl = 'https://USERNAME:PASSWORD@cdeservice.mybluemix.net';
?>

<html>
<head>
<title>Insights for Twitter in PHP</title>
<script src="//www.google.com/jsapi"></script>
<script src="//code.jquery.com/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
$(function(){
  var data = google.visualization.arrayToDataTable([
    [ 'Sentiment', '#' ]
<?php
$vcap = getenv( 'VCAP_SERVICES' );
if( $vcap ){
  $bluemixenv = json_decode( $vcap );
  $i4t_apiurl = $bluemixenv->twitterinsights[0]->credentials->url;
}
if( isset( $_GET['q'] ) ){
  $q = $_GET['q'];
  if( $q ){
    $i4t_url = $i4t_apiurl . '/api/v1/messages/count?q=' . $q . '%20sentiment%3a';
    $sentiments = array( 'positive', 'neutral', 'ambivalent', 'negative' );
    foreach( $sentiments as $sentiment ){
      $url = $i4t_url . $sentiment;
      $text = file_get_contents( $url );
      $json = json_decode( $text );
      $results = $json->search->results;
?>
    ,[ '<?php echo( $sentiment ); ?>', <?php echo( $results ); ?> ]
<?php } } } ?>
  ]);
  var options = { title: '<?php echo( $q ); ?>', piHole: 0.4 };
  var chart = new google.visualization.PieChart( document.getElementById('donutchart') );
  chart.draw( data, options );
});
</script>
</head>
<body>
<h1><?php echo( $q ); ?></h1>
<div id='donutchart' style='width:100%; height:90%;'></div>
</body>
</html>



