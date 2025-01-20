<?php
    if (!function_exists('exec_rpt_enc_new')) {
        function exec_rpt_enc_new($ls_modul,$ls_nama_rpt, $ls_user_param, $tipe) {
            //echo $nc_rpt_user;exit;
            $tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
            global $username;
            global $gs_sid;

            global $nc_rpt_link;
            global $nc_rpt_user;
            global $nc_rpt_pass;
            global $nc_rpt_path;
            global $nc_rpt_sid;
            global $ipReportServer;

            global $smile_adapter_dl_ci_private_key;
            global $smile_adapter_dl_ci_public_key;
            global $smile_adapter_dl_sv_public_key;
            global $smile_adapter_dl_url;
            global $smile_adapter_dl_url_pdf;
            global $smile_adapter_dl_url_xls;
            
            // Override default report link dengan report link yang baru (encryption)
            if ($tipe == "PDF") {
                $encrypt_rpt_link = $smile_adapter_dl_url_pdf;
            } else {
                $encrypt_rpt_link = $smile_adapter_dl_url_xls;
            }
            // echo $ls_user_param;
            $path = $nc_rpt_path.$ls_modul;

            $report["link"]     = $nc_rpt_link;
            $report["user"]     = $nc_rpt_user;
            $report["password"] = $nc_rpt_pass;
            $report["sid"]      = $nc_rpt_sid;
            $report["path"]     = urlencode($path);
            $report["file"]     = $ls_nama_rpt;
            $report["param"] = str_replace(" ","%26",$ls_user_param);
            $report["param"] = str_replace("=","%3D",$report["param"]);

            $link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
            
            $bypass_report = array();
            $report_file = $report["file"];
            if (!in_array($report_file, $bypass_report)) {
                // compose payload
                $payload 		= array("URL" => $link_rpt_server);
                $payload_json 	= json_encode($payload);

                // encryption url
                $imscrypt 						= new ImScrypt(1);
                $imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
                $imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
                $ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
                $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
                $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
                $info 							= "RDF=".$report_file."|FN=fungsi_rpt_simple.exec_rpt_enc_new";
                $info 							= base64_encode($info);
                
                // get token
                $dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
                $link 		= $encrypt_rpt_link."q=".$dl_token->token;
            } else {
                $link = $report["link"].base64_encode($link_rpt_server);
            }

            return $link;
        }
    }
?>
