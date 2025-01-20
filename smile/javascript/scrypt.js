if (typeof Scrypt == "undefined") {
    var Scrypt = class Scrypt {
        constructor() {
            this.kxsv = {};
            this.kxci = {};
        }

        // params(int) 1: encrypt 2: decrypt 3: encrypt/decrypt
        kx_init(init){
            if (init == 1) {
                this.kx_cigen_keys();
            } else if (init == 2) {
                this.kx_svgen_keys();
            } else if (init == 3) {
                this.kx_cigen_keys();
                this.kx_svgen_keys();
            }
        }

        kx_cigen_keys(){
            let seed = sodium.randombytes_buf(sodium.crypto_kx_SEEDBYTES);
            let keypair = sodium.crypto_kx_seed_keypair(seed);

            this.kxci.hx_cipublic_key = sodium.to_hex(keypair.publicKey);
            this.kxci.hx_ciprivate_key = sodium.to_hex(keypair.privateKey);
        }

        kx_svgen_keys(){
            let seed = sodium.randombytes_buf(sodium.crypto_kx_SEEDBYTES);
            let keypair = sodium.crypto_kx_seed_keypair(seed);

            this.kxsv.hx_svpublic_key = sodium.to_hex(keypair.publicKey);
            this.kxsv.hx_svprivate_key = sodium.to_hex(keypair.privateKey);
        }

        // params(hexstring) return(string)
        kx_cisession_keys(svpublic_key){
            if (sodium == undefined || svpublic_key == null || svpublic_key == undefined) {
                return null;
            }

            let bi_svpublic_key = sodium.from_hex(svpublic_key);

            let bi_cipublic_key = sodium.from_hex(this.kxci.hx_cipublic_key);
            let bi_ciprivate_key = sodium.from_hex(this.kxci.hx_ciprivate_key);

            let shared_key = sodium.crypto_kx_client_session_keys(bi_cipublic_key, bi_ciprivate_key, bi_svpublic_key);
            let hx_shared_key = sodium.to_hex(shared_key.sharedRx) + sodium.to_hex(shared_key.sharedTx);

            return hx_shared_key;
        }

        // params(hexstring) return(string)
        kx_svsession_keys(cipublic_key){
            if (sodium == undefined || cipublic_key == null || cipublic_key == undefined) {
                return null;
            }

            let bi_cipublic_key = sodium.from_hex(cipublic_key);
            
            let bi_svpublic_key = sodium.from_hex(this.kxsv.hx_svpublic_key);
            let bi_svprivate_key = sodium.from_hex(this.kxsv.hx_svprivate_key);

            let shared_key = sodium.crypto_kx_server_session_keys(bi_svpublic_key, bi_svprivate_key, bi_cipublic_key);
            let hx_shared_key = sodium.to_hex(shared_key.sharedTx) + sodium.to_hex(shared_key.sharedRx);

            return hx_shared_key;
        }

        // params(string) return(hexstring)
        encrypt(plaintext, svpublic_key){
            let hx_shared_key = this.kx_cisession_keys(svpublic_key);
            let _a, _b;

            _a = 0;
            _b = _a + sodium.crypto_aead_chacha20poly1305_ietf_KEYBYTES * 2; 
            let k = hx_shared_key.substring(_a, _b);

            _a = _b;
            _b = _a + sodium.crypto_aead_chacha20poly1305_ietf_NPUBBYTES * 2;
            let nnc = hx_shared_key.substring(_a, _b);

            _a = _b;
            _b = _a + hx_shared_key.length;
            let ad = hx_shared_key.substring(_a, _b);

            let ciphertext = sodium.crypto_aead_chacha20poly1305_ietf_encrypt(plaintext, sodium.from_hex(ad), null, sodium.from_hex(nnc) , sodium.from_hex(k));
            
            return sodium.to_hex(ciphertext);
        }

        // params(hexstring) return(string)
        decrypt(ciphertext, cipublic_key){
            let hx_shared_key = this.kx_svsession_keys(cipublic_key);
            
            let _a, _b;

            _a = 0;
            _b = _a + sodium.crypto_aead_chacha20poly1305_ietf_KEYBYTES * 2; 
            let k = hx_shared_key.substring(_a, _b);

            _a = _b;
            _b = _a + sodium.crypto_aead_chacha20poly1305_ietf_NPUBBYTES * 2;
            let nnc = hx_shared_key.substring(_a, _b);

            _a = _b;
            _b = _a + hx_shared_key.length;
            let ad = hx_shared_key.substring(_a, _b);

            let bi_plaintext = sodium.crypto_aead_chacha20poly1305_ietf_decrypt(null, sodium.from_hex(ciphertext), sodium.from_hex(ad), sodium.from_hex(nnc) , sodium.from_hex(k));
            let plaintext = sodium.to_string(bi_plaintext);
            
            return plaintext;
        }

        sign_detached(msg){
            let seed = sodium.randombytes_buf(sodium.crypto_sign_SEEDBYTES);
            let keypair = sodium.crypto_sign_seed_keypair(seed);
            let public_key = keypair.publicKey;
            let private_key = keypair.privateKey;

            let signature = sodium.crypto_sign_detached(msg, private_key);

            return { pk: sodium.to_hex(public_key), sg: sodium.to_hex(signature) };
        }

        sign_verify(signature, msg, public_key){
            if (sodium.crypto_sign_verify_detached(sodium.from_hex(signature), msg, sodium.from_hex(public_key))){
                return true;
            } else {
                return false;
            }
        }

        get_sgenc_string(msg, public_key){
            let svpublic_key = public_key;
            let acipublic_key = this.kxci.hx_cipublic_key;
            let bcipublic_key = this.kxsv.hx_svpublic_key;
                    
            
            let cipher = this.encrypt(msg, svpublic_key);
            let signpair = this.sign_detached(cipher);

            let udata = 'c=' + cipher + '&xa=' + acipublic_key + '&xb=' + bcipublic_key + '&sp=' + signpair.pk + '&sg=' + signpair.sg;

            return udata;
        }

        get_sgenc_object(msg, public_key){
            let svpublic_key = public_key;
            let acipublic_key = this.kxci.hx_cipublic_key;
            let bcipublic_key = this.kxsv.hx_svpublic_key;
                    
            let cipher = this.encrypt(msg, svpublic_key);
            let signpair = this.sign_detached(cipher);

            let jdata = { 
                c: cipher, 
                xa: acipublic_key, 
                xb: bcipublic_key, 
                sp: signpair.pk, 
                sg: signpair.sg
            };

            return jdata;
        }

        get_sgdec_jsonobj(jmsg) {
            if (!this.sign_verify(jmsg.sg, jmsg.c, jmsg.sp)) {
                return 'Not valid signature!';
            }
            let jdata = this.decrypt(jmsg.c, jmsg.xa);
            return jdata;
        }
    }
}