cd /home/elwilis/tps/services
#php -q index.php/send_laporan
wget -O - http://103.84.194.194/tps_client/index.php/scheduler/get_permit/getimpor_sppb --no-check-certificate
wget -O - http://103.84.194.194/tps_client/index.php/scheduler/read_permit/sppb --no-check-certificate