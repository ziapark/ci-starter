<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>게시글 상세보기</title>
    <style>
        body {margin: 0;padding: 40px 20px;display: flex;justify-content: center;}
    </style>
    <?php echo $css_optimizer; ?>
</head>
<body>
    <div class="detail-container">
        <div class="detail-title"><?= htmlspecialchars($board->b_title) ?></div>
        <div class="detail-meta">
            작성자: <?= htmlspecialchars($board->u_id) ?> | 카테고리: <?= htmlspecialchars($board->category_name) ?> | 작성일: <?= date('Y-m-d', strtotime($board->b_date)) ?>
        </div>
        <div class="detail-content">
            <?= nl2br(htmlspecialchars($board->b_content)) ?>
        </div>
        <hr>
        <h3>댓글</h3>
        <div id="comment-list">
            <?php 
                $groupColors = ['#ffffff', '#f9f9f9']; 
                $groupBorders = ['#e0e0e0', '#e0e0e0'];
                $colorCount = count($groupColors);
            ?>

            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $index => $comment): ?>
                    <?php
                        if ($index == 0) {
                            echo "<div class='comment-thread'>";
                        }else if ($comment->c_depth == 0) { 
                        echo '</div>';
                        echo "<div class='comment-thread'>";
                    }
                    ?>

                    <div id="comment_<?= $comment->c_num ?>" 
                         class="comment <?= $comment->c_depth > 0 ? 'reply' : '' ?>" 
                         style="margin-left: <?= min($comment->c_depth, 4) * 25 ?>px;">
                        
                        <div class="comment-header">
                            <strong><?= htmlspecialchars($comment->u_id) ?></strong>
                            <span>(<?= date('Y-m-d H:i', strtotime($comment->c_date)) ?>)</span>
                            <span style="font-size: 12px; color: #888; margin-left: 6px;">Lv.<?= $comment->c_depth + 1 ?></span>
                            
                            <?php if($this->session->userdata('u_num') && $this->session->userdata('u_num') == $comment->u_num): ?>
                                <button class="reply-toggle-btn" onclick="delete_comment(<?= $comment->c_num ?>)" style="color: #dc3545; margin-left: 5px;">삭제</button>
                            <?php endif; ?>
                            
                            <div class="reply-action-group">
                                <?php if ($this->session->userdata('u_num')): ?>
                                    <button class="reply-toggle-btn" onclick="toggle_reply_form(<?= $comment->c_num ?>)">답글 쓰기</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <p><?= nl2br(htmlspecialchars($comment->c_content)) ?></p>
                        
                        <form id="reply_comment_form_<?= $comment->c_num ?>" class="reply-form" style="display:none;">
                            <input type="hidden" name="b_num" value=<?= $board->b_num ?>>
                            <input type="hidden" name="c_parent" value="<?= $comment->c_num ?>">
                            <input type="hidden" name="c_depth" value="<?= $comment->c_depth + 1 ?>">
                            <textarea name="c_content" rows="2" placeholder="답글을 입력하세요"></textarea>
                            <button type="submit">등록</button>
                            <button type="button" class="reply-toggle-btn" style="background: #6c757d; text-decoration-line: none;" onclick="toggle_reply_form(<?= $comment->c_num ?>)">취소</button>
                        </form>
                    </div>
                <?php endforeach; ?>
                <?php 
                    if (!empty($comments)) {
                        echo '</div> ';
                    }
                ?>
            <?php else: ?>
                <p>댓글이 없습니다.</p>
            <?php endif; ?>
        </div>

        <?php if ($this->session->userdata('u_num')): ?>
            <form id="first_comment_form" class="comment-form">
                <input type="hidden" name="b_num" value=<?= $board->b_num ?>>
                <input type="hidden" name="c_parent" value="0">
                <input type="hidden" name="c_depth" value="0">
                <textarea name="c_content" rows="3" placeholder="댓글을 입력하세요"></textarea>
                <button type="submit">댓글 등록</button>
            </form>
        <?php else: ?>
            <textarea name="c_content" rows="3" placeholder="댓글을 작성하려면 로그인이 필요합니다." disabled></textarea>
        <?php endif; ?>
        
        <div class="pagination-container">
            <div style="flex:0.4;"></div>

            <div class="pagination">
                <?php if($comment_prev): ?>
                    <a style="background-color: ##dfdfdf;" href="/board/board_detail/<?= $board->b_num ?>?limit_per_page=<?= $limit_per_page ?>&keyword=<?= $keyword ?>&comment_current_page<?= ($comment_current_page - 1)?>">이전</a>
                <?php endif; ?>

                <?php for ($i = $comment_start_page; $i <= $comment_end_page; $i++): ?>
                    <?php if($i == $comment_current_page): ?>
                        <a href="#" class="active"><?= $i ?></a>
                    <?php else: ?>
                        <a href="/board/board_detail/<?= $board->b_num ?>?limit_per_page=<?= $limit_per_page ?>&keyword=<?= $keyword ?>&comment_current_page=<?= $i ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if($comment_next): ?>
                    <a style="background-color: ##dfdfdf;" href="/board/board_detail/<?= $board->b_num ?>?limit_per_page=<?= $limit_per_page ?>&keyword=<?= $keyword ?>&comment_current_page=<?= ($comment_current_page + 1)?>">다음</a>
                <?php endif; ?>
            </div>
        </div>

        <script>
            const isLoggedIn = <?= $this->session->userdata('u_num') ? 'true' : 'false' ?>;
            const loggedInUserNum = <?= $this->session->userdata('u_num') ?>;

            //댓글 작성
            $(document).on('submit', '#first_comment_form', function(e){
                e.preventDefault();
                submit_comment_ajax($(this));
            });

            //답글 작성
            $(document).on('submit', '.reply-form', function(e){
                e.preventDefault();
                submit_comment_ajax($(this));
            });

            //비동기 전송
            function submit_comment_ajax(form){
                const b_num = form.find('input[name="b_num"]').val();
                const c_content = form.find('textarea[name="c_content"]').val().trim();
                const c_parent = form.find('input[name="c_parent"]').val() || 0;
                const c_depth = form.find('input[name="c_depth"]').val() || 0;

                if(!c_content){
                    alert('댓글을 입력하세요');
                    return;
                }

                $.ajax({
                    url: '/comment/insert_comment',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        b_num: b_num,
                        c_content: c_content,
                        c_depth: c_depth,
                        c_parent: c_parent
                    },
                    success: function(data){
                        if(data.success){
                            location.reload(); 
                        }else{
                            alert(data.message);
                        }
                    }
                });
            }

            //답글 쓰기 폼 토글
            function toggle_reply_form(c_num) {
                const form = document.getElementById('reply_comment_form_' + c_num);
                if (form.style.display === 'none') {
                    form.style.display = 'block';
                } else {
                    form.style.display = 'none';
                }
            }
            
            //댓글 삭제
            function delete_comment(c_num){
                if(confirm("정말 삭제하시겠습니까? (답글이 있는 경우 함께 삭제될 수 있습니다.)")){
                    $.ajax({
                        url: '/comment/delete_comment',
                        type: 'POST',
                        dataType: 'json',
                        data: {c_num: c_num},
                        success: function(data){

                            if(data.success){
                                alert('댓글이 삭제되었습니다.');
                                location.reload();
                            }else{
                                alert(data.message);
                            }
                        },
                        error: function(error){
                            console.log( error);
                        
                        }
                    });
                }
            }
        </script>

        <div class="button-group">
            <?php
                $login_u_num = $this->session->userdata('u_num');
                if (!is_null($login_u_num) && $login_u_num == $board->u_num):
            ?>
                <div class="button-group">
                    <a class="edit-btn" href="/board/update_view/<?= htmlspecialchars($board->b_num) ?>?limit_per_page=<?= $limit_per_page ?>&keyword=<?= $keyword ?>">수정</a>
                    <a class="delete-btn" href="/board/delete/<?= htmlspecialchars($board->b_num) ?>">삭제</a>
                </div>
            <?php endif; ?>
            <a class="list-btn" href="/board/board_list?limit_per_page=<?= $limit_per_page ?>&keyword=<?= $keyword ?>">목록</a>
        </div>
    </div>
</body>
</html>