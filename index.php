<?php
require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GuzzleHttp and Monolog</title>
</head>
<body>
    <form style="width: 300px;margin: auto" action="<?= $_SERVER['PHP_SELF']?>" method="post">
       URI <input name="uri" />
        <input type="submit" value="Submit" />
    </form>
<?php
if(isset($_POST['uri'])) {

    try {
        $client = new Client(['base_uri' => $_POST['uri']]);
        $response = $client->request('GET');

        $body = $response->getBody();
        $contentSize = $body->getSize();
        echo $body;

        $logger = new Logger('client');
        $logger->pushHandler(new StreamHandler('guzzle.log'));

        $logger->info("Downloaded $contentSize bytes information from {$_POST['uri']}");

        foreach ($response->getHeaders() as $name => $values) {
            $logger->info($name . ': ' . implode(', ', $values));
        }
    } catch (RequestException $e) {
        echo Psr7\str($e->getRequest());
        if ($e->hasResponse()) {
            echo Psr7\str($e->getResponse());
        }
    }
}
?>
</body>
</html>

