<?php

include '../vendor/autoload.php';

use \Mudde\Import\Import;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example Import</title>
</head>

<body>
    <?php

    echo '<pre>';

    try {
        
        $config = json_decode(file_get_contents('import.json'), true);

        $import = new Import($config);
        $import->init();

        foreach($import as $item) {
            $array = $item->getArrayCopy();
            var_dump($array['_mapped'] ?? $array);
        }

        $import->stop();
    } catch (Exception $e) {
        var_dump($e);
    }


    ?>
</body>

</html>