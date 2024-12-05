<?php
$title = "CozaStore";
include("admin/includes/connect.php");
/* thêm khách truy cập mới */
if (isset($_GET["checked"])) {
?>
    <script>
        setTimeout(() => {
            swal("Cảm ơn bạn!", "Đơn hàng của bạn đã được gửi", "success");
        }, 100);
    </script>
<?php } ?>
<?php
$visitor_ip = $_SERVER["REMOTE_ADDR"];
/* KIỂM TRA KHÁCH TRUY CẬP CÓ ĐỘC NHẤT */
$sql = "SELECT * FROM `unique_visitors`";
$result = mysqli_query($conn, $sql);
$visitors = mysqli_fetch_all($result);
$visitor_check = 1;

?>
<?php
include("includes/header.php");
$sql = "SELECT * FROM categories WHERE category_name='Nữ' OR category_name='Nam' ";
$result = mysqli_query($conn, $sql);
$cat  = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!-- Slider hình ảnh -->
<section class="section-slide">
    <div class="wrap-slick1 rs2-slick1">
        <div class="slick1">
            <div class="item-slick1 bg-overlay1" style="background-image: url(images/slide-05.jpg);" data-thumb="images/thumb-01.jpg" data-caption="Thời trang nữ">
                <div class="container h-full">
                    <div class="flex-col-c-m h-full p-t-100 p-b-60 respon5">
                        <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
                            <span class="ltext-202 txt-center cl0 respon2">
                                Bộ sưu tập Nữ 2024
                            </span>
                        </div>
                        <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                            <h2 class="ltext-104 txt-center cl0 p-t-22 p-b-40 respon1">
                                Sản phẩm mới
                            </h2>
                        </div>
                        <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                            <a href="shop.php?page=1" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn2 p-lr-15 trans-04">
                                Mua ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item-slick1 bg-overlay1" style="background-image: url(images/slide-06.jpg);" data-thumb="images/thumb-02.jpg" data-caption="Thời trang nam">
                <div class="container h-full">
                    <div class="flex-col-c-m h-full p-t-100 p-b-60 respon5">
                        <div class="layer-slick1 animated visible-false" data-appear="rollIn" data-delay="0">
                            <span class="ltext-202 txt-center cl0 respon2">
                                Mùa mới cho nam
                            </span>
                        </div>
                        <div class="layer-slick1 animated visible-false" data-appear="lightSpeedIn" data-delay="800">
                            <h2 class="ltext-104 txt-center cl0 p-t-22 p-b-40 respon1">
                                Jackets & Coats
                            </h2>
                        </div>
                        <div class="layer-slick1 animated visible-false" data-appear="slideInUp" data-delay="1600">
                            <a href="shop.php?page=1" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn2 p-lr-15 trans-04">
                                Mua ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item-slick1 bg-overlay1" style="background-image: url(images/slide-07.jpg);" data-thumb="images/thumb-03.jpg" data-caption="Thời trang nam">
                <div class="container h-full">
                    <div class="flex-col-c-m h-full p-t-100 p-b-60 respon5">
                        <div class="layer-slick1 animated visible-false" data-appear="rotateInDownLeft" data-delay="0">
                            <span class="ltext-202 txt-center cl0 respon2">
                                Bộ sưu tập Nam 2022
                            </span>
                        </div>
                        <div class="layer-slick1 animated visible-false" data-appear="rotateInUpRight" data-delay="800">
                            <h2 class="ltext-104 txt-center cl0 p-t-22 p-b-40 respon1">
                                MÙA MỚI
                            </h2>
                        </div>
                        <div class="layer-slick1 animated visible-false" data-appear="rotateIn" data-delay="1600">
                            <a href="shop.php?page=1" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn2 p-lr-15 trans-04">
                                Mua ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrap-slick1-dots p-lr-10"></div>
    </div>
</section>
<!-- Banner -->


<!-- phần tin tức -->
<section class="bg0 p-t-23 p-b-130">
    <div class="container">
        <div class="p-b-10">
            <h3 class="ltext-103 cl5">
                Sản phẩm mới
            </h3>
        </div>
        <?php
        $sql = "SELECT * FROM products WHERE product_tag LIKE '%new%' LIMIT 5 ";
        $result = mysqli_query($conn, $sql);
        $product  = mysqli_fetch_all($result, MYSQLI_ASSOC);
        ?>
        <div class="row isotope-grid">
            <?php foreach ($product as $val) { ?>
                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item sales">
                    <div class="block2">
                        <div class="block2-pic hov-img0 label-new" data-label="Mới">
                            <a href="product-detail.php?id=<?php echo $val["product_id"] ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                <img loading="lazy" src="<?php echo 'admin/' . $val['product_main_image']; ?>" alt="IMG-PRODUCT">
                            </a>
                        </div>
                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l ">
                                <a href="product-detail.php?id=<?php echo $val["product_id"] ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                    <?php echo $val["product_name"] ?>
                                </a>
                                <span class="stext-105 cl3">
                                    <?php echo number_format($val["product_price"] * 23000, 0, ',', '.') . ' VNĐ'; ?>
                                </span> <!-- Chuyển đổi sang VNĐ -->
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!-- phần giảm giá -->
<section class="bg0 p-t-23 p-b-130">
    <div class="container">
        <div class="p-b-10">
            <h3 class="ltext-103 cl5">
                Sản phẩm đang giảm giá
            </h3>
        </div>
        <?php
        $sql = "SELECT * FROM products WHERE product_tag LIKE '%sales%' LIMIT 5 ";
        $result = mysqli_query($conn, $sql);
        $product  = mysqli_fetch_all($result, MYSQLI_ASSOC);
        ?>
        <div class="row isotope-grid">
            <?php foreach ($product as $val) { ?>
                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item sales">
                    <div class="block2">
                        <div class="block2-pic hov-img0 ">
                            <a href="product-detail.php?id=<?php echo $val["product_id"] ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                <div style="width:15%;height:5vh;border-radius:50px;background-color:red;text-align:center;position:absolute;padding-top:10px;color:white;font-weight:bold"> 50% </div>
                                <img loading="lazy" src="<?php echo 'admin/' . $val['product_main_image']; ?>" alt="IMG-PRODUCT">
                            </a>
                        </div>
                        <div class="block2-txt flex-w flex-t p-t-14">
                            <div class="block2-txt-child1 flex-col-l ">
                                <a href="product-detail.php?id=<?php echo $val["product_id"] ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                    <?php echo $val["product_name"] ?>
                                </a>
                                <span class="stext-105 cl3">
                                    <?php echo number_format($val["product_price"] * 23000, 0, ',', '.') . ' VNĐ'; ?> <!-- Chuyển đổi sang VNĐ -->
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php
include("includes/footer.php");
?>