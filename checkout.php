<?php
ob_start();
$title = "Thanh Toán";
include "./includes/header.php";
include "admin/includes/functions.php";

if (!isset($_SESSION["type"]) || $_SESSION["type"] != 0) {
    header('location:sign_in.php?back_to_checkout');
}
if (!isset($_SESSION['cart'])) {
    header('location:shop.php?page=1');
}
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
}

$sql = "SELECT * FROM users WHERE user_id = $id";
$result = mysqli_query($conn, $sql);
$users  = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_POST['pay'])) {
    $check = 1;
    $name = ($_POST["name"]);
    $email = strtolower($_POST["email"]);
    $location = ($_POST["city"]);
    $mobile   = ($_POST["number"]);

    if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $nameError = "Chỉ cho phép chữ cái và khoảng trắng";
        $check = 0;
    }
    // Kiểm tra email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Email không hợp lệ";
        $check = 0;
    }
    if ($name == "") {
        $check = 0;
        $nameError = "Tên không được để trống!";
    }
    if ($email == "") {
        $check = 0;
        $emailError = "Email không được để trống!";
    }
    if ($location == "") {
        $check = 0;
        $locationError = "Địa chỉ không được để trống!";
    }
    if ($mobile == "") {
        $check = 0;
        $mobileError = "Số điện thoại không được để trống!";
    }
    if (!preg_match("/^[077|079|078]+[0-9]{7}$/", $mobile)) { // Kiểm tra số điện thoại
        $mobileError  = "Phải là số điện thoại hợp lệ";
        $check      = 0;
    }
    if ($check == 1) {
        foreach ($_SESSION['cart'] as $key => $val) {
            $total += (int)$val['product_price'] * (int)$val['quantity'] * 23000; // Chuyển đổi sang VNĐ
            $cartStr = "," . $val['product_name'] . "," . $val['product_price'] . "," . $val['product_id'] . "," . $val['product_size'] . "," . $val['product_quantity'];
            $cart .= $cartStr;
        }
        if ($total != 0) {
            $tot = $_SESSION['total'] * 23000; // Chuyển đổi sang VNĐ
            $sql = "INSERT INTO orders (`order_details`,`order_location`,`order_mobile`,`order_user_id`,`order_user_name`,`order_total`) VALUES ('$cart','$location',$mobile,$id,'$name','$tot')";
            $result = mysqli_query($conn, $sql);
            unset($_SESSION['cart']);
            unset($_SESSION['total']);
            unset($_SESSION['couponsDis']);
            redirect("index.php?checked");
            $conn->close();
        }
    }
}

if (isset($_POST['checkout']) && $total == 0) {
    echo  '<div class="text-center h5 mt-5">Bạn phải thêm sản phẩm vào giỏ hàng!</div>';
} elseif (isset($_POST['checkout']) && $total != 0) {
    unset($_SESSION['cart']);
    unset($_SESSION['total']);
    unset($_SESSION['couponsDis']);
    header("location:index.php?checked");
}
?>
</div>
</form>
<!-- breadcrumb -->
<div class="container">
    <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
        <a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
            Trang Chủ
            <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
        </a>
        <span class="stext-109 cl4">
            Thanh Toán
        </span>
    </div>
</div>
<!-- Giỏ Hàng -->
<form class="bg0 p-t-75 p-b-85" method="POST">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                <div class="m-l-25 m-r--38 m-lr-0-xl">
                    <div class="form-row">
                        <div class="col-md-6 mb-4">
                            <label for="validationCustom01">Họ và Tên</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $users[0]['user_name'] ?>">
                            <div class="valid-feedback text-danger">
                                <?php echo isset($nameError) ? $nameError : ""; ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="validationCustom01">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $users[0]['user_email']; ?>">
                            <div class="valid-feedback text-danger">
                                <?php echo isset($emailError) ? $emailError : ""; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-4">
                            <label for="validationCustom03">Số Điện Thoại</label>
                            <input type="tel" name="number" class="form-control" value="<?php echo isset($users[0]['user_mobile']) ? $users[0]['user_mobile'] : ""; ?>" placeholder="Số Điện Thoại" pattern="^0[0-9]{9,10}$" required>
                            <div class="text-danger">
                                <?php echo isset($mobileError) ? $mobileError : ""; ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="validationCustom04">Thành Phố</label>
                            <input type="text" name="city" class="form-control" value="<?php echo isset($users[0]['user_location']) ? $users[0]['user_location'] : ""; ?>" placeholder="Thành Phố">
                            <div class="text-danger">
                                <?php echo isset($locationError) ? $locationError : ""; ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input style="position:absolute; left:20px;" class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                            <label class="form-check-label" for="invalidCheck">
                                Thanh toán khi nhận hàng
                            </label>
                            <div class="invalid-feedback">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-10 col-lg-10 col-xl-5 m-lr-auto m-b-50">
                <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                    <h4 class="mtext-109 cl2 p-b-30">
                        Tổng Giỏ Hàng
                    </h4>
                    <ul class="header-cart-wrapitem w-full">
                        <?php if (isset($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $key => $value) { ?>
                                <li class="header-cart-item flex-w flex-t m-b-12">
                                    <div class="header-cart-item-img">
                                        <img loading="lazy" src="<?php echo "admin/" . $value['product_image']; ?>" alt="IMG">
                                    </div>
                                    <div class="header-cart-item-txt p-t-8">
                                        <a href="#" class="header-cart-item-name m-b-18 hov-cl1 trans-04">
                                            <?php echo $value['product_name'] . " " . $value['size']; ?>
                                        </a>
                                        <span class="header-cart-item-info">
                                            <?php 
                                            echo $value['quantity'] . " x " . number_format($value['product_price'] * 23000, 0, ',', '.') . " VNĐ"; // Chuyển đổi sang VNĐ
                                            $total += (int)$value['product_price'] * (int)$value['quantity'] * 23000; // Tính tổng
                                            ?>
                                        </span>
                                    </div>
                                </li>
                        <?php }
                        } ?>
                    </ul>
                    <hr>
                    <div class="flex-w flex-t p-b-33">
                        <div class="size-208">
                            <span class="mtext-101 cl2">
                                Tổng: <?php echo isset($_SESSION['total']) ? number_format($_SESSION['total'] * 23000, 0, ',', '.') . ' VNĐ' : number_format($total, 0, ',', '.') . ' VNĐ'; ?>
                            </span>
                        </div>
                    </div>
                    <button type="submit" name="pay" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                        Thanh Toán
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php include "./includes/footer.php";
ob_end_flush(); 
?>