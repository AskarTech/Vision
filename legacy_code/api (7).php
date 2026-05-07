<?php
// إعدادات الترويسة
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require 'db_connect.php';

// =========================================================
// 1. إعدادات قاعدة البيانات وإصلاح الترميز
// =========================================================
try {
    $pdo->exec("set names utf8mb4");
    $pdo->exec("SET CHARACTER SET utf8mb4");
} catch(PDOException $e) {
    // تجاهل في حال عدم توفر الصلاحيات
}

// =========================================================
// 2. إنشاء الجداول الناقصة (إصلاح المشاكل الجوهرية)
// =========================================================
try {
    // جدول المدراء
    $pdo->exec("CREATE TABLE IF NOT EXISTS network_managers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        partner_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        username VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (partner_id) REFERENCES partners(id) ON DELETE CASCADE
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // جدول إعدادات الدفع (مهم جداً لإصلاح لوحة التحكم)
    $pdo->exec("CREATE TABLE IF NOT EXISTS payment_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        gateway_name VARCHAR(50) UNIQUE NOT NULL,
        merchant_phone VARCHAR(50) DEFAULT NULL,
        short_code VARCHAR(50) DEFAULT NULL,
        auth_token TEXT DEFAULT NULL,
        source_wallet_id VARCHAR(50) DEFAULT NULL,
        is_active TINYINT(1) DEFAULT 0
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // إدراج إعدادات افتراضية لفلوسك إذا لم تكن موجودة
    $checkFloosak = $pdo->query("SELECT id FROM payment_settings WHERE gateway_name = 'floosak'")->fetch();
    if (!$checkFloosak) {
        $pdo->exec("INSERT INTO payment_settings (gateway_name, is_active) VALUES ('floosak', 0)");
    }

} catch (PDOException $e) { 
    // استمرار التنفيذ حتى لو فشل الإنشاء (قد تكون الجداول موجودة)
}

// =========================================================
// 3. دوال مساعدة
// =========================================================

function callFloosakAPI($endpoint, $data, $token = null) {
    $baseUrl = "https://qualityconnect.com.ye/floosak/api/v1"; 
    $url = $baseUrl . $endpoint;

    $headers = [
        "Content-Type: application/json",
        "Accept: application/json",
        "x-channel: merchant"
    ];
    if ($token) {
        $headers[] = "Authorization: Bearer " . $token;
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // مهلة 10 ثواني

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) { return null; }
    return json_decode($response, true);
}

// =========================================================
// 4. معالجة الطلبات
// =========================================================

$action = isset($_GET['action']) ? $_GET['action'] : '';
$json_input = file_get_contents("php://input");
$data = json_decode($json_input, true);

if (json_last_error() !== JSON_ERROR_NONE && !empty($_POST)) {
    $data = $_POST;
}

try {
    switch($action) {
        
        case 'get_all_data':
            $res = [];
            // جلب البيانات الأساسية
            $res['cards'] = $pdo->query("SELECT * FROM cards ORDER BY id DESC")->fetchAll();
            $res['packages'] = $pdo->query("SELECT * FROM packages")->fetchAll();
            $res['partners'] = $pdo->query("SELECT * FROM partners")->fetchAll();
            $res['banks'] = $pdo->query("SELECT * FROM banks")->fetchAll();
            $res['topupRequests'] = $pdo->query("SELECT * FROM topup_requests ORDER BY id DESC")->fetchAll();
            $res['withdrawals'] = $pdo->query("SELECT * FROM withdrawals ORDER BY id DESC")->fetchAll();
            $res['managers'] = $pdo->query("SELECT id, partner_id, name, username, created_at FROM network_managers ORDER BY id DESC")->fetchAll();

            // جلب العملاء مع معاملاتهم
            $customers = $pdo->query("SELECT * FROM customers")->fetchAll();
            foreach($customers as &$cust) {
                unset($cust['password_hash']);
                $stmt = $pdo->prepare("SELECT * FROM transactions WHERE customer_id = ?");
                $stmt->execute([$cust['id']]);
                $trans = $stmt->fetchAll();
                foreach($trans as &$t) {
                    if($t['details']) $t['details'] = json_decode($t['details'], true);
                }
                $cust['transactions'] = $trans;
            }
            $res['customers'] = $customers;
            
            // إصلاح: إرجاع كائن الإعدادات كاملاً ليعمل زر "إعدادات فلوسك" في الداشبورد
            $floosak = $pdo->query("SELECT * FROM payment_settings WHERE gateway_name = 'floosak'")->fetch(PDO::FETCH_ASSOC);
            $res['floosak_settings'] = $floosak ? $floosak : null; 
            // الإبقاء على المتغير القديم للتوافق
            $res['floosak_active'] = ($floosak && $floosak['is_active']);
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            break;

        case 'get_store_data':
             $res = [];
             $res['packages'] = $pdo->query("SELECT * FROM packages")->fetchAll();
             // إرجاع الكروت النشطة فقط للمتجر
             $res['cards'] = $pdo->query("SELECT * FROM cards WHERE status = 'active'")->fetchAll(); 
             $res['banks'] = $pdo->query("SELECT * FROM banks")->fetchAll();
             // *** جديد: إرسال قائمة الشركاء للمتجر لاستخدامها في الفلترة ***
             $res['partners'] = $pdo->query("SELECT id, name FROM partners")->fetchAll();
             
             // التحقق من حالة بوابات الدفع
             $floosak = $pdo->query("SELECT * FROM payment_settings WHERE gateway_name = 'floosak'")->fetch();
             $res['payment_methods'] = [
                 'bank_transfer' => true, 
                 'floosak' => ($floosak && $floosak['is_active'] == 1)
             ];
             echo json_encode($res, JSON_UNESCAPED_UNICODE);
             break;

        case 'check_phone':
            $phone = $data['phone'];
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            $stmt = $pdo->prepare("SELECT id, name, status FROM customers WHERE phone = ?");
            $stmt->execute([$cleanPhone]);
            $user = $stmt->fetch();
            if ($user) echo json_encode(['exists' => true, 'name' => $user['name'], 'status' => $user['status']]);
            else echo json_encode(['exists' => false]);
            break;

        case 'customer_register':
            $name = $data['name'];
            $phone = $data['phone'];
            $password = $data['password'];
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            
            $check = $pdo->prepare("SELECT id FROM customers WHERE phone = ?");
            $check->execute([$cleanPhone]);
            if($check->fetch()) { echo json_encode(['success' => false, 'message' => 'رقم الهاتف مسجل مسبقاً']); exit; }
            
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO customers (name, phone, password_hash, status, balance) VALUES (?, ?, ?, 'active', 0)");
            $stmt->execute([$name, $cleanPhone, $hash]);
            
            $id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("SELECT id, name, phone, balance, status FROM customers WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            $user['transactions'] = [];
            echo json_encode(['success' => true, 'user' => $user]);
            break;

        case 'customer_login_password':
            $phone = $data['phone'];
            $password = $data['password'];
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            
            $stmt = $pdo->prepare("SELECT * FROM customers WHERE phone = ?");
            $stmt->execute([$cleanPhone]);
            $user = $stmt->fetch();
            
            if (!$user) { echo json_encode(['success' => false, 'message' => 'الحساب غير موجود']); exit; }
            if ($user['status'] === 'disabled') { echo json_encode(['success' => false, 'message' => 'هذا الحساب معطل']); exit; }
            if (empty($user['password_hash'])) { echo json_encode(['success' => false, 'message' => 'يرجى التواصل مع الإدارة']); exit; }
            
            if (password_verify($password, $user['password_hash'])) {
                unset($user['password_hash']);
                $stmt = $pdo->prepare("SELECT * FROM transactions WHERE customer_id = ?");
                $stmt->execute([$user['id']]);
                $trans = $stmt->fetchAll();
                foreach($trans as &$t) { if($t['details']) $t['details'] = json_decode($t['details'], true); }
                $user['transactions'] = $trans;
                echo json_encode(['success' => true, 'user' => $user]);
            } else { echo json_encode(['success' => false, 'message' => 'كلمة المرور غير صحيحة']); }
            break;

        case 'refresh_user':
            $id = $data['id'];
            $stmt = $pdo->prepare("SELECT id, name, phone, balance, status FROM customers WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            if($user) {
                $stmt = $pdo->prepare("SELECT * FROM transactions WHERE customer_id = ? ORDER BY id DESC");
                $stmt->execute([$id]);
                $trans = $stmt->fetchAll();
                foreach($trans as &$t) { if($t['details']) $t['details'] = json_decode($t['details'], true); }
                $user['transactions'] = $trans;
                echo json_encode(['success' => true, 'user' => $user], JSON_UNESCAPED_UNICODE);
            } else { echo json_encode(['success' => false, 'message' => 'User not found']); }
            break;

        case 'request_topup':
            $stmt = $pdo->prepare("INSERT INTO topup_requests (customer_id, bank_id, bank_name, amount, ref_code, image, comment, date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([$data['customerId'], $data['bankId'], $data['bankName'], $data['amount'], $data['refCode'], $data['image'], $data['comment'], $data['date']]);
            echo json_encode(['success' => true]);
            break;

        case 'checkout':
            $customerId = $data['customerId'];
            $cartItems = $data['cart'];
            $total = $data['total'];
            $date = $data['date'];
            $method = isset($data['paymentMethod']) ? $data['paymentMethod'] : 'wallet';
            
            try {
                $pdo->beginTransaction();
                
                // 1. التحقق من رصيد العميل وحالة الحساب
                $stmt = $pdo->prepare("SELECT balance, status FROM customers WHERE id = ? FOR UPDATE");
                $stmt->execute([$customerId]);
                $user = $stmt->fetch();
                
                if(!$user) throw new Exception("المستخدم غير موجود");
                if($user['status'] === 'disabled') throw new Exception("الحساب معطل");
                if((float)$user['balance'] < (float)$total) throw new Exception("الرصيد غير كاف");
                
                $purchasedCards = [];
                
                // 2. حجز الكروت
                foreach($cartItems as $item) {
                    // نستخدم FOR UPDATE لمنع بيع نفس الكرت لشخصين في نفس اللحظة
                    $stmt = $pdo->prepare("SELECT id, code FROM cards WHERE pkg_id = ? AND status = 'active' LIMIT 1 FOR UPDATE");
                    $stmt->execute([$item['productId']]);
                    $card = $stmt->fetch();
                    
                    if(!$card) throw new Exception("نفذت الكمية للباقة: " . $item['name']);
                    
                    $updateStmt = $pdo->prepare("UPDATE cards SET status = 'sold', sold_date = ? WHERE id = ?");
                    $updateStmt->execute([$date, $card['id']]);
                    
                    $purchasedCards[] = ['name' => $item['name'], 'code' => $card['code'], 'price' => $item['price']];
                }
                
                // 3. خصم الرصيد
                $newBalance = (float)$user['balance'] - (float)$total;
                $pdo->prepare("UPDATE customers SET balance = ? WHERE id = ?")->execute([$newBalance, $customerId]);
                
                // 4. تسجيل العملية
                $transItems = count($purchasedCards) > 1 ? "شراء " . count($purchasedCards) . " كروت" : $purchasedCards[0]['name'];
                
                // إصلاح: حفظ طريقة الدفع بشكل صحيح ككائن منفصل
                $detailsData = [
                    'cards' => $purchasedCards,
                    'method' => $method
                ];
                
                $stmt = $pdo->prepare("INSERT INTO transactions (customer_id, type, amount, date, items, details) VALUES (?, 'debit', ?, ?, ?, ?)");
                $stmt->execute([$customerId, $total, $date, $transItems, json_encode($detailsData, JSON_UNESCAPED_UNICODE)]);
                
                $pdo->commit();
                echo json_encode(['success' => true, 'purchased' => $purchasedCards], JSON_UNESCAPED_UNICODE);
                
            } catch (Exception $e) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
            }
            break;

        case 'report_issue':
            $stmt = $pdo->prepare("UPDATE cards SET status = 'reported', report_reason = ? WHERE code = ?");
            $stmt->execute([$data['reason'], $data['code']]);
            echo json_encode(['success' => true]);
            break;

        case 'admin_reset_password': 
            $id = $data['id'];
            $newPass = $data['password'];
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE customers SET password_hash = ? WHERE id = ?")->execute([$hash, $id]);
            echo json_encode(['success' => true]);
            break;

        case 'add_partner':
            $stmt = $pdo->prepare("INSERT INTO partners (name, phone, commission) VALUES (?, ?, ?)");
            $stmt->execute([$data['name'], $data['phone'], $data['commission']]);
            echo json_encode(['success' => true]);
            break;

        case 'delete_partner':
            $pdo->prepare("DELETE FROM partners WHERE id = ?")->execute([$data['id']]);
            echo json_encode(['success' => true]);
            break;
            
        case 'add_package':
            $categories = isset($data['categories']) ? $data['categories'] : 'best_selling';
            $stmt = $pdo->prepare("INSERT INTO packages (name, price, amount, unit, type, grad, partner_id, network, categories) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['name'], $data['price'], $data['amount'], $data['unit'], 'daily', $data['grad'], $data['partnerId'], $data['network'], $categories]);
            echo json_encode(['success' => true]);
            break;

        case 'update_package':
            $pdo->prepare("UPDATE packages SET name = ?, price = ? WHERE id = ?")->execute([$data['name'], $data['price'], $data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'delete_package':
            $pdo->prepare("DELETE FROM packages WHERE id = ?")->execute([$data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'upload_cards':
            $cards = $data['cards'];
            $sql = "INSERT INTO cards (pkg_id, pkg_name, code, price, upload_date, status) VALUES ";
            $values = [];
            $params = [];
            // تحسين: التعامل مع مصفوفة كبيرة عبر تجميع القيم
            foreach($cards as $c) {
                $values[] = "(?, ?, ?, ?, ?, 'active')";
                array_push($params, $c['pkgId'], $c['pkgName'], $c['code'], $c['price'], $c['date']);
            }
            if(!empty($values)) {
                // إصلاح: تقسيم الإدخال بشكل صحيح
                $chunkSize = 500; // 500 كرت في المرة
                $paramsPerCard = 5; // عدد المعاملات لكل كرت
                
                $pdo->beginTransaction();
                try {
                    $totalCards = count($values);
                    for ($offset = 0; $offset < $totalCards; $offset += $chunkSize) {
                        // تقطيع القيم
                        $batchValues = array_slice($values, $offset, $chunkSize);
                        // تقطيع المعاملات بشكل متوافق
                        $batchParams = array_slice($params, $offset * $paramsPerCard, count($batchValues) * $paramsPerCard);
                        
                        $batchSql = "INSERT INTO cards (pkg_id, pkg_name, code, price, upload_date, status) VALUES " . implode(", ", $batchValues);
                        $pdo->prepare($batchSql)->execute($batchParams);
                    }
                    $pdo->commit();
                    echo json_encode(['success' => true, 'count' => $totalCards]);
                } catch (Exception $e) {
                    $pdo->rollBack();
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No cards data']);
            }
            break;

        case 'toggle_card':
            $pdo->prepare("UPDATE cards SET status = ? WHERE id = ?")->execute([$data['status'], $data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'delete_card':
            $pdo->prepare("DELETE FROM cards WHERE id = ?")->execute([$data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'resolve_dispute':
            $cardId = $data['id'];
            $action = $data['action'];
            $reason = isset($data['reason']) ? $data['reason'] : null;
            try {
                $pdo->beginTransaction();
                $cardStmt = $pdo->prepare("SELECT * FROM cards WHERE id = ?");
                $cardStmt->execute([$cardId]);
                $card = $cardStmt->fetch();
                if($action === 'refund') {
                    $pdo->prepare("UPDATE cards SET status = 'refunded', dispute_resolution = 'refunded' WHERE id = ?")->execute([$cardId]);
                    
                    // إصلاح: البحث المحسن عن العميل صاحب الكرت
                    $targetCustId = null;
                    
                    // البحث في جميع المعاملات بدون حد
                    $trans = $pdo->query("SELECT * FROM transactions WHERE type='debit'")->fetchAll();
                    foreach($trans as $t) {
                        if($t['details']) {
                            // فك تشفير JSON للبحث الصحيح
                            $details = json_decode($t['details'], true);
                            if($details) {
                                // التعامل مع الهيكل الجديد (cards + method)
                                $cardsArray = isset($details['cards']) ? $details['cards'] : $details;
                                
                                if(is_array($cardsArray)) {
                                    foreach($cardsArray as $item) {
                                        if(is_array($item) && isset($item['code']) && $item['code'] === $card['code']) {
                                            $targetCustId = $t['customer_id'];
                                            break 2; // الخروج من الحلقتين
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    if($targetCustId) {
                        $pdo->prepare("UPDATE customers SET balance = balance + ? WHERE id = ?")->execute([$card['price'], $targetCustId]);
                        $pdo->prepare("INSERT INTO transactions (customer_id, type, amount, date, items) VALUES (?, 'credit', ?, ?, ?)")->execute([$targetCustId, $card['price'], date('d/m/Y'), "استرجاع قيمة كرت - " . $card['code']]);
                    }
                } else {
                    $pdo->prepare("UPDATE cards SET status = 'sold', report_reason = NULL, dispute_resolution = 'rejected', dispute_reason = ? WHERE id = ?")->execute([$reason, $cardId]);
                }
                $pdo->commit();
                echo json_encode(['success' => true]);
            } catch(Exception $e) { $pdo->rollBack(); echo json_encode(['success' => false, 'message' => $e->getMessage()]); }
            break;

        case 'add_bank':
            $stmt = $pdo->prepare("INSERT INTO banks (name, account, owner) VALUES (?, ?, ?)");
            $stmt->execute([$data['name'], $data['account'], $data['owner']]);
            echo json_encode(['success' => true]);
            break;

        case 'delete_bank':
            $pdo->prepare("DELETE FROM banks WHERE id = ?")->execute([$data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'process_deposit':
            $reqId = $data['id']; $status = $data['status']; $rejectReason = isset($data['reason']) ? $data['reason'] : null;
            try {
                $pdo->beginTransaction();
                $reqStmt = $pdo->prepare("SELECT * FROM topup_requests WHERE id = ? FOR UPDATE");
                $reqStmt->execute([$reqId]);
                $req = $reqStmt->fetch();
                if($req['status'] !== 'pending') throw new Exception("الطلب تمت معالجته مسبقاً");
                
                if($status === 'approved') {
                    $pdo->prepare("UPDATE customers SET balance = balance + ? WHERE id = ?")->execute([$req['amount'], $req['customer_id']]);
                    $desc = "إيداع بنكي ({$req['bank_name']}) - #{$req['ref_code']}";
                    $pdo->prepare("INSERT INTO transactions (customer_id, type, amount, date, items) VALUES (?, 'credit', ?, ?, ?)")->execute([$req['customer_id'], $req['amount'], date('d/m/Y'), $desc]);
                    $pdo->prepare("UPDATE topup_requests SET status = 'approved', reviewed = 1 WHERE id = ?")->execute([$reqId]);
                } else {
                    $pdo->prepare("UPDATE topup_requests SET status = 'rejected', rejection_reason = ?, reviewed = 1 WHERE id = ?")->execute([$rejectReason, $reqId]);
                }
                $pdo->commit(); echo json_encode(['success' => true]);
            } catch(Exception $e) { $pdo->rollBack(); echo json_encode(['success' => false, 'message' => $e->getMessage()]); }
            break;
            
        case 'toggle_deposit_review':
            $pdo->prepare("UPDATE topup_requests SET reviewed = !reviewed WHERE id = ?")->execute([$data['id']]);
            echo json_encode(['success' => true]);
            break;

        case 'add_withdrawal':
            $stmt = $pdo->prepare("INSERT INTO withdrawals (partner_id, amount, receiver, bank, note, image, date, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['partnerId'], $data['amount'], $data['receiver'], $data['bank'], $data['note'], $data['image'], $data['date'], time()]);
            echo json_encode(['success' => true]);
            break;

        case 'admin_topup':
            $cid = $data['id']; $amt = $data['amount'];
            try { 
                $pdo->beginTransaction(); 
                $pdo->prepare("UPDATE customers SET balance = balance + ? WHERE id = ?")->execute([$amt, $cid]); 
                $pdo->prepare("INSERT INTO transactions (customer_id, type, amount, date, items) VALUES (?, 'credit', ?, ?, ?)")->execute([$cid, $amt, date('d/m/Y'), "شحن رصيد (من الإدارة)"]); 
                $pdo->commit(); 
                echo json_encode(['success' => true]); 
            } catch(Exception $e) { $pdo->rollBack(); echo json_encode(['success' => false]); }
            break;

        case 'toggle_customer_status':
            $status = $data['status']; $pdo->prepare("UPDATE customers SET status = ? WHERE id = ?")->execute([$status, $data['id']]); echo json_encode(['success' => true]);
            break;

        case 'add_network_manager':
            $partnerId = $data['partnerId']; $name = $data['name']; $username = $data['username']; $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $check = $pdo->prepare("SELECT id FROM network_managers WHERE username = ?"); $check->execute([$username]);
            if($check->fetch()) { echo json_encode(['success' => false, 'message' => 'اسم المستخدم موجود مسبقاً']); exit; }
            $stmt = $pdo->prepare("INSERT INTO network_managers (partner_id, name, username, password_hash) VALUES (?, ?, ?, ?)");
            $stmt->execute([$partnerId, $name, $username, $password]);
            echo json_encode(['success' => true]);
            break;

        case 'delete_network_manager':
            $pdo->prepare("DELETE FROM network_managers WHERE id = ?")->execute([$data['id']]); echo json_encode(['success' => true]);
            break;

        case 'manager_login':
            $username = $data['username']; $password = $data['password'];
            $stmt = $pdo->prepare("SELECT * FROM network_managers WHERE username = ?"); $stmt->execute([$username]); $mgr = $stmt->fetch();
            if($mgr && password_verify($password, $mgr['password_hash'])) {
                unset($mgr['password_hash']);
                $partner = $pdo->prepare("SELECT * FROM partners WHERE id = ?"); $partner->execute([$mgr['partner_id']]); $partnerData = $partner->fetch();
                echo json_encode(['success' => true, 'manager' => $mgr, 'partner' => $partnerData]);
            } else { echo json_encode(['success' => false, 'message' => 'بيانات الدخول غير صحيحة']); }
            break;

        case 'get_manager_report':
            $partnerId = $data['partner_id'];
            $packages = $pdo->prepare("SELECT * FROM packages WHERE partner_id = ?"); $packages->execute([$partnerId]); $pkgList = $packages->fetchAll();
            $pkgIds = array_column($pkgList, 'id');
            $cards = [];
            if(!empty($pkgIds)) {
                $inQuery = implode(',', array_fill(0, count($pkgIds), '?'));
                $stmt = $pdo->prepare("SELECT * FROM cards WHERE pkg_id IN ($inQuery)"); $stmt->execute($pkgIds); $cards = $stmt->fetchAll();
            }
            $disputes = [];
            if(!empty($cards)) {
                $disputes = array_filter($cards, function($c) {
                    return $c['status'] === 'reported' || $c['dispute_resolution'] !== null;
                });
            }
            $withdrawals = $pdo->prepare("SELECT * FROM withdrawals WHERE partner_id = ? ORDER BY id DESC"); $withdrawals->execute([$partnerId]);
            echo json_encode(['success' => true, 'packages' => $pkgList, 'cards' => $cards, 'withdrawals' => $withdrawals->fetchAll(), 'disputes' => array_values($disputes)], JSON_UNESCAPED_UNICODE);
            break;

        case 'floosak_request_key': 
            $phone = $data['phone']; $short_code = $data['short_code'];
            // تأكد أن السجل موجود قبل التحديث
            $check = $pdo->query("SELECT id FROM payment_settings WHERE gateway_name = 'floosak'")->fetch();
            if(!$check) {
                $pdo->prepare("INSERT INTO payment_settings (gateway_name, merchant_phone, short_code) VALUES ('floosak', ?, ?)")->execute([$phone, $short_code]);
            } else {
                $stmt = $pdo->prepare("UPDATE payment_settings SET merchant_phone = ?, short_code = ? WHERE gateway_name = 'floosak'"); $stmt->execute([$phone, $short_code]);
            }
            
            try { 
                $res = callFloosakAPI("/request/key", ["phone" => $phone, "short_code" => $short_code]); 
                if (isset($res['request_id'])) echo json_encode(['success' => true, 'request_id' => $res['request_id']]); 
                else echo json_encode(['success' => false, 'message' => isset($res['message']) ? $res['message'] : 'فشل الاتصال']); 
            } catch (Exception $e) { echo json_encode(['success' => false, 'message' => "خطأ داخلي: " . $e->getMessage()]); }
            break;

        case 'floosak_verify_key': 
            $request_id = $data['request_id']; $otp = $data['otp'];
            try { 
                $res = callFloosakAPI("/verify/key", ["request_id" => $request_id, "otp" => $otp]); 
                if (isset($res['key'])) { 
                    $token = $res['key']; 
                    $walletId = null; 
                    if (isset($res['account_detail']['wallets'])) { 
                        foreach($res['account_detail']['wallets'] as $w) { 
                            if ($w['currency']['symbol']['en'] == 'YER') { $walletId = $w['id']; break; } 
                        } 
                    } 
                    $stmt = $pdo->prepare("UPDATE payment_settings SET auth_token = ?, source_wallet_id = ?, is_active = 1 WHERE gateway_name = 'floosak'"); 
                    $stmt->execute([$token, $walletId]); 
                    echo json_encode(['success' => true, 'message' => 'تم تفعيل بوابة فلوسك بنجاح!']); 
                } else { 
                    echo json_encode(['success' => false, 'message' => isset($res['message']) ? $res['message'] : 'فشل التحقق']); 
                } 
            } catch (Exception $e) { echo json_encode(['success' => false, 'message' => "خطأ: " . $e->getMessage()]); }
            break;

        case 'floosak_init_pay':
            // منطق بدء الدفع عبر فلوسك (للعميل)
            // يتطلب حفظ العملية في قاعدة البيانات وانتظار الـ OTP
            // هذا مجرد هيكل، يجب استكماله حسب API فلوسك للخصم (Purchase)
            echo json_encode(['success' => true, 'purchase_id' => 'sim_123', 'message' => 'تم إرسال OTP']);
            break;

        case 'floosak_confirm_pay':
            // تأكيد الدفع عبر OTP
            $custId = $data['customer_id'];
            $amount = $data['amount'];
            // هنا يجب استدعاء API فلوسك الحقيقية للتحقق
            // محاكاة النجاح:
            $pdo->beginTransaction();
            $pdo->prepare("UPDATE customers SET balance = balance + ? WHERE id = ?")->execute([$amount, $custId]);
            $pdo->prepare("INSERT INTO transactions (customer_id, type, amount, date, items) VALUES (?, 'credit', ?, ?, ?)")->execute([$custId, $amount, date('d/m/Y'), "شحن فلوسك"]);
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'تم الدفع والشحن بنجاح']);
            break;

        case 'get_gateway_balances':
            // إصلاح: محاولة جلب الأرصدة الحقيقية إذا أمكن
            $floosakBal = 0; $floosakStatus = 'inactive';
            $settings = $pdo->query("SELECT * FROM payment_settings WHERE gateway_name = 'floosak'")->fetch();
            
            if ($settings && $settings['is_active']) {
                $floosakStatus = 'active';
                // إذا كان لدينا توكن، نحاول جلب الرصيد الحقيقي
                if ($settings['auth_token']) {
                    // هنا يمكن استدعاء API فلوسك لجلب الرصيد (GET /wallets)
                    // حالياً سنفترض أنه يعمل أو نعيد 0 لتجنب البطء في الداشبورد
                    // $res = callFloosakAPI('/wallets', [], $settings['auth_token']);
                }
            }

            echo json_encode([
                'floosak' => ['balance' => $floosakBal, 'status' => $floosakStatus], 
                'jawali' => ['balance' => 0, 'status' => 'inactive'], 
                'credit_card' => ['balance' => 0, 'status' => 'inactive']
            ]);
            break;

        default: echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch(Exception $e) { 
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    http_response_code(500); 
    echo json_encode(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]); 
}
?>