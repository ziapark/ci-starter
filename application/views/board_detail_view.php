<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>게시글 상세보기</title>
    <style>
        body {margin: 0;padding: 40px 20px;display: flex;justify-content: center;}
        .detail-container {padding: 30px;border-radius: 10px;width: 100%;max-width: 800px;}
        .detail-title {font-size: 24px;font-weight: bold;margin-bottom: 10px;color: #333;}
        .detail-meta {font-size: 14px;color: #666;margin-bottom: 20px;border-bottom: 1px solid #ddd;padding-bottom: 10px;}
        .detail-content {font-size: 16px;line-height: 1.6;color: #444;white-space: pre-line;margin-bottom: 30px;}
        .button-group {display: flex;justify-content: flex-end;gap: 10px;}
        .button-group a {padding: 10px 16px;font-size: 14px;border: none;border-radius: 6px;cursor: pointer;text-decoration-line: none;}
        .edit-btn {background-color: #28a745;color: white;}
        .edit-btn:hover {background-color: #218838;}
        .delete-btn {background-color: #dc3545;color: white;}
        .delete-btn:hover {background-color: #c82333;}
        .list-btn {background-color: #6c757d;color: white;}
        .list-btn:hover {background-color: #495057;}
    </style>
</head>
<body>
    <div class="detail-container">
        <div class="detail-title"><?= htmlspecialchars($board->b_title) ?></div>
        <div class="detail-meta">
            작성자: <?= htmlspecialchars($board->u_id) ?> | 작성일: <?= date('Y-m-d', strtotime($board->b_date)) ?>
        </div>
        <div class="detail-content">
            <?= nl2br(htmlspecialchars($board->b_content)) ?>
        </div>
        <div class="button-group">
            <?php
                $login_u_num = $this->session->userdata('u_num');
                if (!is_null($login_u_num) && $login_u_num == $board->u_num):
            ?>
                <div class="button-group">
                    <a class="edit-btn" href="/board/update_view/<?= htmlspecialchars($board->b_num) ?>">수정</a>
                    <a class="delete-btn" href="/board/delete/<?= htmlspecialchars($board->b_num) ?>">삭제</a>
                </div>
            <?php endif; ?>
            <a class="list-btn" href="/board/board_list">목록</a>
        </div>
    </div>
</body>
</html>