<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>게시판</title>

    <?php echo $css_optimizer; ?>
    
</head>
<body>
    <h1>게시판</h1>
    <div class="action-bar">
        <form method="get" action="/board/board_list?limit_per_page=<?= $limit_per_page ?>" class="search-box">
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
    <div>
        <ul class="nav nav-tabs">
            <li class="nav-item <?= ($current_category == 'all') ? 'active' : '' ?>">
                <a class="nav-link" aria-current="page" href="/board/board_list">전체보기</a>
            </li>
            <?php foreach ($categories as $category): ?>
                <li class="nav-item <?= ($current_category == $category->category_idx) ? 'active' : '' ?>">
                    <a class="nav-link" href="/board/board_list?category_idx=<?= htmlspecialchars($category->category_idx)?>"><?= htmlspecialchars($category->category_name) ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
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
                        <td><?php echo (($current_page - 1) * $limit_per_page) + $index + 1; ?></td>
                        <td>
                            <a href="/board/board_detail/<?php echo $row->b_num; ?>?limit_per_page=<?= $limit_per_page ?>&keyword=<?= $keyword ?>">
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
    <div class="pagination-container">
        <div style="flex: 1;"></div>

        <div class="pagination">
            <?php if ($prev): ?>
                <a href="/board/board_list?current_page=<?= ($current_page - 1 )?>&limit_per_page=<?= $limit_per_page ?>">이전</a>
            <?php endif; ?>

            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                <?php if ($i == $current_page): ?>
                    <a href="#" class="active"><?= $i ?></a>
                <?php else: ?>
                    <a href="/board/board_list?current_page=<?= $i ?>&limit_per_page=<?= $limit_per_page ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        
            <?php if ($next): ?>
                <a href="/board/board_list?current_page=<?= ($current_page + 1) ?>&limit_per_page=<?= $limit_per_page ?>">다음</a>
            <?php endif; ?>
        </div>

        <form method="get" action="/board/board_list" class="limit-box">
            <input type="text" name="limit_per_page" placeholder="게시글 개수를 입력하세요">
            <button type="submit" class="search-button">출력</button>
        </form>
    </div>
    <script>
            function add(){
                <?php if(isset ($_SESSION['u_num']) === false){ ?>
                    alert('로그인이 필요합니다.');
                    location.href='/user/view/login';
                <?php }else{ ?>
                    location.href='/board/insert_view';
                <?php } ?>
            }
    </script>
</body>
</html>