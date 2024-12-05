<?php
$title = "Cửa Hàng";
include("includes/header.php");
include "./admin/includes/functions.php";
include "./admin/includes/connect.php";

$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
$tags = mysqli_fetch_all($result, MYSQLI_ASSOC);

$tagsArray = [];
if (isset($_POST['numPage'])) {
    $results_per_page = $_POST['numPage'];
} else {
    $results_per_page = 12;
}

foreach ($tags as $key => $value) {
    array_push($tagsArray, $value['product_tag']);
}
$tags_unique = array_unique($tagsArray);
?>

<!-- Product -->
<style>
    .row {
        margin-left: 0px;
    }
    .containera {
        width: auto;
        padding-right: 0px;
        margin: 0 !important;
    }
    @media (max-width: 2000px) {
        .containera {
            max-width: 100% !important;
        }
    }
    .selectn {
        border: none;
        outline: none;
        width: 100px;
        border-radius: 6px;
        padding: 8px 20px 8px 0;
    }
    .label {
        width: 100px;
    }
</style>

<div class="bg0">
    <div class="containera row">
        <div class="col-lg-3 col-md-4 col-12">
            <div class="flex-w flex-sb-m p-b-52 mt-1">
                <div class="panel-filter w-full p-t-10">
                    <div class="row wrap-filter flex-w bg6 w-full p-l-40 p-t-27 p-lr-15-sm">
                        <form method="POST" class="size-204 respon6-next">
                            <div class="col-md-12 col-sm-4 col-12 filter-col1 p-r-15 p-b-27">
                                <label class="label" for="selectn">Sản phẩm </label>
                                <select name="numPage" class="selectn" id="selectn" onchange='this.form.submit()'>
                                    <option class="selectn" value=12>12</option>
                                    <option class="selectn" value=16>16</option>
                                    <option class="selectn" value=20>20</option>
                                </select>
                            </div>
                            <noscript><input type="submit" value="Submit"></noscript>
                        </form>
                        <div class="col-md-12 col-sm-4 col-12 filter-col3 p-b-27">
                            <div class="mtext-102 cl2 p-b-15">
                                Danh Mục
                            </div>
                            <div class="flex-w p-t-4 m-r--5">
                                <?php foreach ($categories as $key => $value) { ?>
                                    <a href="?sort=category&id=<?php echo $value['category_id']; ?>" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                        <?php echo $value['category_name']; ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of filter  -->
        <?php
        if (isset($_GET["sort"])) {
            if ($_GET["sort"] == "high") {
                $sql = "SELECT * FROM products ORDER BY product_price DESC";
            } elseif ($_GET["sort"] == "category") {
                $id = (int)$_GET["id"];
                $sql = "SELECT * FROM products WHERE product_categorie_id=$id";
            } elseif ($_GET["sort"] == "low") {
                $sql = "SELECT * FROM products ORDER BY product_price ASC";
            } elseif ($_GET["sort"] == "rating") {
                $sql = "SELECT * FROM products ORDER BY product_rate DESC";
            } elseif ($_GET["sort"] == "newness") {
                $sql = "SELECT * FROM products ORDER BY product_date DESC";
            } else {
                $priceRange = explode("-", $_GET["sort"]);
                if (count($priceRange) == 2) {
                    $sql = "SELECT * FROM products WHERE product_price BETWEEN {$priceRange[0]} AND {$priceRange[1]}";
                }
            }
            $result = mysqli_query($conn, $sql);
            $product = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $sql = "SELECT * FROM products";
            $result = mysqli_query($conn, $sql);
            $number_of_results = mysqli_num_rows($result);
            $number_of_pages = ceil($number_of_results / $results_per_page);
            if (!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = $_GET['page'];
            }
            $this_page_first_result = ($page - 1) * $results_per_page;
            $sql = 'SELECT * FROM products LIMIT ' . $this_page_first_result . ',' .  $results_per_page;
            $result = mysqli_query($conn, $sql);
            $product = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        ?>
        <div class="row col-md-8 ">
            <?php foreach ($product as $val) { ?>
                <div class="col-sm-6 col-md-4 col-12 col-lg-3 mt-3 p-b-35">
                    <div class="block2">
                        <a href="product-detail.php?id=<?php echo $val['product_id']; ?>">
                            <div class="block2-pic hov-img0">
                                <img src="<?php echo 'admin/' . $val['product_main_image']; ?>" loading="lazy" alt="IMG-PRODUCT">
                            </div>
                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l ">
                                    <a href="product-detail.php?id=<?php echo $val['product_id']; ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                        <?php echo $val['product_name']; ?>
                                    </a>
                                    <span class="stext-105 cl3">
                                        <?php echo number_format($val['product_price'] * 23000, 0, ',', '.') . ' VNĐ'; ?> <!-- Chuyển đổi sang VNĐ -->
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<style>
    p, li, a {
        font-size: 14px;
    }
    .pagination {
        padding: 30px 0;
    }
    .pagination ul {
        margin: 0;
        padding: 0;
        list-style-type: none;
    }
    .pagination a {
        display: inline-block;
        padding: 10px 18px;
        color: #222;
    }
    .p9 a {
        width: 30px;
        height: 30px;
        line-height: 25px;
        padding: 0;
        text-align: center;
        margin: auto 5px;
    }
    .p9 a.is-active {
        border: 3px solid #717fe0;
        border-radius: 100%;
    }
</style>

<div style="display: flex; justify-content:center;">
    <div class="pagination p9">
        <ul>
            <?php
            if (isset($_GET['page'])) {
                for ($page = 1; $page <= $number_of_pages; $page++) { ?>
                    <a <?php echo $_GET['page'] == $page ? 'class="is-active"' : ""; ?>href="?page=<?php echo $page ?>">
                        <li><?php echo $page ?></li>
                    </a>
            <?php }
            } ?>
        </ul>
    </div>
</div>

<?php include("includes/footer.php"); ?>