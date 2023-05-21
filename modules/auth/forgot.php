<?php
if (!defined('_INCODE'))
    die('Access Dined...');

$data = ['pageTitle' => 'Đặt lại mật khẩu'];
layout('header-login', $data);
//Kiểm tra trạng thái đăng nhập

if (isLogin()) {
    redirect('?module=users');
}
//Xử lý đăng nhập
if (isPost()) {
    $body = getBody();
    if (!empty(trim($body['email']))) {
        $email = $body['email'];
        $queryUser = firstRaw("SELECT id FROM users WHERE email='$email'");
        if (!empty($queryUser)) {
            $userId = $queryUser['id'];

            //Tạo forgot token
            $forgotToken = sha1(uniqid() . time());
            $dataUpdate = [
                'forgotToken' => $forgotToken
            ];
            $updateStatus = update('users', $dataUpdate, "id=$userId");
            if ($updateStatus) {
                //Thiết lập gửi mail
                $linkReset = _WEB_HOST_ROOT . '?module=auth&action=reset&token=' . $forgotToken;
                $subject = 'Yêu cầu khôi phục mật khẩu';
                $content = 'Chào bạn: ' . $email . '<br>';
                $content .= 'Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn.Vui lòng click vào link sau để khôi phục : ' . '<br>';
                $content.=$linkReset.'<br>';
                $content.='Trân trọng';
                //Tiến hành gửi mail
                $sendStatus = sendMail($email,$subject,$content);
                if($sendStatus){
                    setFlashData('msg', 'Vui lòng kiểm tra email để xem hướng dẫn đặt lại mật khẩu');
                    setFlashData('msg_type', 'success');
                }else{

                }
            } else {
                setFlashData('msg', 'Lỗi hệ thống, bạn không thể sử dụng chức năng này');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Địa chỉ email không tồn tại trong hệ thống');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập địa chỉ email');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=auth&action=forgot');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
<div class="row">
    <div class="col-6" style="margin: 20px auto">
        <h3 class="text-center text-uppercase">Đặt lại mật khẩu</h3>
        <?php getMsg($msg, $msg_type); ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email">
        
            </div>

            <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
            <hr>
            <span class="float-left "><a class="text-warning" href="?module=auth&action=login">Đăng nhập</a></span>
            <span class="float-right"><a href="?module=auth&action=register">Đăng kí mới</a></span>
        </form>
    </div>
</div>
<?php
layout('footer-login');
?>