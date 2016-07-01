<?php

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
 */

$path = 'src/';
include 'vendor/autoload.php';

$fqcns = [];

$allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
$phpFiles = new RegexIterator($allFiles, '/\.php$/');
foreach ($phpFiles as $phpFile) {
    $content = file_get_contents($phpFile->getRealPath());
    $tokens = token_get_all($content);
    $namespace = '';
    for ($index = 0; isset($tokens[$index]); ++$index) {
        if (!isset($tokens[$index][0])) {
            continue;
        }
        if (T_NAMESPACE === $tokens[$index][0]) {
            $index += 2;
            while (isset($tokens[$index]) && is_array($tokens[$index])) {
                $namespace .= $tokens[$index++][1];
            }
        }
        if (T_CLASS === $tokens[$index][0]) {
            $index += 2;
            $fqcns[] = $namespace.'\\'.$tokens[$index][1];
        }
    }
}

foreach ($fqcns as $item) {
    //Gpupo\NetshoesSdk\Entity\Product\Sku\Status;
    $testCase = '\\'.str_replace('Gpupo\\', 'Gpupo\\Tests\\', $item).'Test';

    if (!class_exists($testCase) && false === strpos($testCase, 'Abstract') && false === strpos($testCase, 'Command')) {
        echo "vendor/bin/phpunit --stderr vendor/gpupo/common-sdk/bin/TestCaseGenerator.php '".$item."'; # $testCase\n";
    }
}
