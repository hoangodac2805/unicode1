<?php
if (!defined('_INCODE'))
    die('Access Dined...');
$data = ['pageTitle' => 'Đăng nhập hệ thống'];
layout('header-login', $data);
//Kiểm tra trạng thái đăng nhập

if(isLogin()){
    redirect('?module=users');
}
//Xử lý đăng nhập
if (isPost()) {
    $body = getBody();
    if (!empty(trim($body['email'])) && !empty(trim($body['password']))) {
        //Kiểm tra đăng nhập
        $email = $body['email'];
        $password = $body['password'];
        //Truy vấn lấy thông tin user theo email
        $userQuery = firstRaw("SELECT id,password FROM users WHERE email = '$email'");
        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            if (password_verify($password, $passwordHash)) {
                //Tạo token login
                $tokenLogin = sha1(uniqid() . time());
                //Insert dữ liệu vào bảng logintoken
                $dataToken = [
                    'user_id' => $userId,
                    'token' => $tokenLogin,
                    'createdAt' => date('Y-m-d H:i:s')
                ];
                $insertTokenStatus = insert('logintoken', $dataToken);
                if ($insertTokenStatus) {
                    //Lưu loin token vào session
                    setSession('loginToken', $tokenLogin);
                    //Chuyển hướng qua trang quản lý user
                    // redirect('?module=auth&action=login');
                    
                } else {
                    setFlashData('msg', 'Lỗi hệ thống, bạn không thể đăng nhập vào lúc này');
                    setFlashData('msg_type', 'danger');
                    // redirect('?module=auth&action=login');
                }
            } else {
                setFlashData('msg', 'Mật khẩu không chính xác');
                setFlashData('msg_type', 'danger');
                // redirect('?module=auth&action=login');
            }

    } else {
            setFlashData('msg', 'Email không tồn tại trong hệ thống');
            setFlashData('msg_type', 'danger');
            // redirect('?module=auth&action=login');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập email và mật khẩu');
        setFlashData('msg_type', 'danger');
        // redirect('?module=auth&action=login');

    }
    redirect('?module=auth&action=login');

}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
<div class="row">
    <div class="col-6" style="margin: 20px auto">
        <h3 class="text-center text-uppercase">Đăng nhập hệ thống</h3>
        <?php getMsg($msg, $msg_type); ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email">
            </div>
            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input name="password" type="text" class="form-control" placeholder="Mật khẩu">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
            <hr>
            <span class="float-left "><a class="text-warning" href="?module=auth&action=forgot">Quên mật khẩu</a></span>
            <span class="float-right"><a href="?module=auth&action=register">Đăng kí mới</a></span>
        </form>
    </div>
</div>
<?php
layout('footer-login');
?>