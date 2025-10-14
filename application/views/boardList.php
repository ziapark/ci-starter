<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <style>
        body {background-color: #f9f9f9;padding: 40px;}
        h1 {text-align: center;color: #333;margin-bottom: 30px;}
        .top-bar {display: flex;justify-content: flex-end;margin-bottom: 20px;}
        .top-bar a {padding: 8px 16px;font-size: 14px;background-color: #007BFF;color: white;border: none;border-radius: 4px;cursor: pointer;}
        .action-bar {display: flex;justify-content: space-between;align-items: center;margin-bottom: 20px;}
        .search-box input[type="text"] {padding: 8px 12px;font-size: 14px;border: 1px solid #ccc;border-radius: 4px;width: 200px;}
        .write-button a {padding: 8px 16px;font-size: 14px;text-decoration: none;background-color: #007BFF;color: white;border: none;border-radius: 4px;cursor: pointer;}
        .write-button a:hover, .top-bar a:hover {background-color: #0056b3;}
        table {width: 100%;border-collapse: collapse;background-color: white;box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);}
        thead {background-color: #007BFF;color: white;}
        th, td {padding: 12px 16px;border-bottom: 1px solid #ddd;text-align: center;}
        tbody tr:hover {background-color: #f1f1f1;}
        a {color: #007BFF;text-decoration: none;}
        .pagination {margin-top: 30px;display: flex;justify-content: center;gap: 8px;}
        .pagination a {padding: 8px 12px;background-color: #eee;border-radius: 4px;color: #333;text-decoration: none;font-size: 14px;}
        .pagination a.active {background-color: #007BFF;color: white;}
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="login">로그인</a>
    </div>

    <h1>게시판</h1>

    <div class="action-bar">
        <div class="search-box">
            <input type="text" placeholder="검색어를 입력하세요">
        </div>
        <div class="write-button">
            <a href="boardAdd">글쓰기</a>
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


            <?php
                $host = "localhost";
                $dbname = "ci_board";
                $username = "root";
                $password = "";

                $conn = new mysqli($host, $username);

                if ($conn->connect_error) {
                    die("연결 실패: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM board ORDER BY b_num DESC";
                $result = $conn->query($sql);
                $count = 0;

                if (!empty($list)):
                    foreach ($list as $result):?>
                        <tr>
                            <td><?= $row->b_num ?></td>
                            <td><?= $row->title ?></td>
                        </tr>
                    <?php endforeach;
                        else: ?>
                    <tr><td colspan="5">게시글이 없습니다.</td></tr>
                <?php endif;

                $conn->close();?>
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
</body>
</html>