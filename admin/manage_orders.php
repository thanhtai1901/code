<?php
require_once("includes/header.php");
$sql = "SELECT * FROM orders INNER JOIN users ON orders.order_user_id = users.user_id ";
$result = mysqli_query($conn, $sql);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<?php
?>
<div class="card-header">
    <h4 class="card-title">Quản lý Đơn hàng</h4>
</div>
<div class="row">
    <div class="col-lg-12" style=" margin-left:30px;margin-top: 25px; max-width:95%;">
        <div class="users-table table-wrapper">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr class="users-table-info">
                        <th>ID Đơn hàng</th>
                        <th>Tên người dùng</th>
                        <th>Giới tính người dùng</th>
                        <th>Chi tiết đơn hàng</th>
                        <th>Địa điểm giao hàng</th>
                        <th>Số điện thoại</th>
                        <th>Ngày đặt hàng</th>
                        <th>Tổng số tiền</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $key => $order) { ?>
                        <tr>
                            <td><?php echo isset($order['order_id']) ? $order['order_id'] : ''; ?></td>
                            <td><?php echo isset($order['order_user_name']) ? $order['order_user_name'] : ''; ?></td>
                            <td><?php echo isset($order['user_gender']) ? $order['user_gender'] : ''; ?></td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 flex justify-center items-center">
                                    <?php echo $order["order_details"]; ?>
                                </div>
                            </td>
                            <td><?php echo isset($order['order_location']) ? $order['order_location'] : ''; ?></td>
                            <td><?php echo isset($order['order_mobile']) ? $order['order_mobile'] : ''; ?></td>
                            <td><?php echo isset($order['order_date']) ? $order['order_date'] : ''; ?></td>
                            <td><?php echo isset($order['order_total']) ? $order['order_total'] : ''; ?></td>
                            <td><?php echo isset($order['order_status']) ? $order['order_status'] : ''; ?></td>
                            
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>