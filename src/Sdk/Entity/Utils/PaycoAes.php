<?php

namespace Epayco\Woocommerce\Sdk\Entity\Utils;


/**
 * Epayco library encrypt based in AES
 */
if (function_exists('mcrypt_get_iv_size')) {

    class PaycoAes extends McryptEncrypt {}
}else{

    class PaycoAes extends OpensslEncrypt {}
}

?>