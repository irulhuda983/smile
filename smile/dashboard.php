<?PHP
		// session_start();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
         }
		include_once('includes/connsql.php');
		include_once "includes/conf_global.php";
		include "includes/class_database.php";
		//ini_set(1, $display_errors); 
		//$DB = new Database($gs_DBUser, $gs_DBPass, $gs_DBName);

		$username = $_SESSION["USER"];
		$login["user"] = str_replace("'", "`", $_SESSION['NAMA']);
		$login["kantor"] = $_SESSION['KANTOR'];
		$login["kdkantor"] = $_SESSION['KDKANTOR'];
		$login["npk"] = $_SESSION['NPK'];
		$login["email"] = $_SESSION['EMAIL'];
		$login["ip"] = $_SESSION['IP'];
        $gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
        $ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;
        /*
        $sql = "SELECT * FROM SIJSTK.VW_KN_DASHBOARD_KEPESERTAAN WHERE KODE_KANTOR = '".$ls_kode_kantor."' ORDER BY KODE_KANTOR ASC";
        
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_nama_kantor = $row["NAMA_KANTOR"];
        $ls_perusahaan_target = number_format($row["TARGET_PENAMBAHAN_PRS_SETAHUN"], 0, ",", ".");
        $ls_perusahaan_realisasi = number_format($row["REALISASI_PENAMBAHAN_PRS"], 0, ",", ".");
        $ls_perusahaan_persentase_realisasitarget = $row["PERSENTASE_PERUSAHAAN"];
        $ls_perusahaan_target_aktif = number_format($row["TARGET_PRS_AKTIF"], 0, ",", ".");
        
        $ls_tk_target = number_format($row["TARGET_PENAMBAHAN_TK_SETAHUN"], 0, ",", ".");
        $ls_tk_realisasi = number_format($row["REALISASI_PENAMBAHAN_TK"], 0, ",", ".");
        $ls_tk_persentase_realisasitarget = $row["PERSENTASE_TK"];
        $ls_tk_target_aktif = number_format($row["TARGET_TK_AKTIF"], 0, ",", ".");
        
        $ls_iuran_target = number_format($row["TARGET_IURAN_SETAHUN"], 0, ",", ".");
        $ls_iuran_realisasi = number_format($row["REALISASI_IURAN"], 0, ",", ".");
        $ls_iuran_persentase_realisasitarget = $row["PERSENTASE_IURAN"];
		*/
        ?>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
        <div id="welcome" class="x-show-display" style="background-color: #f8f8f8;">
            <div style="text-align: center;padding-top: 35px">
                <!--
                <table valign="top" style="width: 100%; text-align: center;">
                    <tr valign="top">
                        <td colspan="3">
                            <div style="text-align: center;font-size: 24px;color:#646565;">
                                DASHBOARD KEPESERTAAN DESEMBER 2017
                            </div>
                            <div style="text-align: center;font-size:26px;color:#5488e9;padding-bottom: 30px">
                                <?php
                                echo $ls_nama_kantor;
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style="width: 33%;height: 220px; padding: 0 25px 25px 25px;">
                            <div style="width: 322px;height: 48px;background-color: #6197f5;line-height: 48px;font-weight:lighter;font-size: 14px;color:#fff;border: solid 1px #f6f7f8">
                                PENAMBAHAN PERUSAHAAN
                            </div>
                            <div class="clear"></div>
                            <div style="width: 322px;background-color: #fff;text-align: left;padding: 20px;border: solid 1px #f6f7f8" >
                                <div class="clear"></div>
                                <div style="font-size: 14px;font-weight:lighter ;color:#a2a2a2">
                                    REALISASI
                                </div>
                                <div class="clear"></div>
                                <div style="font-size: 26px;font-weight: bold;">
                                    <?php
                                    echo $ls_perusahaan_realisasi;
                                    ?>
                                </div>
                                <div class="clear"></div>
                                <div style="font-size: 16px;font-weight:lighter;color:#a2a2a2">

                                    <?php
                                    echo $ls_perusahaan_persentase_realisasitarget . "% dari target";
                                    ?>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div style="width: 322px;height: 65px;background-color: #fff;text-align: left;padding: 20px;border: solid 1px #f6f7f8">
                                <div style="float:left;">
                                    <span style="font-size: 14px;font-weight:lighter;color:#a2a2a2">PERUSAHAAN AKTIF</span>
                                    <br>
                                    <span style="font-size: 18px; font-weight: bold; color:#7d7d7d">
                                        <?php
                                        echo $ls_perusahaan_target_aktif;
                                        ?>
                                    </span>
                                </div>
                                <div class="clear"></div>
                                <div style="float:right;text-align: right">
                                    <span style="font-size: 14px;font-weight:lighter;color:#a2a2a2;">TARGET</span>
                                    <br>
                                    <span style="font-size: 18px; font-weight: bold; color:#7d7d7d;">
                                        <?php
                                        echo $ls_perusahaan_target;
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div style="width: 322px;height: 12px;background-color: #d5d9dc;">
                            </div>
                        </td>

                        <td style="width: 33%;height: 220px; padding: 0 25px 25px 25px;">
                            <div style="width: 322px;height: 48px;background-color: #6197f5;line-height: 48px;font-weight:lighter;font-size: 14px;color:#fff;border: solid 1px #f6f7f8">
                                PENAMBAHAN TENAGA KERJA
                            </div>
                            <div class="clear"></div>
                            <div style="width: 322px;background-color: #fff;text-align: left;padding: 20px;border: solid 1px #f6f7f8" >
                                <div class="clear"></div>
                                <div style="font-size: 14px;font-weight:lighter;color:#a2a2a2">
                                    REALISASI
                                </div>
                                <div class="clear"></div>
                                <div style="font-size: 26px;font-weight: bold;">
                                    <?php
                                        echo $ls_tk_realisasi;
                                    ?>
                                </div>
                                <div class="clear"></div>
                                <div style="font-size: 16px;font-weight:lighter;color:#a2a2a2">
                                    
                                    <?php
                                        echo $ls_tk_persentase_realisasitarget."% dari target";
                                    ?>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div style="width: 322px;height: 65px;background-color: #fff;text-align: left;padding: 20px;border: solid 1px #f6f7f8">
                                <div style="float:left;">
                                    <span style="font-size: 14px;font-weight:lighter;color:#a2a2a2">TK AKTIF</span>
                                    <br>
                                    <span style="font-size: 18px; font-weight: bold; color:#7d7d7d">
                                        <?php
                                            echo $ls_tk_target_aktif;
                                        ?>
                                    </span>
                                </div>
                                <div class="clear"></div>
                                <div style="float:right;text-align: right">
                                    <span style="font-size: 14px;font-weight:lighter;color:#a2a2a2;">TARGET</span>
                                    <br>
                                    <span style="font-size: 18px; font-weight: bold; color:#7d7d7d;">
                                        <?php
                                            echo $ls_tk_target;
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div style="width: 322px;height: 12px;background-color: #d5d9dc;">
                            </div>
                        </td>

                        <td style="width: 33%;height: 220px; padding: 0 25px 25px 25px;">
                            <div style="width: 322px;height: 48px;background-color: #6197f5;line-height: 48px;font-weight:lighter;font-size: 14px;color:#fff;border: solid 1px #f6f7f8">
                                PENAMBAHAN IURAN
                            </div>
                            <div class="clear"></div>
                            <div style="width: 322px;background-color: #fff;text-align: left;padding: 20px;border: solid 1px #f6f7f8" >
                                <div class="clear"></div>
                                <div style="font-size: 14px;font-weight:lighter;color:#a2a2a2">
                                    REALISASI
                                </div>
                                <div class="clear"></div>
                                <div style="font-size: 26px;font-weight: bold;">
                                    <?php
                                        echo $ls_iuran_realisasi;
                                    ?>
                                </div>
                                <div class="clear"></div>
                                <div style="font-size: 16px;font-weight:lighter;color:#a2a2a2">
                                    
                                    <?php
                                        echo $ls_iuran_persentase_realisasitarget."% dari target";
                                    ?>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div style="width: 322px;height: 65px;background-color: #fff;text-align: left;padding: 20px;border: solid 1px #f6f7f8">
                                <div style="float:left;">
                                </div>
                                <div class="clear"></div>
                                <div style="float:right;text-align: right">
                                    <span style="font-size: 14px;font-weight:lighter;color:#a2a2a2;">TARGET</span>
                                    <br>
                                    <span style="font-size: 18px; font-weight: bold; color:#7d7d7d;">
                                    <?php
                                        echo $ls_iuran_target;
                                    ?>
                                    </span>
                                </div>
                            </div>
                            <div style="width: 322px;height: 12px;background-color: #d5d9dc;">
                            </div>
                        </td>
                    </tr>
                </table>
            -->
                <div style="height:1024px;"></div>
            </div>
        </div>