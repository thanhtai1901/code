<?php
ob_start();
include "./includes/header.php";
if (!isset($_SESSION["type"]) || $_SESSION["type"] == 0) {
  redirect('../index.php');
}
// chọn tất cả các danh mục
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
if (isset($_GET["do"])) {
  $do = $_GET["do"];
  // để hiển thị giá trị trước đó trong chế độ chỉnh sửa
  if ($do == "edit") {
    $id = $_GET["id"];
    $sql = "SELECT * FROM categories WHERE category_id =$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
  }
  // xóa
  if ($do == "delete") {
    $id = $_GET["id"];
    $sql = "DELETE FROM categories WHERE category_id = '$id'";
    $result = mysqli_query($conn, $sql);
    redirect("manage_categories.php");
  }
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST["category_name"];
    $category_description = $_POST["category_description"];
    $category_image = $_FILES["category_image"];
    // xác thực
    $check = 1;
    if ($category_name == "") {
      $check = 0;
      $categoryError = "Tên danh mục không được để trống!";
    }
    if ($category_description == "") {
      $checkok = 0;
      $categoryDescriptionError = "Mô tả danh mục không được để trống!";
    }
    if ($category_image["size"] == 0) {
      $check = 0;
      $imageError = "Vui lòng chọn một hình ảnh";
    }
    // tải hình ảnh lên
    if ($check == 1) {
      $target_dir = "uploads/category_image/";
      if (getimagesize($category_image["tmp_name"]) != 0) {
        $newImage = $target_dir . "IMG-" . uniqid() . $category_image["name"];
        move_uploaded_file($category_image["tmp_name"], $newImage);
      }
    }
    // chỉnh sửa
    if ($do == "edit" && $check == 1) {
      $id = $_GET["id"];
      $sql = "UPDATE categories SET category_name = '$category_name', category_description = '$category_description', category_image = '$newImage' WHERE category_id = $id";
      $result = mysqli_query($conn, $sql);
      redirect("manage_categories.php");
    }
    // thêm
    if ($do == "add" && $check == 1) {
      $sql = "INSERT INTO categories (category_name, category_description, category_image) VALUES ('$category_name', '$category_description', '$newImage')";
      $result = mysqli_query($conn, $sql);
      redirect("manage_categories.php");
    }
  }
?>
  <!-- bắt đầu form -->
  <div class="col-md-8 mt-5 col-12 offset-md-2">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Danh mục</h4>
      </div>
      <div class="card-content">
        <div class="card-body">
          <form class="form form-horizontal" method="POST" enctype="multipart/form-data">
            <div class="form-body">
              <div class="row">
                <div class="col-md-4">
                  <label>Danh mục</label>
                </div>
                <div class="col-md-8">
                  <div class="form-group has-icon-left">
                    <div class="position-relative row justify-content-center align-items-center d-flex">
                      <input name="category_name" value="<?php echo $do == "edit" ? $row["category_name"] : ""; ?>" type="text" class="form-control col-9 mb-2" placeholder="Tên danh mục" style="border: 1px solid #dce7f1 !important;" id="first-name-icon">
                      <div class="form-control-icon col-3 ">
                        <i class="bi bi-person" style="position: absolute; top:-10px; left: -20px;"></i>
                      </div>
                      <span class="text-danger mb-2"><?php echo isset($categoryNameError) ? $categoryNameError : ""; ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <label>Hình ảnh</label>
                </div>
                <div class="col-md-8">
                  <div class="form-group has-icon-left">
                    <div class="position-relative row justify-content-center align-items-center d-flex">
                      <input name="category_image" type="file" class="form-control col-9 mb-2" style="border: 1px solid #dce7f1 !important;" id="first-name-icon">
                      <div class="form-control-icon col-3">
                        <i class="bi bi-envelope" style="position: absolute; top:-10px; left: -20px;"></i>
                      </div>
                      <span class="text-danger"><?php echo isset($imageError) ? $imageError : ""; ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary me-1 mb-1"><?php echo $_GET["do"] == "edit" ? "Lưu" : "Thêm"; ?></button>
                  <button type="reset" class="btn btn-light-secondary me-1 mb-1">Đặt lại</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- kết thúc form -->
<?php  }
?>
<!-- bắt đầu bảng -->
<?php if (!isset($_GET["do"])) { ?>
  <div class="row">
    <div class="offset-2 col-lg-9">
      <div class="users-table table-wrapper">
        <button class="btn btn-primary" style="float: right;margin:10px 50px 0px 10px;">
          <a href="?do=add">Thêm danh mục</a>
        </button>
        <table class="table table-striped" style="border: 2px solid #dce7f1 ;" id="table1">
          <thead>
            <tr class="users-table-info">
              <th>Danh mục</th>
              <th>Mô tả</th>
              <th>Hình ảnh</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($categories as $key => $value) { ?>
              <tr>
                <td>
                  <?php echo $value["category_name"] ?>
                </td>
                <td>
                  <?php echo $value["category_description"] ?>
                </td>
                <td>
                  <?php if ($value["category_image"] != NULL) { ?>
                    <img src="<?php echo $value["category_image"] ?>">
                  <?php } else {
                    echo "Không có hình ảnh";
                  } ?>
                </td>
                <td>
                  <span class="p-relative">
                    <button class="dropdown-btn transparent-btn" type="button" title="Thêm thông tin">
                      <div class="sr-only">Thêm thông tin</div>
                      <i data-feather="more-horizontal" aria-hidden="true"></i>
                    </button>
                    <ul class="users-item-dropdown dropdown">
                      <li><a href="?do=edit&id=<?php echo $value["category_id"] ?>">Chỉnh sửa</a></li>
                      <li><a href="?do=delete&id=<?php echo $value["category_id"] ?>">Xóa</a></li>
                    </ul>
                  </span>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php } ?>
<!-- kết thúc bảng -->
<?php include "./includes/footer.php";
ob_end_flush(); ?>