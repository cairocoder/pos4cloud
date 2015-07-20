<?php

    require_once ("dbClass.php");
    
    class userClass {
    
        public $user_id;
        public $username;
        public $email;
        public $loc_id;
        public $job_id;
        public $user_type;
    
        public static function find_all() {
            $result = self::find_by_sql("SELECT * FROM users");
            return $result;
        }

        public static function getJobName($jobId) {
            global $db;
            $result = $db->query("SELECT `desc` FROM jobs WHERE job_id = {$jobId}");
            $row = $db->fetch_array($result);
            $jobName = $row['desc'];
            return $jobName;
        }

        public static function getMonth($date) {
            $getDate = date('m', $date);
            switch ($getDate) {
                case 1:
                    $month = "M1";
                    break;

                case 2:
                    $month = "M2";
                    break;
                
                case 3:
                    $month = "M3";
                    break;
                
                case 4:
                    $month = "M4";
                    break;
                
                case 5:
                    $month = "M5";
                    break;
                
                case 6:
                    $month = "M6";
                    break;
                
                case 7:
                    $month = "M7";
                    break;
                
                case 8:
                    $month = "M8";
                    break;
                
                case 9:
                    $month = "M9";
                    break;
                
                case 10:
                    $month = "M10";
                    break;
                
                case 11:
                    $month = "M11";
                    break;
                
                case 12:
                    $month = "M12";
                    break;
            }

            return $month;
        }

        public static function getBranchName($locId) {
            global $db;
            $result = $db->query("SELECT `desc` FROM locations WHERE loc_id = {$locId}");
            $row = $db->fetch_array($result);
            $branchName = $row['desc'];
            return $branchName;
        }

        public static function getBranchId($branchShortName) {
            global $db;
            $result = $db->query("SELECT loc_id FROM locations WHERE short_desc = '{$branchShortName}'");
            $row = $db->fetch_array($result);
            $branchId = $row['loc_id'];
            return $branchId;
        }

        public static function getBranchShortName($locId) {
            global $db;
            $result = $db->query("SELECT `short_desc` FROM locations WHERE loc_id = {$locId}");
            $row = $db->fetch_array($result);
            $branchShortName = $row['short_desc'];
            return $branchShortName;
        }

        public static function getAllBranches() {
            global $db;
            $result = $db->query("SELECT * FROM locations WHERE published = 1 ORDER BY `order`");
            while ($row = $db->fetch_array($result)) {
                $allBrances[$row['loc_id']] = $row['desc'];
            }
            return $allBrances;
        }

        public static function getAllBranchesShort() {
            global $db;
            $result = $db->query("SELECT * FROM locations WHERE published = 1 ORDER BY `order`");
            while ($row = $db->fetch_array($result)) {
                $allBrances[$row['loc_id']] = $row['short_desc'];
            }
            return $allBrances;
        }

        public static function getAllBranchesIds() {
            global $db;
            $result = $db->query("SELECT * FROM locations WHERE published = 1 ORDER BY `order`");
            while ($row = $db->fetch_array($result)) {
                $allBrances[$row['loc_id']] = $row['loc_id'];
            }
            return $allBrances;
        }

        public static function getAllDepts() {
            global $db;
            $result = $db->query("SELECT * FROM items_dept");
            while ($row = $db->fetch_array($result)) {
                $allDepts[$row['dept_id']] = $row['long_desc'];
            }
            return $allDepts;
        }

        public static function getAllSubDepts() {
            global $db;
            $result = $db->query("SELECT * FROM items_sub_dept");
            while ($row = $db->fetch_array($result)) {
                $allSubDepts[$row['sub_dept_id']] = $row['desc'];
            }
            return $allSubDepts;
        }

        public static function getAllAttrs() {
            global $db;
            $result = $db->query("SELECT * FROM items_attr");
            while ($row = $db->fetch_array($result)) {
                $allAttrs[$row['attr_id']] = $row['desc'];
            }
            return $allAttrs;
        }

        public static function getAllVends() {
            global $db;
            $result = $db->query("SELECT * FROM items_vendor");
            while ($row = $db->fetch_array($result)) {
                $allVends[$row['vend_id']] = $row['long_desc'];
            }
            return $allVends;
        }

        public static function getAllSeasons() {
            global $db;
            $result = $db->query("SELECT * FROM items_season");
            while ($row = $db->fetch_array($result)) {
                $allSeasons[$row['season_id']] = $row['desc'];
            }
            return $allSeasons;
        }

        public static function getAllGenders() {
            global $db;
            $result = $db->query("SELECT * FROM items_gender");
            while ($row = $db->fetch_array($result)) {
                $allGenders[$row['gender_id']] = $row['desc'];
            }
            return $allGenders;
        }

        public static function getAllYears() {
            global $db;
            $result = $db->query("SELECT DISTINCT YEAR(`date`) AS `year` FROM invoice_header");
            while ($row = $db->fetch_array($result)) {
                $allYears[] = $row['year'];
            }
            return $allYears;
        }

        public static function getAllDesc() {
            global $db;
            $result = $db->query("SELECT * FROM items_desc");
            while ($row = $db->fetch_array($result)) {
                $allDesc[$row['desc_id']] = $row['desc'];
            }
            return $allDesc;
        }

        public static function getAllZones() {
            global $db;
            $result = $db->query("SELECT * FROM wh_zones");
            while ($row = $db->fetch_array($result)) {
                $allZones[$row['zone_id']] = $row['desc'];
            }
            return $allZones;
        }

        public static function getSalesMen($locId = null) {
            global $db;
            $query = "SELECT emp_id, emp_name FROM employees
                      WHERE job_id = 28";

            if (!empty($locId))
            {
                $query .= " AND loc_id = {$locId}";
            }

            $result = $db->query($query);
            while ($row = $db->fetch_array($result)) {
                $salesMen[$row['emp_id']] = $row['emp_name'];
            }
            return $salesMen;
        }

        public static function getSalesMan($empId) {
            global $db;
            $result = $db->query("SELECT emp_name FROM employees
                                  WHERE emp_id = {$empId}");
            $row = $db->fetch_array($result);
            $salesMan = $row['emp_name'];
            return $salesMan;
        }

		public static function getReason($reasonId) {
            global $db;
            $result = $db->query("SELECT `desc` FROM reasons
                                  WHERE reason_id = {$reasonId}");
            $row = $db->fetch_array($result);
            $reason = $row['desc'];
            return $reason;
        }

        public static function getUserName($userId) {
            global $db;
            $result = $db->query("SELECT username FROM users
                                  WHERE user_id = {$userId}");
            $row = $db->fetch_array($result);
            $userName = $row['username'];
            return $userName;
        }

        public static function getItemName($itemId) {
            global $db;
            $result = $db->query("SELECT item_name FROM items
                                  WHERE item_id = {$itemId}");
            $row = $db->fetch_array($result);
            $itemName = $row['item_name'];
            return $itemName;
        }

        public static function getItemDept($itemId) {
            global $db;
            $result1  = $db->query("SELECT dept_id FROM items
                                   WHERE item_id = {$itemId}");
            $row1     = $db->fetch_array($result1);
            $deptId   = $row1['dept_id'];

            $result2  = $db->query("SELECT `desc` FROM items_dept
                                    WHERE dept_id = {$deptId}");
            $row2     = $db->fetch_array($result2);
            $itemDept = $row2['desc'];

            return $itemDept;
        }

        public static function getItemDesc($itemId) {
            global $db;
            $result1 = $db->query("SELECT dept_id FROM items
                                   WHERE item_id = {$itemId}");
            $row1 = $db->fetch_array($result1);
            $deptId = $row1['dept_id'];

            $result2 = $db->query("SELECT `long_desc` FROM items_dept
                                  WHERE dept_id = {$deptId}");
            $row2 = $db->fetch_array($result2);
            $itemDesc = $row2['long_desc'];

            return $itemDesc;
        }

        public static function getItemAttr($itemId) {
            global $db;
            $result1 = $db->query("SELECT attr_id FROM items
                                   WHERE item_id = {$itemId}");
            $row1 = $db->fetch_array($result1);
            $attrId = $row1['attr_id'];

            $result2 = $db->query("SELECT `desc` FROM items_attr
                                   WHERE attr_id = {$attrId}");
            $row2 = $db->fetch_array($result2);
            $itemAttr = $row2['desc'];

            return $itemAttr;
        }

        public static function getItemVend($itemId) {
            global $db;
            $result1 = $db->query("SELECT vend_id FROM items
                                   WHERE item_id = {$itemId}");
            $row1 = $db->fetch_array($result1);
            $vendId = $row1['vend_id'];

            $result2 = $db->query("SELECT `desc` FROM items_vendor
                                   WHERE vend_id = {$vendId}");
            $row2 = $db->fetch_array($result2);
            $itemVend = $row2['desc'];

            return $itemVend;
        }

        public static function getItemIntlCode($itemId) {
            global $db;
            $result1  = $db->query("SELECT intl_code_id FROM items
                                   WHERE item_id = {$itemId}");
            $row1     = $db->fetch_array($result1);
            $intlCode = $row1['intl_code_id'];

            $result2  = $db->query("SELECT `desc` FROM items_intl_code
                                   WHERE intl_code_id = {$intlCode}");
            $row2     = $db->fetch_array($result2);
            $itemIntlCode = $row2['desc'];

            return $itemIntlCode;
        }

        public static function getItemPrices($itemId) {
            global $db;
            $result     = $db->query("SELECT `msrp`, `rtp`, `item_cost` FROM items
                                      WHERE item_id = {$itemId}");
            $row        = $db->fetch_array($result);
            $itemPrices = array('msrp' => $row['msrp'],
                                'rtp'  => $row['rtp'],
                                'cost' => $row['item_cost']);

            return $itemPrices;
        }

        public static function getItemSizes($itemId) {
            global $db;
            $result       = $db->query("SELECT items_size.size_id, items_size.desc FROM `items_size`
                                        JOIN `items` ON items.dept_id = items_size.dept_id
                                        WHERE items.item_id = '{$itemId}'
                                        ORDER BY items_size.order ASC");
            while ($row = $db->fetch_array($result)) {
                $itemSizes[] = $row;
            }

            return $itemSizes;
        }

        public static function getItemSizeSales($itemId, $sizeId, $locId) {
            global $db;
            $result        = $db->query("SELECT SUM(`qty`) AS `qty` FROM invoice_detail
                                         WHERE item_id = '{$itemId}'
                                         AND size_id   = '{$sizeId}'
                                         AND loc_id    = '{$locId}'");
            $row           = $db->fetch_array($result);
            $itemSizeSales = $row['qty'];
            if (empty($itemSizeSales)) {
                $itemSizeSales = 0;
            }
            return $itemSizeSales;
        }

        public static function getItemSizeStock($itemId, $sizeId, $locId) {
            global $db;
            $result        = $db->query("SELECT SUM(`qty`) AS `qty` FROM warehouses
                                         WHERE item_id = '{$itemId}'
                                         AND size_id   = '{$sizeId}'
                                         AND wrhs_id   = '{$locId}'");
            $row           = $db->fetch_array($result);
            $itemSizeStock = $row['qty'];
            if (empty($itemSizeStock)) {
                $itemSizeStock = 0;
            }
            return $itemSizeStock;
        }

        public static function getItemSizeSlipStock($itemId, $sizeId, $locId) {
            global $db;
            $result           = $db->query("SELECT item_size_qty FROM saved_transfers
                                            WHERE item_id      = '{$itemId}'
                                            AND   size_id      = '{$sizeId}'
                                            AND   from_wrhs_id = '{$locId}'");
            $row              = $db->fetch_array($result);
            $temSizeSlipStock = $row['item_size_qty'];
            if (empty($temSizeSlipStock)) {
                $temSizeSlipStock = 0;
            }
            return $temSizeSlipStock;
        }

        public static function getItemTotalSales($itemId, $locId = null, $dateFrom = null, $dateTo = null) {
            global $db;
            $netSales    = 0;
            $totalSales  = 0;
            $totalReturn = 0;
            $query       = "SELECT invoice_detail.type, invoice_detail.invo_no, SUM(invoice_detail.qty) AS qty,
                            invoice_header.date
                            FROM invoice_detail
                            JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
                            AND  invoice_detail.loc_id = invoice_header.loc_id
                            WHERE invoice_detail.item_id = {$itemId}";
            if (!empty($locId))
            {
                $query .= " AND invoice_detail.loc_id = {$locId}";
            }
            $query     .= " GROUP BY invoice_detail.type";
            $result     = $db->query($query);

            while ($row = $db->fetch_array($result))
            {
                if (!empty($dateFrom) && !empty($dateTo)) {
                    if ($row['date'] >= $dateFrom && $row['date'] <= $dateTo)
                    {
                        if ($row['type'] == 1)
                        {
                            $netSales    += $row['qty'];
                            $totalSales  += $row['qty'];
                        } else {
                            $netSales    -= $row['qty'];
                            $totalReturn += $row['qty'];
                        }
                    }
                } else {
                    if ($row['type'] == 1)
                    {
                        $netSales    += $row['qty'];
                        $totalSales  += $row['qty'];
                    } else {
                        $netSales    -= $row['qty'];
                        $totalReturn += $row['qty'];
                    }
                }                
            }
            $salesReport = array("netSales"    => $netSales,
                                 "totalSales"  => $totalSales,
                                 "totalReturn" => $totalReturn);
            return $salesReport;
        }

        public static function getItemTotalSales2($itemId, $locId = null) {
            global $db;
            $query   = "SELECT SUM(IF(invoice_detail.type=1, invoice_detail.qty, -1*invoice_detail.qty)) AS `qty`
                        FROM `invoice_detail`
                        WHERE `item_id` = '{$itemId}'";

            if (!empty($locId))
            {
                $query  .= " AND loc_id = '{$locId}'";
            }
            $result      = $db->query($query);
            $row         = $db->fetch_array($result);
            $totalSales  = $row['qty'];

            return $totalSales;
        }

        public static function getItemTotalSales3($itemId, $locId = null, $month = null, $year, $dept, $season, $gender, $desc, $subDept, $brances) {
            global $db;
            $query   = "SELECT SUM(IF(invoice_detail.type=1, invoice_detail.qty, -1*invoice_detail.qty)) AS `qty` FROM `invoice_detail`
                        JOIN  invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
                        AND   invoice_detail.loc_id = invoice_header.loc_id
                        WHERE invoice_detail.item_id = '{$itemId}'";

            if (!empty($locId))
            {
                $query  .= " AND invoice_detail.loc_id = '{$locId}'";
            }

            if (!empty($month))
            {
                $query  .= " AND MONTH(invoice_header.date) IN ({$month})";
            }

            if (!empty($year))
            {
                $query  .= " AND YEAR(invoice_header.date) IN ({$year})";
            }

            if (!empty($dept))
            {
                $query  .= " AND items.dept_id IN ({$dept})";
            }

            if (!empty($season))
            {
                $query  .= " AND items.season_id IN ({$season})";
            }

            if (!empty($gender))
            {
                $query  .= " AND items.gender_id IN ({$gender})";
            }

            if (!empty($desc))
            {
                $query  .= " AND items.desc_id IN ({$desc})";
            }

            if (!empty($subDept))
            {
                $query  .= " AND items.sub_dept_id IN ({$subDept})";
            }

            if (!empty($$brances))
            {
                $query  .= " AND invoice_detail.loc_id IN ({$brances})";
            }

            $result      = $db->query($query);
            $row         = $db->fetch_array($result);
            $totalSales  = $row['qty'];

            return $totalSales;
        }

        public static function getItemTotalSalesValue($itemId, $locId = null) {
            global $db;
            $query   = "SELECT SUM(invoice_detail.rtp * invoice_detail.qty) AS `rtp`
                        FROM `invoice_detail`
                        WHERE `item_id` = '{$itemId}'";

            if (!empty($locId))
            {
                $query  .= " AND loc_id = '{$locId}'";
            }
            $result      = $db->query($query);
            $row         = $db->fetch_array($result);
            $totalSales  = $row['rtp'];
            
            return $totalSales;
        }

        public static function getItemTotalSalesValue2($itemId, $locId = null, $month = null, $year, $dept, $season, $gender, $desc, $subDept, $brances) {
            global $db;
            $query   = "SELECT SUM(invoice_detail.rtp * invoice_detail.qty) AS `rtp`
                        FROM `invoice_detail`
                        JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
                        AND  invoice_detail.loc_id = invoice_header.loc_id
                        WHERE invoice_detail.item_id = '{$itemId}'";

            if (!empty($locId))
            {
                $query  .= " AND invoice_detail.loc_id = '{$locId}'";
            }

            if (!empty($month))
            {
                $query  .= " AND MONTH(invoice_header.date) IN ({$month})";
            }

            if (!empty($year))
            {
                $query  .= " AND YEAR(invoice_header.date) IN ({$year})";
            }

            if (!empty($dept))
            {
                $query  .= " AND items.dept_id IN ({$dept})";
            }

            if (!empty($season))
            {
                $query  .= " AND items.season_id IN ({$season})";
            }

            if (!empty($gender))
            {
                $query  .= " AND items.gender_id IN ({$gender})";
            }

            if (!empty($desc))
            {
                $query  .= " AND items.desc_id IN ({$desc})";
            }

            if (!empty($subDept))
            {
                $query  .= " AND items.sub_dept_id IN ({$subDept})";
            }

            if (!empty($$brances))
            {
                $query  .= " AND invoice_detail.loc_id IN ({$brances})";
            }

            $result      = $db->query($query);
            $row         = $db->fetch_array($result);
            $totalSales  = $row['rtp'];
            
            return number_format($totalSales);
        }

        public static function getItemTotalStock($itemId, $locId = null, $dept, $season, $gender, $desc, $subDept, $brances) {
            global $db;
            $query   = "SELECT SUM(warehouses.qty) AS `qty` FROM `warehouses`
                        JOIN   items ON warehouses.item_id = items.item_id
                        WHERE  warehouses.item_id = '{$itemId}'";
                        
            if (!empty($locId))
            {
                $query  .= " AND warehouses.wrhs_id = {$locId}";
            }

            if (!empty($dept))
            {
                $query  .= " AND items.dept_id IN ({$dept})";
            }

            if (!empty($season))
            {
                $query  .= " AND items.season_id IN ({$season})";
            }

            if (!empty($gender))
            {
                $query  .= " AND items.gender_id IN ({$gender})";
            }

            if (!empty($desc))
            {
                $query  .= " AND items.desc_id IN ({$desc})";
            }

            if (!empty($subDept))
            {
                $query  .= " AND items.sub_dept_id IN ({$subDept})";
            }

            if (!empty($$brances))
            {
                $query  .= " AND warehouses.wrhs_id IN ({$brances})";
            }

            $result      = $db->query($query);
            $row         = $db->fetch_array($result);
            $totalStock  = $row['qty'];

            return $totalStock;
        }

        public static function getItemTotalStockValue($itemId, $locId = null, $dept, $season, $gender, $desc, $subDept, $brances) {
            global $db;
            $query   = "SELECT SUM(warehouses.qty * items.rtp) AS `totalValue` FROM `warehouses`
                        JOIN items ON warehouses.item_id = items.item_id
                        WHERE warehouses.item_id = '{$itemId}'";
                        
            if (!empty($locId))
            {
                $query  .= " AND warehouses.wrhs_id = {$locId}";
            }

            if (!empty($dept))
            {
                $query  .= " AND items.dept_id IN ({$dept})";
            }

            if (!empty($season))
            {
                $query  .= " AND items.season_id IN ({$season})";
            }

            if (!empty($gender))
            {
                $query  .= " AND items.gender_id IN ({$gender})";
            }

            if (!empty($desc))
            {
                $query  .= " AND items.desc_id IN ({$desc})";
            }

            if (!empty($subDept))
            {
                $query  .= " AND items.sub_dept_id IN ({$subDept})";
            }

            if (!empty($$brances))
            {
                $query  .= " AND warehouses.wrhs_id IN ({$brances})";
            }
            
            $result      = $db->query($query);
            $row         = $db->fetch_array($result);
            $totalStock  = $row['totalValue'];

            return number_format($totalStock);
        }

        public static function getTypeTotalSales($type, $type_id, $locId = null, $month = null, $year, $dept, $season, $gender, $desc, $subDept, $brances) {
            global $db;
            $query   = "SELECT SUM(IF(invoice_detail.type=1, invoice_detail.qty, -1*invoice_detail.qty)) AS `qty` FROM `invoice_detail`
                        JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
                        AND  invoice_detail.loc_id = invoice_header.loc_id
                        JOIN items ON invoice_detail.item_id = items.item_id
                        WHERE items.".$type." = '{$type_id}'";

            if (!empty($locId))
            {
                $query  .= " AND invoice_detail.loc_id = '{$locId}'";
            }

            if (!empty($month))
            {
                $query  .= " AND MONTH(invoice_header.date) in ({$month})";
            }

            if (!empty($year))
            {
                $query  .= " AND YEAR(invoice_header.date) in ({$year})";
            }

            if (!empty($dept))
            {
                $query  .= " AND items.dept_id IN ({$dept})";
            }

            if (!empty($season))
            {
                $query  .= " AND items.season_id IN ({$season})";
            }

            if (!empty($gender))
            {
                $query  .= " AND items.gender_id IN ({$gender})";
            }

            if (!empty($desc))
            {
                $query  .= " AND items.desc_id IN ({$desc})";
            }

            if (!empty($subDept))
            {
                $query  .= " AND items.sub_dept_id IN ({$subDept})";
            }

            if (!empty($$brances))
            {
                $query  .= " AND invoice_detail.loc_id IN ({$brances})";
            }
            
            $result      = $db->query($query);
            $row         = $db->fetch_array($result);
            $totalSales  = $row['qty'];

            return $totalSales;
        }

        public static function getTypeTotalSalesValue($type, $type_id, $locId = null, $month = null, $year, $dept, $season, $gender, $desc, $subDept, $brances) {
            global $db;
            $query   = "SELECT SUM(invoice_detail.rtp * invoice_detail.qty) AS `rtp`
                        FROM `invoice_detail`
                        JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
                        AND  invoice_detail.loc_id = invoice_header.loc_id
                        JOIN items ON invoice_detail.item_id = items.item_id
                        WHERE items.".$type." = '{$type_id}'";

            if (!empty($locId))
            {
                $query  .= " AND invoice_detail.loc_id = '{$locId}'";
            }

            if (!empty($month))
            {
                $query  .= " AND MONTH(invoice_header.date) in ({$month})";
            }

            if (!empty($year))
            {
                $query  .= " AND YEAR(invoice_header.date) in ({$year})";
            }

            if (!empty($dept))
            {
                $query  .= " AND items.dept_id IN ({$dept})";
            }

            if (!empty($season))
            {
                $query  .= " AND items.season_id IN ({$season})";
            }

            if (!empty($gender))
            {
                $query  .= " AND items.gender_id IN ({$gender})";
            }

            if (!empty($desc))
            {
                $query  .= " AND items.desc_id IN ({$desc})";
            }

            if (!empty($subDept))
            {
                $query  .= " AND items.sub_dept_id IN ({$subDept})";
            }

            if (!empty($$brances))
            {
                $query  .= " AND invoice_detail.loc_id IN ({$brances})";
            }
            
            $result      = $db->query($query);
            $row         = $db->fetch_array($result);
            $totalSales  = $row['rtp'];
            
            return number_format($totalSales);
        }

        public static function getTypeTotalStock($type, $type_id, $locId = null, $dept, $season, $gender, $desc, $subDept, $brances) {
            global $db;
            $query   = "SELECT SUM(`qty`) AS `qty` FROM `warehouses`
                        JOIN items ON warehouses.item_id = items.item_id
                        WHERE items.".$type." = '{$type_id}'";
                        
            if (!empty($locId))
            {
                $query  .= " AND wrhs_id = {$locId}";
            }

            if (!empty($dept))
            {
                $query  .= " AND items.dept_id IN ({$dept})";
            }

            if (!empty($season))
            {
                $query  .= " AND items.season_id IN ({$season})";
            }

            if (!empty($gender))
            {
                $query  .= " AND items.gender_id IN ({$gender})";
            }

            if (!empty($desc))
            {
                $query  .= " AND items.desc_id IN ({$desc})";
            }

            if (!empty($subDept))
            {
                $query  .= " AND items.sub_dept_id IN ({$subDept})";
            }

            if (!empty($$brances))
            {
                $query  .= " AND warehouses.wrhs_id IN ({$brances})";
            }

            $result      = $db->query($query);
            $row         = $db->fetch_array($result);
            $totalStock  = $row['qty'];

            return $totalStock;
        }

        public static function getTypeTotalStockValue($type, $type_id, $locId = null, $dept, $season, $gender, $desc, $subDept, $brances) {
            global $db;
            $query   = "SELECT SUM(warehouses.qty * items.rtp) AS `totalValue` FROM `warehouses`
                        JOIN items ON warehouses.item_id = items.item_id
                        WHERE items.".$type." = '{$type_id}'";
                        
            if (!empty($locId))
            {
                $query  .= " AND warehouses.wrhs_id = {$locId}";
            }

            if (!empty($dept))
            {
                $query  .= " AND items.dept_id IN ({$dept})";
            }

            if (!empty($season))
            {
                $query  .= " AND items.season_id IN ({$season})";
            }

            if (!empty($gender))
            {
                $query  .= " AND items.gender_id IN ({$gender})";
            }

            if (!empty($desc))
            {
                $query  .= " AND items.desc_id IN ({$desc})";
            }

            if (!empty($subDept))
            {
                $query  .= " AND items.sub_dept_id IN ({$subDept})";
            }

            if (!empty($$brances))
            {
                $query  .= " AND warehouses.wrhs_id IN ({$brances})";
            }
            
            $result      = $db->query($query);
            $row         = $db->fetch_array($result);
            $totalStock  = $row['totalValue'];

            return number_format($totalStock);
        }

        public static function getItemLocation($itemId, $locId) {
            global $db;
            $query   = "SELECT wh_locations.desc AS loc FROM wh_locations
                        JOIN wh_item_location ON wh_locations.loc_id = wh_item_location.loc_id
                        WHERE wh_item_location.item_id = {$itemId}
                        AND   wh_item_location.main_loc_id = {$locId}";
            $result       = $db->query($query);
            $row          = $db->fetch_array($result);
            $itemLocation = $row['loc'];

            return $itemLocation;
        }

        public static function getCustomerName($custId) {
            global $db;
            $result = $db->query("SELECT cust_name FROM customers
                                  WHERE cust_id = {$custId}");
            $row = $db->fetch_array($result);
            $custName = $row['cust_name'];
            return $custName;
        }

        public static function getCustomerTel($custId) {
            global $db;
            $result = $db->query("SELECT cust_tel FROM customers
                                  WHERE cust_id = {$custId}");
            $row = $db->fetch_array($result);
            $custTel = $row['cust_tel'];
            return $custTel;
        }

        public static function getCustomerId($custTel) {
            global $db;
            $result = $db->query("SELECT cust_id FROM customers
                                  WHERE cust_tel = {$custTel}");
            $row = $db->fetch_array($result);
            $custId = $row['cust_id'];
            return $custId;
        }

        public static function getSizeDesc($sizeId) {
            global $db;
            $result = $db->query("SELECT `desc` FROM items_size
                                  WHERE size_id = {$sizeId}");
            $row = $db->fetch_array($result);
            $sizeDesc = $row['desc'];
            return $sizeDesc;
        }

        public static function getZoneName($zoneId) {
            global $db;
            $result = $db->query("SELECT `desc` FROM wh_zones WHERE zone_id = {$zoneId}");
            $row = $db->fetch_array($result);
            $zoneName = $row['desc'];
            return $zoneName;
        }

        public static function getLocName($locId) {
            global $db;
            $result = $db->query("SELECT `desc` FROM wh_locations WHERE loc_id = {$locId}");
            $row = $db->fetch_array($result);
            $locName = $row['desc'];
            return $locName;
        }
        
        public static function find_by_id($id=0) {
            global $db;
            $result = self::find_by_sql("SELECT * FROM users WHERE id = {$id} LIMIT 1");
            return !empty($result) ? array_shift($result) : false;
        }
        
        public static function find_by_sql($sql="") {
            global $db;
            $result = $db->query($sql);
            $object_array = array();
            while ($row = $db->fetch_array($result)) {
                $object_array[] = self::instantiate($row);
            }
            return $object_array;
        }
        
        private static function instantiate($record) {
            $object = new self;
            foreach ($record as $attribute=>$value) {
                if ($object->has_attribute($attribute)) {
                    $object->$attribute = $value;
                }
            }
            return $object;
        }
        
        private function has_attribute($attribute) {
            $object_vars = get_object_vars($this);
            return array_key_exists($attribute, $object_vars);
        }
        
        public static function authenticate($email="", $password="") {
            global $db;
            $email = $db->escape_value($email);
            $password = $db->escape_value($password);
        
            $sql  = "SELECT * FROM users ";
            $sql .= "WHERE email = '{$email}' ";
            $sql .= "AND password = '{$password}' ";
            $sql .= "LIMIT 1";
            $result = self::find_by_sql($sql);
            return !empty($result) ? array_shift($result) : false;
        }
    
    }

?>
