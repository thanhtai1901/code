<?php
ob_start();
include "./includes/header.php"; ?>
<?php
if (!isset($_SESSION["type"]) || $_SESSION["type"] != 2) {
  redirect('../index.php');
}
?>
<?php
$admin_name = "";
$admin_email = "";
$admin_password = "";
$admin_type = "";
$sql = "SELECT * FROM admins";
$result = mysqli_query($conn, $sql);
$admins  = mysqli_fetch_all($result, MYSQLI_ASSOC);
//print_r($admins);
//Xóa
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $delete = "DELETE FROM admins WHERE admin_id = $id";
  $result = mysqli_query($conn, $delete);
  if (mysqli_query($conn, $delete)) {
    echo "Đã xóa bản ghi thành công";
  } else {
    echo "Lỗi khi xóa bản ghi: " . mysqli_error($conn);
  }
  redirect("manage_admins.php");
}
//Chỉnh sửa
if (isset($_GET['do'])) {
  $do = $_GET["do"];
  if ($do == "edit") {
    $id = $_GET["id"];
    $sql = "SELECT * FROM admins WHERE admin_id =$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    //print_r($row);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $check = 1;
      $admin_name = ($_POST["admin_name"]);
      $admin_email = strtolower($_POST["admin_email"]);
      $admin_password = ($_POST["admin_password"]);
      $admin_type = ($_POST["admin_type"]);
      if ($admin_name == "") {
        $nameErr = ("Tên không được để trống");
        $check = 0;
      }
      if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = ("$admin_email không phải là địa chỉ email hợp lệ");
        $check = 0;
      }
      if ($admin_email == "") {
        $check = 0;
        $emailErr = "Email không được để trống!";
      }
      if ($admin_password == "") {
        $check = 0;
        $passwordErr = "Mật khẩu không được để trống!";
      }
      if ($admin_type == "0") {
        $admin_type = 0;
      } else {
        $admin_type = 1;
      }
      $image = ($_FILES["admin_image"]);
      $image_folder = "uploads/admin_image/";
      $target_file = $image_folder . uniqid() . basename($image["name"]);
      @move_uploaded_file($image["tmp_name"], $target_file);
      if ($check == 1) {
        $sql2 = "UPDATE admins SET admin_name = '$admin_name', admin_email='$admin_email', admin_password = '$admin_password' , admin_type='$admin_type', admin_image='$target_file'  WHERE admin_id = '$id'";
        if ($conn->query($sql2) === TRUE) {
          echo "Đã cập nhật";
        } else {
          echo "Lỗi: " . $sql2 . "<br>" . $conn->error;
        }
        $conn->close();
        redirect("manage_admins.php");
      }
    }
  }
  //Thêm 
  else if ($do == "add") {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $check = 1;
      $admin_name = ($_POST["admin_name"]);
      $admin_email = strtolower($_POST["admin_email"]);
      $admin_password = ($_POST["admin_password"]);
      $admin_type = ($_POST["admin_type"]);
      if ($admin_name == "") {
        $nameErr = ("Tên không được để trống");
        $check = 0;
      }
      if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = ("$admin_email không phải là địa chỉ email hợp lệ");
        $check = 0;
      }
      if ($admin_email == "") {
        $check = 0;
        $emailErr = "Email không được để trống!";
      }
      if ($admin_password == "") {
        $check = 0;
        $passwordErr = "Mật khẩu không được để trống!";
      }
      if ($admin_type == "0") {
        $admin_type = 0;
      } else {
        $admin_type = 1;
      }
      $image = ($_FILES["admin_image"]);
      $image_folder = "uploads/admin_image/";
      $target_file = $image_folder . uniqid() . basename($image["name"]);
      move_uploaded_file($image["tmp_name"], $target_file);
      if ($check == 1) {
        $sql = "INSERT INTO `admins` (`admin_name`,`admin_email`,`admin_password`, `admin_type`,`admin_image`) VALUES ('$admin_name','$admin_email','$admin_password','$admin_type','$target_file')";
        if (mysqli_query($conn, $sql)) {
          echo "Đã tạo bản ghi mới thành công";
        } else {
          echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
        }
        $conn->close();
        redirect("manage_admins.php");
      }
    }
  }
?>
  <!-- bắt đầu form -->
  <div class="col-md-8 mt-5 col-12 offset-md-2">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Tài khoản quản lý</h4>
      </div>
      <div class="card-content">
        <div class="card-body">
          <form class="form form-horizontal" method="POST" enctype="multipart/form-data">
            <div class="form-body">
              <div class="row">
                <div class="col-md-4">
                  <label>Tên</label>
                </div>
                <div class="col-md-8">
                  <div class="form-group has-icon-left">
                    <div class="position-relative row justify-content-center align-items-center d-flex">
                      <input name="admin_name" type="text" class="form-control col-9 mb-2" placeholder="Tên" value="<?php if ($do == "edit") {
                                                                                                                        echo $row['admin_name'];
                                                                                                                      } ?>" style="border: 1px solid #dce7f1 !important;" id="first-name-icon">
                      <div class="form-control-icon col-3 ">
                        <i class="bi bi-person" style="position: absolute; top:-10px; left: -20px;"></i>
                      </div>
                      <div style="color:red"><?php echo @$nameErr;  ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <label>Email</label>
                </div>
                <div class="col-md-8">
                  <div class="form-group has-icon-left">
                    <div class="position-relative row justify-content-center align-items-center d-flex">
                      <input name="admin_email" type="email" class="form-control col-9 mb-2" placeholder="Email" value="<?php if ($do == "edit") {
                                                                                                                          echo $row['admin_email'];
                                                                                                                        } ?>" style="border: 1px solid #dce7f1 !important;" id="first-name-icon">
                      <div class="form-control-icon col-3">
                        <i class="bi bi-envelope" style="position: absolute; top:-10px; left: -20px;"></i>
                      </div>
                      <div style="color:red"><?php echo @$emailErr;  ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <label>Mật khẩu</label>
                </div>
                <div class="col-md-8">
                  <div class="form-group has-icon-left">
                    <div class="position-relative row justify-content-center align-items-center d-flex">
                      <input name="admin_password" type="password" class="form-control col-9 mb-2" placeholder="Mật khẩu" value="<?php if ($do == "edit") {
                                                                                                                                    echo $row['admin_password'];
                                                                                                                                  }
                                                                                                                                  ?>" style="border: 1px solid #dce7f1 !important;">
                      <div class="form-control-icon col-3">
                        <i class="bi bi-lock" style="position: absolute; top:-10px; left: -20px;"></i>
                      </div>
                      <div style="color:red"><?php echo @$passwordErr;  ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <label>Quyền</label>
                </div>
                <div class="col-md-8">
                  <div class="form-group has-icon-left">
                    <div class="position-relative row justify-content-center align-items-center d-flex">
                      <select name="admin_type" class="form-control col-9 mb-2" style="border: 1px solid #dce7f1 !important;">
                        <option value="0" <?php if ($do == "edit") {
                                            echo $row['admin_type'] == 0 ? "selected" : "";
                                          } ?>> Quản trị viên</option>
                        <option value="1" <?php if ($do == "edit") {
                                            echo $row['admin_type'] == 1 ? "selected" : "";
                                          } ?>> Siêu quản trị viên </option>
                      </select>
                      <div class="form-control-icon col-3">
                        <i class="bi bi-phone" style="position: absolute; top:-10px; left: -20px;"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <label>Hình ảnh</label>
                </div>
                <div class="col-md-8">
                  <div class="form-group has-icon-left">
                    <div class="position-relative row justify-content-center align-items-center d-flex">
                      <input name="admin_image" type="file" class="form-control col-9 mb-2" style="border: 1px solid #dce7f1 !important;" placeholder="Type">
                      <div class="form-control-icon col-3">
                        <i class="bi bi-phone" style="position: absolute; top:-10px; left: -20px;"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary me-1 mb-1">Gửi</button>
                  <button type="reset" class="btn btn-light-secondary me-1 mb-1">Đặt lại</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php
}
$conn->close();
?>
<!--  đây -->
<link rel="stylesheet" href="assets/vendors/simple-datatables/style.css">
<link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
<link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
<link rel="stylesheet" href="assets/css/app.css">
<link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">
<?php
if (!isset($_GET['do'])) { ?>
  <main class="main users chart-page" id="skip-target">
    <div class="container">
      <!-- kết thúc form -->
      <!-- bắt đầu bảng -->
      <div class="row">
        <div class="col-lg-12">
          <div class="users-table table-wrapper">
            <table class="table table-striped" id="table1">
              <button class="btn btn-primary" style="float: right;margin:10px 50px 0px 10px;">
                <a href="manage_admins.php?do=add">Thêm quản trị viên </a>
              </button>
              <thead>
                <tr class="users-table-info">
                  <th>ID Quản trị viên</th>
                  <th>Tên Quản trị viên</th>
                  <th>Email Quản trị viên</th>
                  <th>Mật khẩu Quản trị viên</th>
                  <th>Loại</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($admins as $key => $admin) { ?>
                  <tr>
                    <td>
                      <label class="users-table__checkbox">
                        <input type="checkbox" class="check">
                        <div class="categories-table-img">
                          <?php echo isset($admin['admin_id']) ? $admin['admin_id'] : ''; ?>
                        </div>
                      </label>
                    </td>
                    <td class="px-6 py-4">
                      <div class="text-sm text-gray-900 flex justify-center items-center">
                        <img src=<?php echo isset($admin['admin_image']) ? $admin['admin_image'] : ''; ?> class="mr-3" width="50px" alt="">
                        <?php echo $admin["admin_name"]; ?>
                      </div>
                    </td>
                    <td>
                      <div class="pages-table-img">
                        <?php echo isset($admin['admin_email']) ? $admin['admin_email'] : ''; ?>
                      </div>
                    </td>
                    <td><span class="badge-pending"><?php echo isset($admin['admin_password']) ? $admin['admin_password'] : ''; ?> </span></td>
                    <td><?php echo isset($admin['admin_type']) && $admin['admin_type'] == 0 ? "Quản trị viên" : "Siêu quản trị viên"; ?></td>
                    <td>
                      <div class="table-data-feature">
                        <button class="btn btn-success" title="chỉnh sửa">
                          <a href="manage_admins.php?do=edit&id=<?php echo $admin['admin_id'] ?>"> Chỉnh sửa </a>
                        </button>
                        <button class="btn btn-danger" title="xóa">
                          <a href="manage_admins.php?delete=<?php echo $admin['admin_id'] ?>"> Xóa </a>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>
<?php } ?>
<?php include "./includes/footer.php";
ob_end_flush(); ?>