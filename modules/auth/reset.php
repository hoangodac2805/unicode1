<?php
if (!defined('_INCODE'))
    die('Access Dined...');
layout('header-login');

// File này chứa chức năng kích hoạt tài khoản
echo '<div class="container text-center">';
$token = getBody()['token'];

if (!empty($token)) {
    $tokenQuery = firstRaw("SELECT id,fullname,email FROM users WHERE forgotToken = '$token'");
    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        $userEmail = $tokenQuery['email'];
        if (isPost()) {
            $body = getBody();
            $errors = [];
            if (empty(trim($body['password']))) {
                $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
            } else {
                if (strlen(trim($body['password'])) < 8) {
                    $errors['password']['min'] = 'Mật khẩu không được nhỏ hơn 8 ký tự';
                }
            }
            if (empty(trim($body['password']))) {
                $errors['password_confirm']['required'] = 'Mật khẩu bắt buộc phải nhập';
            } else {
                if (trim($body['password']) != trim($body['password_confirm'])) {
                    $errors['password_confirm']['match'] = 'Mật khẩu không trùng nhau';
                }
            }

            if (empty($errors)) {
                //Xử lý update mật khẩu
                $passwordHash = password_hash($body['password'], PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $passwordHash,
                    'forgotToken' => null,
                    'updatedAt' => date('Y-m-d H:i:s')
                ];
                $updateStatus = update('users', $dataUpdate, "id=$userId");
                if ($updateStatus) {
                    setFlashData('msg', 'Thay đổi mật khẩu thành công');
                    setFlashData('msg_type', 'success');
                    //Gửi mail thông báo khi đổi xong
                    $subject = 'Bạn vừa đổi mật khẩu';
                    $content = 'Chúc mừng bạn đã đổi mật khẩu thành công!';
                    sendMail($userEmail, $subject, $content);
                    redirect('?module=auth&action=login');
                } else {
                    setFlashData('msg', 'Lỗi hệ thống, không thể đổi mât khẩu');
                    setFlashData('msg_type', 'danger');
                    redirect('?module=auth&action=reset&token=' . $token);
                }
            } else {
                setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setFlashData('msg_type', 'danger');
                setFlashData('errors', $errors);
                redirect('?module=auth&action=reset&token=' . $token);
            }
            // redirect('?module=auth&action=reset&token='.$token);
        }
        $msg = getFlashData('msg');
        $msg_type = getFlashData('msg_type');
        $errors = getFlashData('errors');
        ?>

        <div class="row text-left">
            <div class="col-6" style="margin: 20px auto">
                <h3 class="text-center text-uppercase">Đặt lại mật khẩu</h3>
                <?php getMsg($msg, $msg_type); ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="">Mật khẩu mới</label>
                        <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
                        <?php form_error('password', $errors) ?>
                    </div>
                    <div class="form-group">
                        <label for="">Xác nhận mật khẩu mới</label>
                        <input name="password_confirm" type="password" class="form-control" placeholder="Mật khẩu">
                        <?php form_error('password_confirm', $errors) ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
                    <hr>
                    <span class="float-left "><a class="text-warning" href="?module=auth&action=login">Đăng nhập</a></span>
                    <span class="float-right"><a href="?module=auth&action=register">Đăng kí mới</a></span>
                    <input type='hidden' name='token' value='<?php echo $token ?>'>
                </form>
            </div>
        </div>

        <?php
    } else {
        getMsg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }
} else {
    getMsg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');

}
echo '</div>';
layout('footer-login');
?>