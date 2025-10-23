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
        <h2>게시글 작성</h2>
        <form action="/board/insert" method="post">
            <input type="hidden" id="u_num" name="u_num" value="<?= htmlspecialchars($u_num) ?>">
            <div class="form-group">
                <label for="u_id">작성자</label>
                <input type="text" id="u_id" name="u_id" value="<?= htmlspecialchars($u_id) ?>" readonly>
            </div>
            <div class="form-group">
                <label for="b_title">제목</label>
                <input type="text" id="b_title" name="b_title" placeholder="제목을 입력하세요" required>
            </div>
            <div class="form-group">
                <label for="b_title">카테고리</label>
                <select id="category_idx" name="category_idx" style="width: 103%;" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category->category_idx) ?>"><?= htmlspecialchars($category->category_name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="b_content">내용</label>
                <textarea id="b_content" name="b_content" placeholder="내용을 입력하세요" style="height: 200px;" required></textarea>
            </div>
            <div class="button-group">
                <a href="/board/board_list" class="cancel-btn">취소</a>
                <button type="submit" class="submit-btn">등록</button>
            </div>
        </form>
    </div>
</body>
</html>