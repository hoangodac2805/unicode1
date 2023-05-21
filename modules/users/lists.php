<?php
if (!defined('_INCODE'))
    die('Access Dined...');

$data = [
    'pageTitle' => 'Quản lý người dùng'
];
layout('header', $data);


//Xử lý phân trang
//1. Xác định số lượng bản ghi 1 trang
$perPage = 2;
//2.Tính số lượng 
$allUserNum = getRows("SELECT id FROM users");
$maxPage = ceil($allUserNum / $perPage);
//3.Xử lý số trang dựa vào phương thức GET

if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if (!$page >= 1 || $page > $maxPage) {
        $page = 1;
    }
} else {
    $page = 1;
}
//4,Tính toán offset trong Limit dựa bào biến $page
$offset = ($page - 1) * $perPage;
echo $offset;
//Truy vấn lấy tất cả bản ghi 
$listAllUser = getRaw("SELECT * FROM users ORDER BY fullname LIMIT $offset,$perPage");

?>
<div class="container">
    <hr>
    <h1 class="text-center">Quản lý người dùng</h1>
    <div class="mb-2 mt-2">
        <a href="#" class="btn btn-success">Thêm người dùng <i class="fa fa-plus"></i></a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Trạng thái</th>
                <th width="5%">Sửa</th>
                <th width="5%">Xoá</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($listAllUser) && is_array($listAllUser)): ?>
                <?php foreach ($listAllUser as $index => $userInfo): ?>
                    <tr>
                        <td>
                            <?php echo ($page - 1) * $perPage + $index + 1 ?>
                        </td>
                        <td>
                            <?php echo $userInfo['fullname'] ?>
                        </td>
                        <td>
                            <?php echo $userInfo['email'] ?>
                        </td>
                        <td>
                            <?php echo $userInfo['phone'] ?>
                        </td>
                        <td>
                            <?php echo !$userInfo['status'] == 1 ? '<button type="button" class="btn btn-success btn-sm">Kích hoạt</button>' : 'Đã kích hoạt' ?>
                        </td>
                        <td><a href="#" class="btn btn-primary"><i class="fa fa-edit"></i></a></td>
                        <td><a href="#" onclick="return confirm('Are you sure?')" class="btn btn-danger"><i
                                    class="fa fa-trash"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">
                        <div class="alert alert-danger">Không có người dùng</div>
                    </td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo ($page - 1 < 1)? 'd-none' : '' ?>">
                <a class="page-link" href="?module=users&page=<?php echo $page - 1?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <?php
            $begin= $page -2;
            if($begin <1){
                $begin = 1;
            }
            $end = $page +2;
            if($end > $maxPage){
                $end = $maxPage;
            }
            for ($index =   $begin; $index <= $end; $index++): ?>
                <li class="page-item <?php echo $index==$page? 'active' : '' ?>"><a class="page-link" href="?module=users&page=<?php echo $index ?>">
                        <?php echo $index ?>
                    </a></li>
            <?php endfor; ?>


            <li class="page-item <?php echo ($page + 1 > $maxPage)? 'd-none' : '' ?>">
                <a class="page-link" href="?module=users&page=<?php echo $page + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </nav>
    <hr>

</div>
<?php

layout('footer');