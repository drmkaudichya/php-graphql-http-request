<?php
function graphql_query(string $endpoint, string $query, array $variables = [], ?string $token = null): array
{
	//$headers = array();
    //$headers = ['Content-Type: application/json', 'Accept: application/json'];
	$headers = array();
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Accept: application/graphql';
	$headers[] = 'Host: example.com'; //Hostname here
    if (null !== $token) {
        $headers[] = "Authorization: Bearer $token";
    }
	//print_r($headers);
	//echo "<br/>";
	
    if (false === $data = @file_get_contents($endpoint, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode(['query' => $query, 'variables' => $variables]),
        ]
    ]))) {
        $error = error_get_last();
        throw new \ErrorException($error['message'], $error['type']);
    }
    return json_decode($data, true);
}



$query = 'query login{}'; //GraphQL Query Here

$data = graphql_query('https://example.com/graphql', $query, [], null);
//print_r($data);

$token = $data['data']['Oauth']['access_tokens'][0]['access_token'];
//echo $token;

$query = 'query apps {
  Apps{
    id,
    name
  }
}';

$data = graphql_query('https://example.com/graphql', $query, [], $token);
print_r($data);

?>
