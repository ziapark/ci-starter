<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글쓰기</title>
    <style>
        body {margin: 0;padding: 40px 20px;display: flex;justify-content: center;}
        .write-container {padding: 30px;border-radius: 10px;width: 100%;max-width: 800px;}
        .write-container h2 {text-align: center;margin-bottom: 30px;color: #333;}
        .form-group {margin-bottom: 20px;}
        .form-group label {display: block;margin-bottom: 8px;font-weight: bold;color: #555;}
        .form-group input[type="text"], .form-group textarea, .form-group select {width: 100%;padding: 10px 12px;font-size: 14px;border: 1px solid #ccc;border-radius: 6px;resize: vertical;background-color: white;}
        .button-group {display: flex;justify-content: flex-end;gap: 10px;margin-top: 20px;}
        .button-group button, .button-group a {padding: 10px 20px;font-size: 14px;border: none;border-radius: 6px;cursor: pointer;text-decoration: none;}
        .submit-btn {background-color: #28a745;color: white;}
        .submit-btn:hover {background-color: #218838;}
        .cancel-btn {background-color: #6c757d;color: white;}
        .cancel-btn:hover {background-color: #495057;}
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
                <a href="/board/board_list" class="cancel-btn">취소</a>
                <button type="submit" class="submit-btn">수정</button>
            </div>
        </form>
    </div>
</body>
</html>