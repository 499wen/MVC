<?php
namespace Models;
// use 

class Order extends Base {

    // 添加充值订单数据
    function findByCode ($code){

        $stmt = self::$pdo->prepare("select * from mvc_order where order_code = ?");
        $stmt ->execute([
            $code
        ]);
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // 充值成功 - 设置订单为已支付状态
    function setPaid ($code){

        $stmt = self::$pdo->prepare("update mvc_order set status=1,created_up = now() WHERE order_code=?");
        return $stmt ->execute([
            $code
        ]);
    }

    
}