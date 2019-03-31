<?php
    include("./simplehtmldom_1_8_1/simple_html_dom.php");
    
    $convert_base_url = 'https://youtubemp3music.info/@api/json/mp3/';
    $download_dir = date('Ymd');
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://asiapop40.com");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $temp = curl_exec($ch);
    $elem = str_get_html($temp)->find('div[class=track-video]');
    
    mkdir($download_dir, 0755);
    chdir($download_dir);

    for ($i=0; $i<count($elem); $i++) {
        $url = $convert_base_url . $elem[$i]->{'data-video-id'};
        
        curl_setopt($ch, CURLOPT_URL, $url);
        $ret = curl_exec($ch);
        $json_obj = json_decode($ret);
        echo "Now downloading: " . $json_obj->vidTitle . "\n";
        $file = fopen ('https:' . $json_obj->vidInfo->{'0'}->dloadUrl, "rb");
        if ($file) {
            $new_file = fopen($json_obj->vidTitle . ".mp3", "wb");
            if ($new_file) {
                while(!feof($file)) {
                    fwrite($new_file, fread($file, 1024 * 8 ), 1024 * 8 );
                }
            }
        }
        if ($file) {
            fclose($file);
        }

        if ($new_file) {
            fclose($new_file);
        }
    }

    chdir('../');
    curl_close($ch);
?>
