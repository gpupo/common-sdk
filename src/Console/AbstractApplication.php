<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/common-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\CommonSdk\Console;

use Gpupo\Common\Console\AbstractApplication as Core;
use Gpupo\CommonSchema\TranslatorDataCollection;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractApplication extends Core
{
    public function displayOrderList(TranslatorDataCollection $collection, OutputInterface $output)
    {
        if (0 === $collection->count()) {
            return $output->writeln('<info>Nenhum pedido para exibir</info>');
        }

        return $this->displayTableResults($output, $collection->toArray(), [
            'merchant', 'orderNumber', 'acceptedOffer', 'orderDate',
            'customer', 'billingAddress', 'quantity', 'freight', 'total',
        ], 49, true);
    }
}
