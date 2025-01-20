<?php
include_once 'conf_global.php';

require_once 'scrypt.php';

$GLOBALS[SVPUBLIC_KEY] = $kx_public_key;
$GLOBALS[SVPRIVATE_KEY] = $kx_private_key;

class ImScrypt extends Scrypt {
    public function __construct($init) {
        $this->init($init);
    }

    // params(int) 1: encryptor 2: decryptor 3: encryptor/decryptor
    protected function init($init){
        if ($init == 1) {
            $this->kx_init(1);
        } else if ($init == 2) {
            $this->kx_init(2);
            $this->kxsv[SVPUBLIC_KEY] = $GLOBALS[SVPUBLIC_KEY];
            $this->kxsv[SVPRIVATE_KEY] = $GLOBALS[SVPRIVATE_KEY];
        } else if ($init == 3) {
            $this->kx_init(3);
            $this->kxsv[SVPUBLIC_KEY] = $GLOBALS[SVPUBLIC_KEY];
            $this->kxsv[SVPRIVATE_KEY] = $GLOBALS[SVPRIVATE_KEY];
        }
    }

    public function getencurl_string($link){
        $ciphertext = $this->encrypt($link, $this->kxsv[SVPUBLIC_KEY]);
        $sgpair_key = $this->sign_detached($ciphertext);
        $enc_link   = "c={$ciphertext}&xa={$this->kxci[CIPUBLIC_KEY]}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";

        return $enc_link;
    }

    public function get_sgenc_string($msg, $public_key){
        $ciphertext = $this->encrypt($msg, $public_key);
        $sgpair_key = $this->sign_detached($ciphertext);
        $udata = "c={$ciphertext}&xa={$this->kxci[CIPUBLIC_KEY]}&xb={$this->kxsv[SVPUBLIC_KEY]}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";

        return $udata;
    }

    public function get_sgenc_object($msg, $public_key){
        $ciphertext = $this->encrypt($msg, $public_key);
        $sgpair_key = $this->sign_detached($ciphertext);

        $jdata['c'] = $ciphertext;
        $jdata['xa'] = $this->kxci[CIPUBLIC_KEY];
        $jdata['xb'] = $this->kxsv[SVPUBLIC_KEY];
        $jdata['sp'] = $sgpair_key['publickey'];
        $jdata['sg'] = $sgpair_key['signature'];
        
        return $jdata;
    }
}
?>