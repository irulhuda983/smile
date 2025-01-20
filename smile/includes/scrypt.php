<?php
define('SVPUBLIC_KEY', 'hx_svpublic_key');
define('SVPRIVATE_KEY', 'hx_svprivate_key');
define('CIPUBLIC_KEY', 'hx_cipublic_key');
define('CIPRIVATE_KEY', 'hx_ciprivate_key');

class Scrypt{
    public function __construct($init) {
        $this->kxci = array();
        $this->kxsv = array();
        $this->kx_init($init);
    }

    // params(int) 1: encrypt 2: decrypt 3: encrypt/decrypt
    protected function kx_init($init){
        if ($init == 1) {
            self::kx_cigen_keys();
        } else if ($init == 2) {
            self::kx_svgen_keys();
        } else if ($init == 3) {
            self::kx_cigen_keys();
            self::kx_svgen_keys();
        }
    }

    private function kx_cigen_keys(){
        $seed = random_bytes(SODIUM_CRYPTO_KX_SEEDBYTES);
        $keypair = sodium_crypto_kx_seed_keypair($seed);
        
        $this->kxci[CIPUBLIC_KEY] = sodium_bin2hex(sodium_crypto_kx_publickey($keypair));
        $this->kxci[CIPRIVATE_KEY] = sodium_bin2hex(sodium_crypto_kx_secretkey($keypair));
    }

    private function kx_svgen_keys(){
        $seed = random_bytes(SODIUM_CRYPTO_KX_SEEDBYTES);
        $keypair = sodium_crypto_kx_seed_keypair($seed);
        
        $this->kxsv[SVPUBLIC_KEY] = sodium_bin2hex(sodium_crypto_kx_publickey($keypair));
        $this->kxsv[SVPRIVATE_KEY] = sodium_bin2hex(sodium_crypto_kx_secretkey($keypair));
    }

    // params(hexstring) return(hextring)
    private function kx_cisession_keys($svpublic_key){
        $bi_svpublic_key = sodium_hex2bin($svpublic_key);

        $bi_cipublic_key = sodium_hex2bin($this->kxci[CIPUBLIC_KEY]);
        $bi_ciprivate_key = sodium_hex2bin($this->kxci[CIPRIVATE_KEY]);
        $bi_cikeypair = $bi_ciprivate_key . $bi_cipublic_key;
        $shared_key = sodium_crypto_kx_client_session_keys($bi_cikeypair, $bi_svpublic_key);
        
        $hx_cishared_key = sodium_bin2hex($shared_key[0]) . sodium_bin2hex($shared_key[1]);

        return $hx_cishared_key;
    }

    // params(hexstring) return(hextring)
    private function kx_svsession_keys($cipublic_key){
        $bi_cipublic_key = sodium_hex2bin($cipublic_key);

        $bi_svpublic_key = sodium_hex2bin($this->kxsv[SVPUBLIC_KEY]);
        $bi_svprivate_key = sodium_hex2bin($this->kxsv[SVPRIVATE_KEY]);
        $bi_svkeypair = $bi_svprivate_key . $bi_svpublic_key;
        $shared_key = sodium_crypto_kx_server_session_keys($bi_svkeypair, $bi_cipublic_key);
        
        $hx_svshared_key = sodium_bin2hex($shared_key[1]) . sodium_bin2hex($shared_key[0]);

        return $hx_svshared_key;
    }

    // params(string, hexstring) return(hexstring)
    public function encrypt($plaintext, $svpublic_key){
        try{
            $hx_shared_key = self::kx_cisession_keys($svpublic_key);
        
            $_a = 0;
            $_b = SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES * 2;
            $key = substr($hx_shared_key, $_a, $_b);

            $_a = $_a + $_b;
            $_b = SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES * 2;
            $nonce = substr($hx_shared_key, $_a, $_b);
            
            $_a = $_a + $_b;
            $_b = strlen($hx_shared_key) - $_a;
            $ad = substr($hx_shared_key, $_a, $_b);

            $ciphertext = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
                $plaintext,
                sodium_hex2bin($ad),
                sodium_hex2bin($nonce),
                sodium_hex2bin($key)
            );

            $hx_ciphertext = sodium_bin2hex($ciphertext);
            
            return $hx_ciphertext;
        } catch(Exception $e) {
            return null;
        }
    }

    // params(string, hexstring) return(string)
    public function decrypt($ciphertext, $cipublic_key){
        try{
            $hx_shared_key = self::kx_svsession_keys($cipublic_key);
        
            $_a = 0;
            $_b = SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES * 2;
            $key = substr($hx_shared_key, $_a, $_b);

            $_a = $_a + $_b;
            $_b = SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES * 2;
            $nonce = substr($hx_shared_key, $_a, $_b);
            
            $_a = $_a + $_b;
            $_b = strlen($hx_shared_key) - $_a;
            $ad = substr($hx_shared_key, $_a, $_b);

            $decrypted = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
                sodium_hex2bin($ciphertext),
                sodium_hex2bin($ad),
                sodium_hex2bin($nonce),
                sodium_hex2bin($key)
            );
            
            if ($decrypted === false) {
                $decrypted = null;
            }

            return $decrypted;
        } catch(Exception $e) {
            return null;
        }
    }

    public function sign_detached($msg){
        $seed = random_bytes(SODIUM_CRYPTO_SIGN_SEEDBYTES);
        $keypair = sodium_bin2hex(sodium_crypto_sign_seed_keypair($seed));
        $private = sodium_hex2bin(substr($keypair, 0, SODIUM_CRYPTO_SIGN_SECRETKEYBYTES * 2));
        $public = sodium_hex2bin(substr($keypair, SODIUM_CRYPTO_SIGN_SECRETKEYBYTES * 2, SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES * 2));
        
        $signature = sodium_crypto_sign_detached($msg, $private);
        $data['publickey'] = sodium_bin2hex($public);
        $data['signature'] = sodium_bin2hex($signature);

        return $data;
    }

    public function sign_verify($signature, $msg, $publickey){
        try {
            if (sodium_crypto_sign_verify_detached(sodium_hex2bin($signature), $msg, sodium_hex2bin($publickey))) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $e) {
            return false;
        }
        
    }
}
?>