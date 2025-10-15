<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <style>
        body {background-color: #f9f9f9;padding: 40px;}
        h1 {text-align: center;color: #333;margin-bottom: 30px;}
        .action-bar {display: flex;justify-content: space-between;align-items: center;margin-bottom: 20px;}
        .search-box input[type="text"] {padding: 8px 12px;font-size: 14px;border: 1px solid #ccc;border-radius: 4px;width: 200px;}
        .write-button{padding: 8px 16px;font-size: 14px;text-decoration: none;background-color: #007BFF;color: white;border: none;border-radius: 4px;cursor: pointer;}
        .write-button:hover{background-color: #0056b3;}
        table {width: 100%;border-collapse: collapse;background-color: white;box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);}
        thead {background-color: #007BFF;color: white;}
        th, td {padding: 12px 16px;border-bottom: 1px solid #ddd;text-align: center;}
        tbody tr:hover {background-color: #f1f1f1;}
        a {color: #007BFF;text-decoration: none;}
        .pagination {margin-top: 30px;display: flex;justify-content: center;gap: 8px;}
        .pagination a {padding: 8px 12px;background-color: #eee;border-radius: 4px;color: #333;text-decoration: none;font-size: 14px;}
        .pagination a.active {background-color: #007BFF;color: white;}
        .right-action {display: flex;gap: 10px;}
        .login-button {padding: 8px 16px;font-size: 14px;background-color: #28a745;color: white;border: none;border-radius: 4px;cursor: pointer;text-decoration: none;display: inline-block;text-align: center;}
        .login-button:hover {background-color: #218838;}
        .search-button {padding: 8px 16px;font-size: 12px;background-color: #17a2b8;color: white;border: none;border-radius: 4px;cursor: pointer;margin-left: 8px;}
        .search-button:hover {background-color: #138496;}
    </style>
</head>
<body>
    <h1>게시판</h1>

    <div class="action-bar">
        <form method="get" action="/board/search" class="search-box">
            <input type="text" name="keyword" placeholder="검색어를 입력하세요">
            <button type="submit" class="search-button">검색</button>
        </form>

        <div class="right-action">
            <button class="write-button" onclick="add()">글쓰기</button>
            <?php
            if(isset($_SESSION) === false) {session_start();}
            if(isset($_SESSION['u_num']) === false){
            ?>
            <a class="login-button" href="/user/view/login">로그인</a>
            <?php }else{ ?>
            <a class="login-button" href="/user/logout">로그아웃</a>
            <?php } ?>  
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">제목</th>
                <th scope="col">글쓴이</th>
                <th scope="col">작성일</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($board)) : ?>
                <?php foreach ($board as $index => $row): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td>
                            <a href="/board/board_detail/<?php echo $row->b_num; ?>">
                                <?php echo htmlspecialchars($row->b_title); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($row->u_id); ?></td>
                        <td><?= date('Y-m-d', strtotime($row->b_date)); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">게시글이 없습니다.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="pagination">
        <a href="#">&laquo;</a>
        <a href="#">1</a>
        <a href="#" class="active">2</a>
        <a href="#">3</a>
        <a href="#">4</a>
        <a href="#">5</a>
        <a href="#">&raquo;</a>
    </div>
    <script>
            function add(){
                <?php if(isset ($_SESSION['u_num']) === false){ ?>
                    alert('로그인이 필요합니다.');
                    location.href='/user/view/login';
                <?php }else{ ?>
                    location.href='/board/view/board_insert';
                <?php } ?>
            }
    </script>
</body>
</html>