<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글쓰기</title>
    <?php echo $css_optimizer; ?>
    <style>
        body {margin: 0;padding: 40px 20px;display: flex;justify-content: center;}
    </style>
</head>
<body>
    <div class="write-container">
        <h2>게시글 수정</h2>
        <form action="/board/update" method="post">
            <input type="hidden" id="b_num" name="b_num" value="<?= htmlspecialchars($board->b_num) ?>">
            <div class="form-group">
                <label for="u_id">작성자</label>
                <input type="text" id="u_id" name="u_id" value="<?= htmlspecialchars($board->u_id) ?>" readonly>
            </div>
            <div class="form-group">
                <label for="b_title">제목</label>
                <input type="text" id="b_title" name="b_title" value="<?= htmlspecialchars($board->b_title) ?>" required>
            </div>
            <div class="form-group">
                <label for="b_title">카테고리</label>
                <select id="category_idx" name="category_idx" style="width: 103%;" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category->category_idx) ?>" <?= $category->category_idx == $board->category_idx ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category->category_name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="b_content">내용</label>
                <textarea id="b_content" name="b_content" style="height: 200px;" required><?= htmlspecialchars($board->b_content) ?></textarea>
            </div>
            <div class="button-group">
                <a href="/board/board_detail/<?php echo $board->b_num; ?>?limit_per_page=<?= $limit_per_page ?>&keyword=<?= $keyword ?>" class="cancel-btn">취소</a>
                <button type="submit" class="submit-btn">수정</button>
            </div>
        </form>
    </div>
</body>
</html>