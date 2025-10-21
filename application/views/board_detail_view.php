<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        hr {margin: 40px 0 20px; background: #ddd; height:1px; border:0;}
        #comment-list {margin-top: 20px;} 
        .comment {--depth: 0;position: relative;background-color: #f9f9f9;border-radius: 6px;border-left: none;}
        .comment-header {font-size: 14px; color: #555;display: flex;justify-content: space-between;align-items: center;} 
        .comment p {font-size: 15px; color: #333;margin-top:3px;}
        .reply-action-group {display: flex;gap: 6px;align-items: center;margin-left: auto;}
        .reply-toggle-btn {background: none; color: #007bff; border: none; font-size: 13px; cursor: pointer; padding: 0;} 
        .reply-toggle-btn:hover {text-decoration: underline;} 
        .reply-form, .comment-form {margin-top: 10px;} 
        .reply-form textarea, .comment-form textarea, textarea {width: 97%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;} 
        .reply-form button, .comment-form button {margin-top: 5px; background-color: #007bff; color: white; border: none; padding: 8px 14px; border-radius: 4px; cursor: pointer;} 
        .reply-form button:hover, .comment-form button:hover {background-color: #0056b3;} 
        .login-comment-info {color: #888; margin-top: 20px;}
        .reply-list {margin-left: 20px;padding-left: 10px;border-left: 2px dashed #ccc;border-radius: 6px;}
        .reply-list-flat {margin-left: 0;}
        .pagination {display: flex;justify-content: center;margin-top: 30px;gap: 8px;flex-wrap: wrap;}
        .pagination a {display: inline-block;padding: 8px 12px;font-size: 14px;color: #007bff;background-color: #f1f1f1;border-radius: 5px;text-decoration: none;transition: all 0.2s ease-in-out;border: 1px solid #ccc;}
        .pagination a:hover {background-color: #e2e6ea;color: #0056b3;}
        .pagination a.active {background-color: #007bff;color: white;pointer-events: none;font-weight: bold;border-color: #007bff;}
    </style>
</head>
<body>
    <div class="detail-container">
        <!-- 게시글 정보 -->
        <div class="detail-title"><?= htmlspecialchars($board->b_title) ?></div>
        <div class="detail-meta">
            작성자: <?= htmlspecialchars($board->u_id) ?> | 카테고리: <?= htmlspecialchars($board->category_name) ?> | 작성일: <?= date('Y-m-d', strtotime($board->b_date)) ?>
        </div>
        <div class="detail-content">
            <?= nl2br(htmlspecialchars($board->b_content)) ?>
        </div>

        <!-- 댓글 정보 -->
        <hr>
        <h3>댓글</h3>
        <div id="comment-list">
            <?php if (!empty($comments)): ?>
                <?php foreach (array_reverse($comments) as $comment): ?>
                    <div id="comment_<?= $comment->c_num ?>" class="comment">
                        <div class="comment-header">
                            <strong><?= htmlspecialchars($comment->u_id) ?></strong>
                            <span>(<?= date('Y-m-d H:i', strtotime($comment->c_date)) ?>)</span>
                            <span style="font-size: 12px; color: #888; margin-left: 6px;">Lv.1</span>
                            <?php if($this->session->userdata('u_num') && $this->session->userdata('u_num') == $comment->u_num): ?>
                                <button class="reply-toggle-btn" onclick="delete_comment(<?= $comment->c_num ?>)" style="color: #dc3545; margin-left: 5px;">삭제</button>
                            <?php endif; ?>
                            <div class="reply-action-group">
                                <?php if ($this->session->userdata('u_num')): ?>
                                    <button class="reply-toggle-btn" onclick="toggle_reply_form(<?= $comment->c_num ?>)">답글 쓰기 |</button>
                                <?php endif; ?>
                                <button class="reply-toggle-btn" onclick="reply_list(<?= $board->b_num ?>, <?= $comment->c_num ?>, <?= (int)$comment->c_depth + 1 ?>)">답글 보기</button>
                            </div>
                        </div>
                        <p><?= nl2br(htmlspecialchars($comment->c_content)) ?></p>
                        
                        <!-- 답글 작성 폼 -->
                        <form id="reply_comment_form_<?= $comment->c_num ?>" class="reply-form" style="display:none;">
                            <input type="hidden" id="b_num" name="b_num" value=<?= $board->b_num ?>>
                            <input type="hidden" id="c_parent" name="c_parent" value="<?= $comment->c_num ?>">
                            <input type="hidden" id="c_depth" name="c_depth" value="<?= $comment->c_depth + 1 ?>">
                            <textarea id="c_content" name="c_content" rows="2" placeholder="답글을 입력하세요"></textarea>
                            <button type="submit">등록</button>
                            <button type="button" class="reply-toggle-btn" style="background: #6c757d; text-decoration-line: none;" onclick="toggle_reply_form(<?= $comment->c_num ?>)">취소</button>
                        </form>

                        <!-- 답글 출력 -->
                        <div id="reply_list_display_<?= $comment->c_num ?>" class="reply-list" style="display:none;"></div>
                        
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>댓글이 없습니다.</p>
            <?php endif; ?>
        </div>

        <!-- 댓글 작성 -->
        <?php if ($this->session->userdata('u_num')): ?>
            <form id="first_comment_form" class="comment-form">
                <input type="hidden" id="b_num" name="b_num" value=<?= $board->b_num ?>>
                <textarea id="c_content" name="c_content" rows="3" placeholder="댓글을 입력하세요"></textarea>
                <button type="submit">댓글 등록</button>
            </form>
        <?php else: ?>
            <textarea name="c_content" rows="3" placeholder="댓글을 작성하려면 로그인이 필요합니다." disabled></textarea>
        <?php endif; ?>

        <!-- 댓글 페이징 -->
        <div class="pagination">
            <?php if($prev): ?>
                <a href="/board/board_detail/<?= $board->b_num ?>?current_page=<?= ($current_page - 1 ) ?>">이전</a>
            <?php endif; ?>

            <?php for($i = $start_page; $i <= $end_page; $i++): ?>
                <?php if($i == $current_page): ?>
                    <a href="#" class="active"><?= $i ?></a>
                <?php else: ?>
                    <a href="/board/board_detail/<?= $board->b_num ?>?current_page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if($next): ?>
                <a href="/board/board_detail/<?= $board->b_num ?>?current_page=<?= ($current_page + 1 ) ?>">다음</a>
            <?php endif; ?>
        </div>

        <script>
            //로그인 확인
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
                            if(c_parent>0){
                                form.find('textarea[name="c_parent"]').val('');
                                form.hide();
                                
                                if(data.new_comment){
                                    append_new_comment(data.new_comment);
                                }else{
                                    reply_list(b_num, c_parent, c_depth, true);
                                }    
                            }else{
                                location.reload();
                            }
                        }else{
                            alert(data.message);
                        }
                    }
                });
            }

            //답글 쓰고 실시간 출력
            function append_new_comment(reply){
                let replyContainerClass = 'reply-list';
                if (parseInt(reply.c_depth) >= 4) { 
                    replyContainerClass = 'reply-list-flat';
                }
                
                let replyHTML = `
                    <div id="comment_${reply.c_num}" class="comment">
                        <div class="comment-header">                                                                   
                            <strong>${reply.u_id}</strong>
                            <span>(${reply.c_date})</span>
                            <span style="font-size: 12px; color: #888; margin-left: 6px;">Lv.${parseInt(reply.c_depth) + 1}</span>
                            ${
                                (loggedInUserNum && loggedInUserNum == reply.u_num) 
                                ? `<button class="reply-toggle-btn" onclick="delete_comment(${reply.c_num})" style="color: #dc3545; margin-left: 5px;">삭제</button>` 
                                : '' 
                            }
                            <div class="reply-action-group">
                `;
                
                if (isLoggedIn) {
                    replyHTML += `<button class="reply-toggle-btn" onclick="toggle_reply_form(${reply.c_num})" style="background: none; color: #007bff; padding: 0;">답글 쓰기 |</button>`;
                }
                replyHTML += `
                                <button id= "reply_list_${reply.c_num}" class="reply-toggle-btn" onclick="reply_list(${reply.b_num}, ${reply.c_num}, ${parseInt(reply.c_depth) + 1})" style="background: none; color: #007bff; padding: 0;">답글 보기</button>
                            </div>                                       
                        </div>
                        <p>${reply.c_content.replace(/\n/g, '<br>')}</p>
                        <form id="reply_comment_form_${reply.c_num}" class="reply-form" style="display:none;">
                            <input type="hidden" name="b_num" value=${reply.b_num}>
                            <input type="hidden" name="c_parent" value="${reply.c_num}">
                            <input type="hidden" name="c_depth" value="${parseInt(reply.c_depth) + 1}">
                            <textarea name="c_content" rows="2" placeholder="답글을 입력하세요"></textarea>
                            <button type="submit">등록</button>
                            <button type="button" class="reply-toggle-btn" style="background: #6c757d; text-decoration-line: none;" onclick="toggle_reply_form(${reply.c_num})">취소</button>
                        </form>
                        <div id="reply_list_display_${reply.c_num}" class="${replyContainerClass}" style="display:none;"></div>
                    </div>
                `;

                let list = document.getElementById('reply_list_display_' + reply.c_parent);
                if(list){
                    list.innerHTML += replyHTML;
                    list.style.display = 'block';
                }
            }

            //답글 쓰기
            function toggle_reply_form(c_num) {
                const form = document.getElementById('reply_comment_form_' + c_num);
                if (form.style.display === 'none') {
                    form.style.display = 'block';
                } else {
                    form.style.display = 'none';
                }
            }

            //답글 보기
            function reply_list(b_num, p_num, b_depth, forceOpen = false){
                $.ajax({
                    url: '/comment/reply_list',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        b_num: b_num,
                        p_num: p_num,
                        b_depth: b_depth
                    },
                    success: function(data){
                        if(data.success){                          
                            //답글 출력
                            let list = document.getElementById('reply_list_display_' + p_num);

                            const wasOpen = (list.style.display === 'block');

                            list.innerHTML = '';

                            data.reply_list.forEach(function(reply){
                                let replyContainerClass = 'reply-list';
                                if (parseInt(reply.c_depth) >= 4) { 
                                    replyContainerClass = 'reply-list-flat';
                                }

                                let replyHTML = `
                                    <div id="comment_${reply.c_num}" class="comment">
                                        <div class="comment-header">                                                                   
                                            <strong>${reply.u_id}</strong>
                                            <span>(${reply.c_date})</span>
                                            <span style="font-size: 12px; color: #888; margin-left: 6px;">Lv.${parseInt(reply.c_depth) + 1}</span>
                                            ${
                                                (loggedInUserNum && loggedInUserNum == reply.u_num) 
                                                ? `<button class="reply-toggle-btn" onclick="delete_comment(${reply.c_num})" style="color: #dc3545; margin-left: 5px;">삭제</button>` 
                                                : '' 
                                            }
                                            <div class="reply-action-group">
                                `;
                                
                                if (isLoggedIn) {
                                    replyHTML += `<button class="reply-toggle-btn" onclick="toggle_reply_form(${reply.c_num})" style="background: none; color: #007bff; padding: 0;">답글 쓰기 |</button>`;
                                }
                                replyHTML += `
                                                <button id= "reply_list_${reply.c_num}" class="reply-toggle-btn" onclick="reply_list(${reply.b_num}, ${reply.c_num}, ${parseInt(reply.c_depth) + 1})" style="background: none; color: #007bff; padding: 0;">답글 보기</button>
                                            </div>                                       
                                        </div>
                                        <p>${reply.c_content.replace(/\n/g, '<br>')}</p>
                                        <form id="reply_comment_form_${reply.c_num}" class="reply-form" style="display:none;">
                                            <input type="hidden" name="b_num" value=${reply.b_num}>
                                            <input type="hidden" name="c_parent" value="${reply.c_num}">
                                            <input type="hidden" name="c_depth" value="${parseInt(reply.c_depth) + 1}">
                                            <textarea name="c_content" rows="2" placeholder="답글을 입력하세요"></textarea>
                                            <button type="submit">등록</button>
                                            <button type="button" class="reply-toggle-btn" style="background: #6c757d; text-decoration-line: none;" onclick="toggle_reply_form(${reply.c_num})">취소</button>
                                        </form>
                                        <div id="reply_list_display_${reply.c_num}" class="${replyContainerClass}" style="display:none;"></div>
                                    </div>
                                `;

                                list.innerHTML += replyHTML;
                            });

                            if (forceOpen) {
                                list.style.display = 'block';
                            } else {
                                if(wasOpen){
                                    list.style.display = 'none';
                                }else{
                                    list.style.display = 'block';
                                    if(data.reply_list.length === 0){
                                        alert('더 이상 답글이 없습니다.');
                                    }
                                }
                            }
                        }else{
                            alert(data.message);
                        }
                    }
                });
            }
            
            //댓글 삭제
            function delete_comment(c_num){
                if(confirm("정말 삭제하시겠습니까?")){
                    $.ajax({
                        url: '/comment/delete_comment',
                        type: 'POST',
                        dataType: 'json',
                        data: {c_num: c_num},
                        success: function(data){
                            if(data.success){
                                $('#comment_' + c_num).remove();
                                alert('댓글이 삭제되었습니다.');
                            }else{
                                alert(data.message);
                            }
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