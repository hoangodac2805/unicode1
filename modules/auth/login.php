<?php
if (!defined('_INCODE'))
    die('Access Dined...');
$data = ['pageTitle' => 'Đăng nhập hệ thống'];
layout('header-login', $data);
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
<div class="row">
    <div class="col-6" style="margin: 20px auto">
        <h3 class="text-center text-uppercase">Đăng nhập hệ thống</h3>
        <?php getMsg($msg,$msg_type); ?>
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